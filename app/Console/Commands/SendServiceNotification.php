<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Helpers\OneSignal;

class SendServiceNotification extends Command
{
    protected $signature = 'notification:service';
    protected $description = 'Send a OneSignal notification to users with cars that need service or are due soon';

    public function handle()
    {
        $titleOverdue = 'Service Reminder';
        $titleUpcoming = 'Upcoming Service Reminder';
        $currentDate = Carbon::now();
        $oneMonthFromNow = $currentDate->copy()->addMonth();

        $users = User::has('userCars')
            ->with(['userCars' => function ($query) {
                $query->with(['CarModel.Cars', 'services' => function ($serviceQuery) {
                    $serviceQuery->orderBy('created_at', 'desc');
                }]);
            }])
            ->get();

        if ($users->isEmpty()) {
            $this->warn("No users with cars found to notify.");
            return self::FAILURE;
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($users as $user) {
            $overdueCars = [];
            $dueSoonCars = [];

            foreach ($user->userCars as $userCar) {
                foreach ($userCar->services as $service) {

                    if ($service->status === 'inactive') {
                        $this->info("Skipping inactive service for car: " . ($userCar->CarModel?->Cars?->model ?? 'Unknown Model'));
                        continue; // Skip this service if it's inactive
                    }


                    // Convert string dates to Carbon instances
                    $nextServiceDate = $service->next_service ? Carbon::parse($service->next_service) : null;
                    $nextServiceKm = $service->next_service_km ?? null; // Ensure proper assignment of next_service_km
                    $car_km = $userCar->car_km ?? null; // Ensure car_km is assigned properly

                    $purchaseDate = $userCar->purchase_date ? Carbon::parse($userCar->purchase_date) : null;

                    // Skip if next_service is the same as purchase_date
                    if ($nextServiceDate && $purchaseDate && $nextServiceDate->equalTo($purchaseDate)) {
                        continue;
                    }

                    // Check overdue service based on date
                    if ($nextServiceDate && $nextServiceDate->lessThan($currentDate)) {
                        $overdueCars[] = $userCar->CarModel?->Cars?->model ?? 'vehicle';
                        break; // No need to check further services for this car
                    }

                    // Check overdue service based on mileage (next_service_km vs car_km)
                    if ($nextServiceKm !== null && $car_km !== null && $car_km >= $nextServiceKm) {
                        $overdueCars[] = $userCar->CarModel?->Cars?->model ?? 'vehicle';
                        break; // No need to check further services for this car
                    }

                    // Check if service is due in one month
                    if ($nextServiceDate && $nextServiceDate->greaterThan($currentDate) && $nextServiceDate->lessThanOrEqualTo($oneMonthFromNow)) {
                        $dueSoonCars[] = $userCar->CarModel?->Cars?->model ?? 'vehicle';
                    }
                }
            }

            // Send notifications for overdue services
            if (!empty($overdueCars)) {
                $content = count($overdueCars) === 1
                    ? "Your {$overdueCars[0]} needs to be serviced!"
                    : "These cars need to be serviced: " . implode(', ', $overdueCars);
                $this->sendNotification($user->id, $titleOverdue, $content);
                $successCount++;
                continue; // Don't send 'due soon' if there's an overdue one
            }

            // Send notifications for upcoming services
            if (!empty($dueSoonCars)) {
                $content = count($dueSoonCars) === 1
                    ? "Your {$dueSoonCars[0]} will need service in one month!"
                    : "These cars will need service in one month: " . implode(', ', $dueSoonCars);
                $this->sendNotification($user->id, $titleUpcoming, $content);
                $successCount++;
            }
        }

        $this->comment("Notification process completed: $successCount succeeded, $failureCount failed.");
        return ($failureCount === 0) ? self::SUCCESS : self::FAILURE;
    }

    private function sendNotification($userId, $title, $content)
    {
        if (OneSignal::sendNotificationTo($userId, $title, $content)) {
            $this->info("Notification sent to user {$userId}");
        } else {
            $this->error("Failed to send notification to user {$userId}");
        }
    }
}

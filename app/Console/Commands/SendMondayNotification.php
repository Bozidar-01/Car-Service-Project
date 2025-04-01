<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\OneSignal;
use App\Models\User;

class SendMondayNotification extends Command
{
    protected $signature = 'notification:monday';
    protected $description = 'Send a OneSignal notification to users with cars every Monday';

    public function handle()
    {
        $title = 'Monday Reminder';

        // Fetch users who have at least one car
        $users = User::has('userCars')->with('userCars.CarModel.Cars')->get(); // Eager load userCars

        if ($users->isEmpty()) {
            $this->warn("No users with cars found to notify.");
            return self::FAILURE;
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($users as $user) {
            $carCount = $user->userCars->count();

            if ($carCount === 1) {
                $car = $user->userCars->first();
                $content = "Happy Monday! Don’t forget to update the km of your {$car->carModel->Cars->model}. 🚘";
            } else {
                $content = "Happy Monday! Don’t forget to update your cars mileage. 🚘";
            }

            $success = OneSignal::sendNotificationTo($user->id, $title, $content);

            if ($success) {
                $successCount++;
                $this->info("Notification sent successfully to user {$user->id}.");
            } else {
                $failureCount++;
                $this->error("Failed to send notification to user {$user->id}.");
            }
        }

        $this->comment("Notification process completed: $successCount succeeded, $failureCount failed.");

        return ($failureCount === 0) ? self::SUCCESS : self::FAILURE;
    }
}

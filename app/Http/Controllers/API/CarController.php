<?php

namespace App\Http\Controllers\API;

use App\Helpers\FileUpload;
use App\Http\Controllers\BaseController;
use App\Models\CarModel;
use App\Models\CarModels;
use App\Models\Cars;
use App\Models\completedServices;
use App\Models\DefaultService;
use App\Models\Mods;
use App\Models\Service;
use App\Models\UserCars;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Carbon\Carbon;
use function Pest\Laravel\get;
use function PHPUnit\Framework\greaterThanOrEqual;

class CarController extends BaseController
{
    public function getCars()
    {
        $userId = auth()->user()->id;

        // Get IDs of cars the user already has
        $userCarIds = UserCars::where('user_id', $userId)
            ->pluck('car_model_id')
            ->toArray();

        // Fetch cars not in user_cars and with car models
        $cars = Cars::whereNotIn('id', $userCarIds)
            ->whereHas('carModels') // Only cars with at least one car model
            ->get();

        // Process image URLs
        foreach ($cars as $car) {
            if ($car->image) {
                $mediaPath = $car->image;
                $car->image = FileUpload::getFullUrl("uploads/{$mediaPath}");
            }
        }

        $data = [
            'cars' => $cars,
            'userCars' => count($userCarIds),
        ];

        return $this->responseSuccess($data, 'cars', 'Cars fetched successfully', 200);
    }


    public function getCarModel($id)
    {
        $model = CarModel::where("car_id", $id)->get();


        return $this->responseSuccess($model, 'car-models', 'car models fetched successfully', 200);

    }

    public function addNewUserCar(Request $request)
    {
        $request->validate([
            'car_model_id' => 'required',
            'engine' => 'required',
            'color' => 'required',
            'manufacture_date' => 'required',
        ]);

        $userID = auth()->user()->id;

        $userCar = new UserCars();



        $userCar->user_id = $userID;
        $userCar->car_model_id = $request->car_model_id;
        $userCar->engine = $request->engine;
        $userCar->color = $request->color;
        $userCar->manufacture_date = $request->manufacture_date;

        $userCar->save();

        $cars = UserCars::all();
        if ($cars->count() == 1) {
            $cars->first()->update(['main_car' => 1]);
            $userCar->save();
        }



        return $this->responseSuccess($userCar, 'car-added', 'Car Added Successfully', 200);

    }

    public function getUserCars()
    {
        $userID = auth()->user()->id;

        // Fetch user cars with their related CarModels and Cars
        $cars = UserCars::where('user_id', $userID)
            ->orderByDesc('main_car') // Main car first
            ->orderBy('id')
            ->with(['CarModel.Cars']) // Eager load relationships
            ->get();


        // Add full image URL to each car
        foreach ($cars as $car) {
            if ($car->carModel && $car->carModel->Cars) {
                $car->image = FileUpload::getFullUrl("uploads/{$car->carModel->Cars->image}");
            }
        }

        return $this->responseSuccess($cars, 'cars', 'cars fetched successfully', 200);
    }

    public function addNewCarDetails(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_cars,id',
            'car_km' => 'required|numeric',
            'car_price' => 'required|numeric',
            'purchase_date' => 'required|date',
        ]);

        $userID = auth()->user()->id;

        // Find the user car
        $userCar = UserCars::where('id', $request->id)->firstOrFail();

        // Update car details
        $userCar->car_km = $request->car_km;
        $userCar->car_price = $request->car_price;
        $userCar->purchase_date = $request->purchase_date;
        $userCar->save();

        // Fetch all default services
        $defaultServices = DefaultService::all();

        // Add default services to the services table
        foreach ($defaultServices as $defaultService) {
            Service::create([
                'user_car_id' => $userCar->id,
                'user_id' => $userID,
                'default_service_id' => $defaultService->id,
                'price' => null,
                'status' => 'active',
                'last_serviced' => null,
                'next_service' => $request->purchase_date,
                'next_service_km' =>  0,
                'product_used' => null,
            ]);
        }

        return $this->responseSuccess($userCar, 'car-updated', 'Car Updated Successfully with Default Services', 200);
    }

    public function getDefaultServices()
    {
        $defaultServices = DefaultService::all();

        return $this->responseSuccess($defaultServices, 'default-services', 'default services fetched successfully', 200);
    }

    public function getAllServices($carID)
    {
        $userID = auth()->user()->id;
        $services = Service::with('defaultService')->where('user_id', $userID)->where('user_car_id', $carID)->get();

        return $this->responseSuccess($services, 'services', 'services fetched successfully', 200);
    }

    public function getActiveServices($carID)
    {
        $userID = auth()->user()->id;
        $currentDate = Carbon::now();

        $car = UserCars::where('id', $carID)->firstOrFail();

        $services = Service::with('defaultService')
            ->where('user_id', $userID)
            ->where('user_car_id', $carID)
            ->where('status', 'active')
            ->where(function ($query) use ($currentDate, $car) {
                $query->where('next_service', '<=', $currentDate)
                    ->orWhere('next_service_km', '<=', $car->car_km);
            })
            ->get();


        return $this->responseSuccess($services, 'services', 'services fetched successfully', 200);
    }

    public function getCompletedServices($carID)
    {
        $userID = auth()->user()->id;

        $services = completedServices::where('user_car_id', $carID)
            ->where('user_id', $userID)
            ->get()
            ->map(function ($completed) {
                return $completed->service;
            });

        return $this->responseSuccess($services, 'services', 'services fetched successfully', 200);
    }



    public function markServiceCompleted(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric',
            'service_id' => 'required|exists:services,id',
        ]);

        $userId = auth()->user()->id;

        $service = Service::where('user_id', $userId)
            ->where('id', $request->service_id)
            ->firstOrFail();

        $car = UserCars::where('id', $service->user_car_id)->firstOrFail();
        $defaultService = DefaultService::where('id', $service->default_service_id)->first();

        // Update service
        $service->status = 'completed';
        $service->price = $request->price;
        $service->product_used = $request->product_used;
        $service->last_serviced = Carbon::parse($request->custom_date) ?? Carbon::today();

        if ($service->default_service_id) {
            $service->next_service = $service->last_serviced->copy()->addMonths($defaultService->date_interval);
            $service->last_service_km = $request->custom_km ?? $car->car_km;
            $service->next_service_km = $service->last_service_km + $defaultService->service_interval;
        } else {
            $customService = is_string($service->custom_service)
                ? json_decode($service->custom_service, true)
                : (array)$service->custom_service;
            $months = (int) $customService['date_interval'];
            $service->next_service = $service->last_serviced->copy()->addMonths($months);
            $service->last_service_km = $request->custom_km ?? $car->car_km;
            $service->next_service_km = $service->last_service_km + $customService['service_interval'];
        }

        // Keep service active if km/date is already passed
        if ($car->car_km >= $service->next_service_km || $service->next_service->lte(Carbon::today())) {
            $service->status = 'active';
        }

        $service->save();

        // Prepare completed service payload
        $completedServicePayload = array_merge(
            $service->toArray(),
            ['default_service' => $defaultService, 'status' => 'completed'] // Force status completed here
        );

        // Save to completedServices
        $completedService = new CompletedServices();
        $completedService->user_id = $userId;
        $completedService->user_car_id = $car->id;
        $completedService->service = $completedServicePayload; // Always completed
        $completedService->save();

        return $this->responseSuccess($service, 'service-completed', 'Service Completed Successfully', 200);
    }


    public function toggleService(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'status' => 'required|string',
        ]);

        $userId = auth()->user()->id;

        // Find the specific service for the authenticated user
        $service = Service::where('user_id', $userId)
            ->where('id', $request->service_id)
            ->firstOrFail();

        // Update service details
        $service->status = $request->input('status');
        $service->save();

        return $this->responseSuccess($service, 'status-changed', 'Service Status Changed Successfully', 200);
    }

    public function addCustomService(Request $request)
    {
        $request->validate([
            'custom_service' => 'required',
            'user_car_id' => 'required'
        ]);

        $car = UserCars::where('id', $request->user_car_id)->firstorfail();

        $service = new Service();
        $user_id = auth()->user()->id;
        $service->user_id = $user_id;
        $service->custom_service = $request->custom_service;
        $service->next_service = $car->purchase_date;
        $service->next_service_km = 0;
        $service->last_service_km = 0;
        $service->user_car_id = $request->user_car_id;
        $service->save();

        return $this->responseSuccess($service, 'service-added', 'Service Added Successfully', 200);

    }

    public function updateService(Request $request)
    {
        $request->validate([
            'custom_service' => 'required',
            'id' => 'required',
            'user_car_id' => 'required'
        ]);

        $service = Service::where('id', $request->id)->first();
        $service->custom_service = $request->custom_service;
        $service->user_car_id = $request->user_car_id;
        $service->save();

        return $this->responseSuccess($service, 'service-updated', 'Service Updated Successfully', 200);

    }

    public function checkCarsInNeedOfService()
    {
        $userId = auth()->user()->id;

        // Get user cars with active services
        $userCars = UserCars::where('user_id', $userId)
            ->whereHas('services', function ($query) {
                $query->where('status', 'active');
            })
            ->get();

        // Extract car IDs
        $carIds = $userCars->pluck('id')->toArray();
        $count = count($carIds); // Or $userCars->count()

        return $this->responseSuccess(
            [
                'count' => $count,
                'car_ids' => $carIds
            ],
            'cars-in-need',
            'Cars needing service fetched successfully',
            200
        );
    }


    public function addMod(Request $request)
    {
        $request->validate([
            'user_car_id' => 'required',
            'mod_type' => 'required',
            'price' => 'required',
        ]);

        $userID = auth()->user()->id;

        $mod = new Mods();
        $mod->user_id = $userID;
        $mod->user_car_id = $request->user_car_id;
        $mod->mod_type = $request->mod_type;
        $mod->price = $request->price;

        $mod->save();

        return $this->responseSuccess($mod, 'mods-added', 'Mod Added Successfully', 200);
    }


    public function getAllMods($carID)
    {
        $userID = auth()->user()->id;
        $mods = Mods::where('user_id', $userID)->where('user_car_id', $carID)->get();

        return $this->responseSuccess($mods, 'mods', 'mods fetched successfully', 200);
    }

    public function updateCarKm(Request $request)
    {
        $request->validate([
            'car_km' => 'required',
            'user_car_id' => 'required'
        ]);

        $userId = auth()->user()->id;

        $car = UserCars::where('user_id', $userId)->where('id', $request->user_car_id)->firstorfail();

        $car->car_km = $request->car_km;

        $car->save();

        Service::where('next_service_km', '<=', $car->car_km)
            ->where('status', 'completed')
            ->update([
                'status' => 'active',
                'next_service' => now()
            ]);




        return $this->responseSuccess($car, 'car-km-updated', 'Car KM Updated Successfully', 200);
    }


    public function deleteUserCar($id)
    {

        $userId = auth()->user()->id;

        $car = UserCars::where('user_id', $userId)->where('id', $id)->firstorfail();


        $car->delete();

        return $this->responseSuccess($car, 'car-removed', 'Car Removed Successfully', 200);
    }


    public function makeCarMain($id)
    {
        $userId = auth()->user()->id;

        // Find the car belonging to the authenticated user
        $car = UserCars::where('user_id', $userId)->where('id', $id)->firstOrFail();

        // Set all other cars' main_car to 0
        UserCars::where('user_id', $userId)->update(['main_car' => 0]);

        // Set the selected car as main
        $car->update(['main_car' => 1]);

        // Retrieve all cars, ensuring the main car is first
        $cars = UserCars::where('user_id', $userId)
            ->orderByDesc('main_car') // Main car first
            ->orderBy('id') // Secondary sorting by ID
            ->get();

        return $this->responseSuccess($cars, 'car-main', 'Car Set As Main', 200);

    }


    public function addCompletedService(Request $request)
    {
        $request->validate([
            'custom_service' => 'required|array',
            'user_car_id' => 'required|exists:user_cars,id',
        ]);

        $userId = auth()->user()->id;

        // Create the service record
        $service = new Service();
        $service->user_id = $userId;
        $service->user_car_id = $request->user_car_id;
        $service->custom_service = $request->custom_service;
        $service->status = 'old';
        $service->last_service_km = $request->custom_service['last_service_km'] ?? null;
        $service->next_service_km = $request->custom_service['next_service_km'] ?? null;
        $service->last_serviced = $request->custom_service['last_serviced'] ?? null;
        $service->next_service = $request->custom_service['next_service'] ?? null;
        $service->save();

        // Prepare payload for CompletedServices
        $completedData = [
            'user_id' => $userId,
            'user_car_id' => $service->user_car_id,
            'service' => [
                'default_service' => null,
                'default_service_id' => null,
                'status' => 'completed',
                'last_service_km' => $service->last_service_km,
                'last_serviced' => $service->last_serviced,
                'product_used' => $service->custom_service['product_used'] ?? null,
                'price' => $service->custom_service['price'] ?? null,
                'custom_service' => [
                    'name' => $service->custom_service['name'] ?? null,
                ],
            ],
        ];

        // Save to CompletedServices
        $completedService = CompletedServices::create($completedData);

        return response()->json([
            'success' => true,
            'message' => 'Service added and completed successfully',
            'data' => $completedService
        ], 201);
    }


    public function uploadDoc(Request $request){
        $request->validate([
            'car_document' => 'required',
            'car_id' => 'required',
        ]);

        $userID = auth()->user()->id;

        $car = UserCars::where('user_id',$userID)->where('id',$request->input('car_id'))->firstorfail();

        if($request->file('car_document')) {
            // Use the FileUpload helper to store the file
            $url = FileUpload::storeFile($request->file('car_document'));
            $filename = basename($url); // Extracts the filename from the file URL
            $car->car_document = $filename; // Save only the filename in the database
        }

        if($car->car_document){
            $mediaPath = $car->car_document;
            $car->car_document = FileUpload::getFullUrl("uploads/{$mediaPath}");
        }

        $car->save();

        return $this->responseSuccess($car,'user-update','user updated successfully',200);
    }


}

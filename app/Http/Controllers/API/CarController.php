<?php

namespace App\Http\Controllers\API;

use App\Helpers\FileUpload;
use App\Http\Controllers\BaseController;
use App\Models\CarModel;
use App\Models\CarModels;
use App\Models\Cars;
use App\Models\DefaultService;
use App\Models\Service;
use App\Models\UserCars;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        return $this->responseSuccess($cars, 'cars', 'Cars fetched successfully', 200);
    }


   public function getCarModel($id){
        $model = CarModel::where("car_id",$id)->get();


       return $this->responseSuccess($model,'car-models','car models fetched successfully',200);

   }

   public function addNewUserCar(Request $request){
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


        return $this->responseSuccess($userCar,'car-added','Car Added Successfully', 200);

   }

    public function getUserCars()
    {
        $userID = auth()->user()->id;

        // Fetch user cars with their related CarModels and Cars
        $cars = UserCars::where('user_id', $userID)
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
            'purchase_date' => 'required|date',
        ]);

        $userID = auth()->user()->id;

        // Find the user car
        $userCar = UserCars::where('id', $request->id)->firstOrFail();

        // Update car details
        $userCar->car_km = $request->car_km;
        $userCar->purchase_date = $request->purchase_date;
        $userCar->save();

        // Fetch all default services
        $defaultServices = DefaultService::all();

        // Add default services to the services table
        foreach ($defaultServices as $defaultService) {
            // Check if the service already exists for this car to avoid duplicates
            $existingService = Service::where('user_car_id', $userCar->id)
                ->where('default_service_id', $defaultService->id)
                ->exists();

            if (!$existingService) {
                Service::create([
                    'user_car_id' => $userCar->id,
                    'user_id' => $userID,
                    'default_service_id' => $defaultService->id,
                    'price' => null, // Or set from $defaultService if available
                    'status' => 'active',
                    'last_serviced' => null,
                    'next_service' => $request->purchase_date,
                    'product_used' => null,
                ]);
            }
        }

        return $this->responseSuccess($userCar, 'car-updated', 'Car Updated Successfully with Default Services', 200);
    }

    public function getDefaultServices(){
        $defaultServices = DefaultService::all();

        return $this->responseSuccess($defaultServices,'default-services','default services fetched successfully',200);
    }

    public function getActiveServices(){
        $userID = auth()->user()->id;
        $services = Service::with('defaultService')->where('user_id',$userID)->where('status','active')->get();

        return $this->responseSuccess($services,'services','services fetched successfully',200);
    }


    public function markServiceCompleted(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric',
            'product_used' => 'required|string',
            'service_id' => 'required|exists:services,id', // Added to target specific service
        ]);

        $userId = auth()->user()->id;

        // Find the specific service for the authenticated user
        $service = Service::where('user_id', $userId)
            ->where('id', $request->service_id)
            ->firstOrFail();

        // Update service details
        $service->status = 'completed';
        $service->price = $request->price;
        $service->product_used = $request->product_used;
        $service->last_serviced = Carbon::today(); // Set to today's date
        $service->save();

        return $this->responseSuccess($service, 'service-completed', 'Service Completed Successfully', 200);
    }
}

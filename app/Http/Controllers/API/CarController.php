<?php

namespace App\Http\Controllers\API;

use App\Helpers\FileUpload;
use App\Http\Controllers\BaseController;
use App\Models\CarModel;
use App\Models\CarModels;
use App\Models\Cars;
use App\Models\UserCars;
use Faker\Provider\Base;
use Illuminate\Http\Request;

class CarController extends BaseController
{
    public function getCars(){
        $cars = Cars::all();
        foreach ($cars as $car){
            if($car->image){
                $mediaPath = $car->image;
                $car->image = FileUpload::getFullUrl("uploads/{$mediaPath}");
            }
        }
        return $this->responseSuccess($cars,'cars','cars fetched successfully',200);
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
}

<?php

namespace App\Http\Controllers\API;

use App\Helpers\FileUpload;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CarBrandResource;
use App\Models\CarBrand;
use App\Models\UserCars;
use Illuminate\Http\Request;

class CarBrandsController extends BaseController
{
    public function getBrands()
    {
        $userId = auth()->user()->id;

        // Get user-owned car IDs
        $userCarIds = UserCars::where('user_id', $userId)
            ->pluck('car_model_id') // Adjust column name if different
            ->toArray();

        // Get brands with cars not owned by the user
        $brands = CarBrand::whereHas('cars', function ($query) use ($userCarIds) {
            $query->whereNotIn('id', $userCarIds);
        })->get();


        foreach ($brands as $brand) {
            if ($brand->logo) {
                $mediaPath = $brand->logo;
                $brand->logo = FileUpload::getFullUrl("uploads/{$mediaPath}");
            }
        }

        return $this->responseSuccess($brands, 'car-brands', 'Car Brands shown successfully', 200);
    }



}

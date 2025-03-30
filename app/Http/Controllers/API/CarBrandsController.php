<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CarBrandResource;
use App\Models\CarBrand;
use Illuminate\Http\Request;

class CarBrandsController extends BaseController
{
  public function getBrands(){
    $brands =  CarBrand::all();

      return $this->ResponseSuccess($brands,'car-brands','Car Brands shown successfully',200);
  }




}

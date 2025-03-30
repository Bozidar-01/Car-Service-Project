<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\CarBrand;
use Illuminate\Http\Request;

class CarBrandsController extends BaseController
{
  public function getBrands(){
    $response =  CarBrand::all();


      return $this->ResponseSuccess($response,'','',200);
  }

}

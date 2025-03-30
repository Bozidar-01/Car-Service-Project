<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\CarBrandsController;
use Illuminate\Support\Facades\Route;


//auth routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);


});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::prefix('cars')->group(function () {
    Route::get('/', [CarBrandsController::class, 'getBrands']);
});


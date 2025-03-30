<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\CarBrandsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CarController;

//auth routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('carBrands')->group(function () {
        Route::get('/', [CarBrandsController::class, 'getBrands']);
    });

    Route::prefix('cars')->group(function () {
        Route::get('/', [CarController::class, 'getCars']);
        Route::get('/getUserCars', [CarController::class, 'getUserCars']);
        Route::get('/models/{id}', [CarController::class, 'getCarModel']);
        Route::post('/addUserCar', [CarController::class, 'addNewUserCar']);
        Route::post('/updateUserCar', [CarController::class, 'addNewCarDetails']);
        Route::get('/defaultServices', [CarController::class, 'getDefaultServices']);
        Route::get('/activeServices', [CarController::class, 'getActiveServices']);
        Route::post('/completeService', [CarController::class, 'markServiceCompleted']);

    });

});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);



<?php

use App\Http\Controllers\API\userController;
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
        Route::post('/uploadDocs', [CarController::class, 'uploadDoc']);
        Route::post('/addUserCar', [CarController::class, 'addNewUserCar']);
        Route::post('/updateUserCar', [CarController::class, 'addNewCarDetails']);
        Route::get('/defaultServices', [CarController::class, 'getDefaultServices']);
        Route::get('/activeServices/{id}', [CarController::class, 'getActiveServices']);
        Route::post('/completeService', [CarController::class, 'markServiceCompleted']);
        Route::post('/toggleService', [CarController::class, 'toggleService']);
        Route::get('/completedServices/{id}', [CarController::class, 'getCompletedServices']);
        Route::post('/addCustomService', [CarController::class, 'addCustomService']);
        Route::get('/getAllServices/{id}', [CarController::class, 'getAllServices']);
        Route::post('/updateService', [CarController::class, 'updateService']);
        Route::get('/countServiceCars', [CarController::class, 'checkCarsInNeedOfService']);
        Route::post('/addMod', [CarController::class, 'addMod']);
        Route::get('/getAllMods/{id}', [CarController::class, 'getAllMods']);
        Route::post('/updateCarKm', [CarController::class, 'updateCarKm']);
        Route::get('/deleteUserCar/{id}', [CarController::class, 'deleteUserCar']);
        Route::get('/makeCarMain/{id}', [CarController::class, 'makeCarMain']);
        Route::post('/addCompletedService', [CarController::class, 'addCompletedService']);


    });

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'getUserData']);
        Route::post('/update', [UserController::class, 'updateUser']);
        Route::post('/updatePic', [UserController::class, 'updatePic']);


    });


});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);



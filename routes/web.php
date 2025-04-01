<?php

use App\Http\Controllers\CarsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarBrandsController;
use App\Http\Controllers\CarModelsController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//Route::get('/', [CarBrandsController::class, 'sendNotif']);

// Car Brands and Cars Resources (open to general users if needed)


// Dashboard Route (with 'auth' and 'verified' middleware)
Route::get('/dashboard', function (Request $request) {
    if(auth()->user()->role == 'admin'){
    return redirect('admin/car_brands');
    }else{
        return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes (Prefixed with 'admin', Role-based access control)
Route::group([
    'prefix' => 'admin', // Prefix for these routes
    'middleware' => ['auth', 'verified', 'role'], // Apply role middleware for 'admin' role
], function () {
    Route::resource('car_brands', CarBrandsController::class);
    Route::resource('cars', CarsController::class);
    // Cars Routes under Car Brands
    Route::get('/car_brands/{brand}/cars', [CarsController::class, 'index'])->name('cars.index');
    Route::get('/car_brands/{brand}/cars/create', [CarsController::class, 'create'])->name('cars.create');
    Route::post('/car_brands/{brand}/cars/create/store', [CarsController::class, 'store'])->name('cars.store');
    Route::delete('/car_brands/{brand}/cars/{cars}/delete', [CarsController::class, 'destroy'])->name('cars.destroy');
    Route::get('/car_brands/{brand}/cars/{cars}/edit', [CarsController::class, 'edit'])->name('cars.edit');
    Route::put('/car_brands/{brand}/cars/{cars}/edit/update', [CarsController::class, 'update'])->name('cars.update');

    // Car Models Routes under Cars
    Route::get('/car_brands/{brand}/cars/{cars}/models', [CarModelsController::class, 'index'])->name('models.index');
    Route::post('/car_brands/{brand}/cars/{cars}/models/store', [CarModelsController::class, 'store'])->name('models.store');
    Route::get('/car_brands/{brand}/cars/{cars}/models/{model}/edit', [CarModelsController::class, 'edit'])->name('models.edit');
    Route::put('/car_brands/{brand}/cars/{cars}/models/{model}/update', [CarModelsController::class, 'update'])->name('models.update');
    Route::delete('/car_brands/{brand}/cars/{cars}/models/{model}/delete', [CarModelsController::class, 'destroy'])->name('models.destroy');
});

// Profile Routes (for authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes (Default Laravel Authentication Routes)
require __DIR__ . '/auth.php';

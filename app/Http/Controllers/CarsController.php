<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarsRequest;
use App\Models\CarBrand;
use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($brand)
    {
        $cars = Cars::where('car_brand_id', $brand)->get();
        $brandName = CarBrand::findOrFail($brand)->name;
        $brandID = CarBrand::findOrFail($brand)->id;
        $cars->map(function ($carBrand) {
            $carBrand->image_url = $carBrand->image ? Storage::url($carBrand->image) : null;
            return $carBrand;
        });
        return view('admin.car_brands.cars.index', compact('cars', 'brandName', 'brandID'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($brandID)
    {
        $brandName = CarBrand::findOrFail($brandID)->name;

        return view('admin.car_brands.cars.create',compact('brandName','brandID'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarsRequest $request,$brandID)
    {
        $request->validate([
            'model' => 'required|string|max:255',
            'year_of_production' => 'required|string|max:255',
            'image' => 'required|image|max:2048', // Example: max 2MB
        ]);
        $car = new Cars();
        $car->car_brand_id = $brandID;
        $car->model = $request->model;
        $car->year_of_production = $request->year_of_production;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $car->image = $path;
        }
//        dd($brandID);
        $car->save();
        return redirect()->route('cars.index', ['brand' => $brandID])->with('success', 'Car brand added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cars $cars)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($brand,Cars $cars)
    {
        $cars->image_url = $cars->image ? Storage::url($cars->image) : null;

        return view('admin.car_brands.cars.edit', compact('cars','brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarsRequest $request, $brand, Cars $cars)
    {
        try {
            $data = $request->validated();
            // Compare values to skip unnecessary updates
            if (isset($data['model']) && $data['model'] === $cars->model) {
                unset($data['model']);
            }
            if (isset($data['year_of_production']) && $data['year_of_production'] === $cars->year_of_production) {
                unset($data['year_of_production']);
            }

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $data['image'] = $path;
            }
            $cars->update($data); // Attempt to update the car
            return redirect()->route('cars.index', ['brand' => $brand])
                ->with('success', 'Car has been successfully updated!');
        } catch (\Exception $e) {
            // Handle the exception and redirect back with an error message
            return redirect()->back()->with('error', 'Failed to update the car. Please try again.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($brand,Cars $cars)
    {
        $cars->delete();
        return redirect()->route('cars.index', ['brand' => $brand])
            ->with('success', 'Car has been successfully deleted!');
    }


}

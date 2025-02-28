<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarModelRequest;
use App\Models\CarModel;
use App\Models\Cars;
use Illuminate\Http\Request;

class CarModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($brand,$cars)
    {
        $models = CarModel::where('car_id', $cars)->get();
        $car = Cars::where('id', $cars)->first();
        return view('admin.car_brands.cars.models.index',compact('models','cars','brand','car'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarModelRequest $request, $brand, $cars)
    {
        $model = new CarModel();
        $model->manufacture_date = $request->manufacture_date;
        $model->car_id = $cars;
        $model->colors =request('color');
        $engine = [
            'type' => request('type'),
            'power' => request('power'),
        ];

        $model->engines = $engine;
        $model->save();
        return redirect()->back()->with('success', 'Car Model added!');

    }

    /**
     * Display the specified resource.
     */
    public function show(CarModel $carModels)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($brand, $cars, CarModel $model)
    {
        $car = Cars::where('id', $cars)->first();
        return view('admin.car_brands.cars.models.edit', compact('model','cars','brand','car'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarModelRequest $request, CarModel $carModel, $brand, $cars, $model)
    {
        // Retrieve the specific car model record
        $data = $carModel::where('id', $model)->first();

        // Update attributes on the retrieved model
        $data->manufacture_date = $request->manufacture_date;
        $data->colors = request('color'); // Assign directly from the request

        // Prepare and assign the engine array
        $engine = [
                'type' => request('type'),
                'power' => request('power'),
        ];
        $data->engines = $engine; // Assign the engines

        // Save the updated data
        $data->save();

        return redirect()->route('models.index',['brand' => $brand, 'cars' => $cars])
            ->with('success','Model updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($brand, $cars, CarModel $model)
    {
        $model->delete();

        return redirect()->back()->with('success', 'Car Model deleted!');
    }
}

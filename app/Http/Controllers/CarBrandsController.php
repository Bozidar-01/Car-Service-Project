<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarBrandRequest;
use App\Models\CarBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarBrandsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {

        // Retrieve all car brands
        $carBrands = CarBrand::paginate(5);

        // Generate file URLs for each car brand and add a logo_url field to the collection
        $carBrands->map(function ($carBrand) {
            $carBrand->logo_url = $carBrand->logo ? Storage::url($carBrand->logo) : null;
//            dd($carBrand->logo_url);
            return $carBrand;
        });

        // Pass the collection to the view
        return view('admin.car_brands.index', compact('carBrands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.car_brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'logo' => 'required|image|max:2048', // Example: max 2MB
        ]);

        $carBrand = new CarBrand();
        $carBrand->name = $request->name;
        $carBrand->country = $request->country;

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $carBrand->logo = $path;
        }

        $carBrand->save();

        return redirect()->route('car_brands.index')->with('success', 'Car brand added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CarBrand $carBrand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarBrand $carBrand)
    {
            $carBrand->logo_url = $carBrand->logo ? Storage::url($carBrand->logo) : null;
        return view('admin.car_brands.edit', compact('carBrand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarBrandRequest $request, CarBrand $carBrand)
    {
        // Save the validated data
        $data = $request->validated();

        // Compare values to skip unnecessary updates
        if (isset($data['name']) && $data['name'] === $carBrand->name) {
            unset($data['name']);
        }
        if (isset($data['country']) && $data['country'] === $carBrand->country) {
            unset($data['country']);
        }

        // Handle logo upload if it exists
        if ($request->hasFile('logo')) {
            $filePath = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $filePath;
        }

        // Update only the modified fields
        $carBrand->update($data);

        return redirect()->route('car_brands.index')->with('success', 'Car Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarBrand $carBrand)
    {
        $carBrand->delete();

        return redirect()->route('car_brands.index')->with('success', 'Car Brand deleted successfully.');
    }
}

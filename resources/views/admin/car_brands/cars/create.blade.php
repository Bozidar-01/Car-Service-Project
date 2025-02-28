<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Cars for ') }} {{ $brandName }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <form action="{{ route('cars.store', ['brand' => $brandID]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Model -->
            <div class="mb-4">
                <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                <input type="text" name="model" id="model" value="{{ old('model') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       placeholder="Enter car model" required>
                @error('model')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Year of Production -->
            <div class="mb-4">
                <label for="year_of_production" class="block text-sm font-medium text-gray-700">Year of Production</label>
                <input type="text" name="year_of_production" id="year_of_production" value="{{ old('year_of_production') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       placeholder="Enter year of production (e.g., 2023)" required>
                @error('year_of_production')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Car Image (optional)</label>
                <input type="file" name="image" id="image"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-sm file:font-medium file:bg-gray-50 hover:file:bg-gray-100"
                       accept="image/*">
                @error('image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Allowed types: jpeg, png, jpg, gif, svg. Max size: 2MB.</p>
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Submit
                </button>

            </div>
        </form>
    </div>
</x-app-layout>

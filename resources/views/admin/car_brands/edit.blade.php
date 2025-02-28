<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $carBrand->name }}
        </h2>
    </x-slot>

    <!-- Form Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('car_brands.update',$carBrand->id)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-6">
                                <!-- Name -->
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ $carBrand->name }}" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Country -->
                                <x-input-label for="country" :value="__('Country')" />
                                <x-text-input value="{{ $carBrand->country }}" id="country" class="block mt-1 w-full" type="text" name="country"  value="{{ $carBrand->country }}" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Company Logo -->
                                <x-input-label for="logo" :value="__('Company Logo')" />
                                <!-- Display Current Logo (if exists) -->
                                @if($carBrand->logo_url)
                                    <img src="{{ $carBrand->logo_url }}" alt="Car Brand Logo" class="h-20 w-20 object-cover">
                                @endif

                                <!-- File Input for Uploading New Logo -->
                                <x-text-input id="logo" class="block mt-2 w-full" type="file" name="logo" accept="image/*"></x-text-input>
                                <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-danger-button type="submit" class="ms-4">
                                    {{ __('Submit') }}
                                </x-danger-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

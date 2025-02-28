<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cars for ') }} {{ $brandName }}
        </h2>
    </x-slot>

    <!-- Form Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('cars.store', ['brand' => $brandID]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <!-- Model -->
                                <x-input-label for="model" :value="__('Model')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model')" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Year of Production -->
                                <x-input-label for="year_of_production" :value="__('Year of Production')" />
                                <x-text-input id="year_of_production" class="block mt-1 w-full" type="text" name="year_of_production" :value="old('year_of_production')" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('year_of_production')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Image -->
                                <x-input-label for="image" :value="__('Car Image (optional)')" />
                                <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" accept="image/*"></x-text-input>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
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

    <!-- Display Cars Section -->
    <div class="pb-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table table-striped myTables" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Model</th>
                            <th>Year of Production</th>
                            <th>Image</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cars as $car)
                            <tr>
                                <td style="text-align: center">{!! $car->id !!}</td>
                                <td style="text-align: center">{!! $car->model !!}</td>
                                <td style="text-align: center">{!! $car->year_of_production !!}</td>
                                <td>
                                    <img src="{{ $car->image_url }}" alt="{{ $car->model }}" style="width: 100px; height: 100px; object-fit: cover; margin: 0 auto">
                                </td>
                                <td>
                                    <div style="display: flex; gap: 3px; justify-content: end">
                                        <div>
                                            <a>
                                                <x-primary-button type="button" class="ms-3" onclick="window.location='{{ route('models.index', ['cars' => $car->id, 'brand' => $brandID ]) }}';">
                                                    {{ __('View Models') }}
                                                </x-primary-button>
                                            </a>
                                        </div>
                                        <div>
                                            <a>
                                                <x-primary-button type="button" class="ms-3" onclick="window.location='{{ route('cars.edit', ['cars' => $car->id, 'brand' => $brandID ]) }}';">
                                                    {{ __('Edit') }}
                                                </x-primary-button>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('cars.destroy', ['cars' => $car->id, 'brand' => $brandID ]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit" class="ms-3" onclick="return confirm('Are you sure you want to delete this car?')">
                                                    {{ __('Delete') }}
                                                </x-danger-button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <!-- Form Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('cars.update', ['brand' => $brand, 'cars'=>$cars]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-6">
                                <!-- Model -->
                                <x-input-label for="model" :value="__('Model')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" value="{{$cars->model}}" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('model')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Year of Production -->
                                <x-input-label for="year_of_production" :value="__('Year of Production')" />
                                <x-text-input id="year_of_production" class="block mt-1 w-full" type="text" name="year_of_production" value="{{$cars->year_of_production}}" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('year_of_production')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Image -->
                                <x-input-label for="image" :value="__('Car Image (optional)')" />
                                @if($cars->image_url)
                                    <img src="{{ $cars->image_url }}" alt="Car Brand Logo" class="h-20 w-20 object-cover">
                                @endif
                                <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" accept="image/*"></x-text-input>
                                <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            </+>
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

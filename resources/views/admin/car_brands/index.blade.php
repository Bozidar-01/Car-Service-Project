<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Car Companies ') }}
        </h2>
    </x-slot>

    <!-- Form Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('car_brands.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <!-- Model -->
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Year of Production -->
                                <x-input-label for="country" :value="__('Country')" />
                                <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Image -->
                                <x-input-label for="logo" :value="__('Company Logo')" />
                                <x-text-input id="logo" class="block mt-1 w-full" type="file" name="logo" accept="image/*"></x-text-input>
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

    <!-- Display Cars Section -->
    <div class="pb-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table table-striped myTables" style="width:100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company</th>
                            <th>Logo</th>
                            <th>Origin</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($carBrands as $carBrand)
                            <tr>
                                <td style="text-align: center">{!! $carBrand->id !!}</td>
                                <td style="text-align: center">{!! $carBrand->name !!}</td>
                                <td>
                                    <img src="{{ $carBrand->logo_url }}" alt="{{ $carBrand->name }}" style="width: 100px; height: 100px; object-fit: cover; margin: 0 auto">
                                </td>
                                <td style="text-align: center">{!! $carBrand->country !!}</td>

                                <td>
                                    <div style="display: flex; gap: 3px; justify-content: end">
                                        <div>
                                            <a href="{{route('cars.index',['brand'=>$carBrand])}}">
                                                <x-primary-button type="button" class="ms-3">
                                                    {{ __('View') }}
                                                </x-primary-button>
                                            </a>
                                        </div>
                                        <div>
                                            <a>
                                                <x-primary-button type="button" class="ms-3" onclick="window.location='{{ route('car_brands.edit',['car_brand'=>$carBrand]) }}';">
                                                    {{ __('Edit') }}
                                                </x-primary-button>

                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('car_brands.destroy', ['car_brand' => $carBrand]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit" class="ms-3" onclick="return confirm('Are you sure you want to delete this car manufacturer?')">
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
                    <div class="mt-4 flex justify-center">
{{--                        {!! $carBrands->links() !!}--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $car->model  }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('models.update',['brand'=>$brand,'cars'=>$cars,'model'=>$model])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-6">
                                <!-- Manufacture Date -->
                                <x-input-label for="manufacture_date" :value="__('Manufacture Date')" />
                                <x-text-input id="manufacture_date" class="block mt-1 w-full" type="date" name="manufacture_date" value="{{$model->manufacture_date}}" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('manufacture_date')" class="mt-2" />
                            </div>
                            <div class="col-3">
                                <!-- Engine Type -->
                                <x-input-label for="name" :value="__('Engine Type')" />
                                <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" value="{{$model->engines['type']}}" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                            <div class="col-3">
                                <!-- Engine Power -->
                                <x-input-label for="name" :value="__('Engine Power')" />
                                <x-text-input id="power" class="block mt-1 w-full" type="text" name="power" value="{{$model->engines['power']}}" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('power')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Color -->
                                <x-input-label for="color" :value="__('Color')" />
                                <x-text-input id="color" class="block mt-1 w-full" type="text" name="color" value="{{$model->colors}}" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('color')" class="mt-2" />
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

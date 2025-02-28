<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $car->model }}
        </h2>
    </x-slot>
    @if(session('success'))
        <div class="alert alert-success" id="alert-box" style="width: 100%; background: green; color:#fff; text-align: center">
            {{ session('success') }}
        </div>

        <script>
            function dismissAlert() {
                const alertBox = document.getElementById('alert-box');
                if (alertBox) {
                    alertBox.remove(); // Remove the alert when the button is clicked
                }
            }
        </script>
    @endif

    <!-- Form Section -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('models.store',['brand'=>$brand,'cars'=>$cars])}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <!-- Manufacture Date -->
                                <x-input-label for="manufacture_date" :value="__('Engine')" />
                                <x-text-input id="manufacture_date" class="block mt-1 w-full" type="date" name="manufacture_date" :value="old('manufacture_date')" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('manufacture_date')" class="mt-2" />
                            </div>
                            <div class="col-3">
                                <!-- Engine -->
                                <x-input-label for="name" :value="__('Engine Type')" />
                                <x-text-input id="engines[engine_type]" class="block mt-1 w-full" type="text" name="type" :value="old('type')" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                            <div class="col-3">
                                <!-- Engine -->
                                <x-input-label for="name" :value="__('Engine Power')" />
                                <x-text-input id="power" class="block mt-1 w-full" type="text" name="power" :value="old('power')" required autofocus></x-text-input>
                                <x-input-error :messages="$errors->get('power')" class="mt-2" />
                            </div>
                            <div class="col-6">
                                <!-- Color -->
                                <x-input-label for="color" :value="__('Color')" />
                                <x-text-input id="color" class="block mt-1 w-full" type="text" name="color" :value="old('color')" required autofocus></x-text-input>
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

    <!-- Display Cars Section -->
    <div class="pb-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table table-striped myTables" style="width:100%">
                        <thead>
                        <tr>
                            <th style="text-align: start; vertical-align: middle;">ID</th>
                            <th style="text-align: start; vertical-align: middle;">Engine</th>
                            <th style="text-align: start; vertical-align: middle;">HP</th>
                            <th style="text-align: start; vertical-align: middle;">Color</th>
                            <th style="text-align: end; vertical-align: middle;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($models as $model)
                            <tr>
                                <td style="text-align: start; vertical-align: middle;" >
                                    {!! $model->id !!}
                                </td>
                                <td style="text-align: start;">
                                    {{ $model->engines['type'] }}
                                </td>
                                <td style="text-align: start;">
                                    {{ $model->engines['power'] }}
                                </td>
                                <td style="text-align: start;">
                                    {{ $model->colors }}
                                </td>
                                <td style="text-align: start;" class="align-middle">
                                    <div style="display: flex; gap: 3px; justify-content: end">
                                        <div>
{{--                                            <a href="{!! url('car_brands/'.$model->id.'/cars/') !!}">--}}
{{--                                                <x-primary-button type="button" class="ms-3">--}}
{{--                                                    {{ __('View') }}--}}
{{--                                                </x-primary-button>--}}
{{--                                            </a>--}}
                                        </div>
                                        <div>
                                            <a>
                                                <x-primary-button type="button" class="ms-3" onclick="window.location='{{ route('models.edit', ['brand' => $brand, 'cars'=>$cars ,'model'=>$model]) }}';">
                                                    {{ __('Edit') }}
                                                </x-primary-button>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="{{ route('models.destroy', ['brand' => $brand, 'cars'=>$cars ,'model'=>$model]) }}" method="POST">
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

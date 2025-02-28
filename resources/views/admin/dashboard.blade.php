<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container">
        <h1>Car Brands</h1>

        @if($carBrands->isEmpty())
            <p>No car brands found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                @foreach($carBrands as $carBrand)
                    <tr>
                        <td>{{ $carBrand->id }}</td>
                        <td>{{ $carBrand->name }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>


</x-app-layout>

<form action="{{ route('car_brands.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="name">Car Brand Name</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="country">Country</label>
        <input type="text" name="country" id="country" value="{{ old('country') }}" required>
        @error('country')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="logo">Car Brand Logo</label>
        <input type="file" name="logo" id="logo" required>
        @error('logo')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Add Car Brand</button>
</form>

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The car brand name is required.',
            'name.unique' => 'This car brand name already exists.',
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a file of type: jpeg, png, jpg, gif, svg.',
            'logo.max' => 'The logo size must not exceed 2MB.'
        ];
    }

}

<?php

namespace App\Http\Requests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Validation\Validator;
class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:55',
            'price' => 'required|numeric',
            'brand' => 'required|string|max:55',
            'processor' => 'required|string|max:55',
            'ram' => 'required|string|max:55',
            'storage' => 'required|string|max:55'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors(); // Get all the error messages.

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $errors,
        ], 422));
    }
}
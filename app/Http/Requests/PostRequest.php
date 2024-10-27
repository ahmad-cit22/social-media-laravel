<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255'],
            'picture' => ['nullable', 'image', 'max:2024', 'mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Empty content! Please write your post properly.',
            'picture.image' => 'Please upload a valid image.',
            'picture.max' => 'Image size must be 2MB or less.',
            'picture.mimes' => 'Allowed image formats are: jpeg, png, jpg, gif, svg, webp.',
        ];
    }
}

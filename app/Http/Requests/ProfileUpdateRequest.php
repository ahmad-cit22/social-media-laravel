<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::user()->id],
            'username' => ['required', 'string', 'max:255', 'alpha_num', 'unique:users,username,' . Auth::user()->id],
            'bio' => ['nullable', 'string', 'max:255'],
            'new_password' => ['nullable', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', 'max:16', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:1024', 'mimes:jpeg,png,jpg,gif,svg,webp'],
        ];
    }

    public function messages()
    {
        return [
            'new_password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'avatar.max' => 'Image size must be 2MB or less.',
            'avatar.mimes' => 'Allowed image formats are: jpeg, png, jpg, gif, svg, webp.',
        ];
    }
}

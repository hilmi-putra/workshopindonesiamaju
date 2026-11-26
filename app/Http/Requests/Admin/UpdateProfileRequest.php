<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'nullable', 
                'string', 
                'max:255', 
                'alpha_dash', // only letters, numbers, dashes and underscores
                Rule::unique('users', 'username')->ignore($userId)
            ],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'url', 'max:2048'], // We expect a URL from the upload endpoint
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).',
            'username.unique' => 'Username ini sudah digunakan.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'avatar.url' => 'Avatar harus berupa URL gambar yang valid.',
        ];
    }
}

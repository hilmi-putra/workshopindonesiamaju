<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PublisherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $publisherId = $this->publisher ? $this->publisher->id : '';

        return [
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:publishers,slug,' . $publisherId,
            'description' => 'nullable|string',
            'logo'        => 'nullable|string',
            'website'     => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'phone'       => 'nullable|string|max:50',
            'address'     => 'nullable|string',
        ];
    }
}

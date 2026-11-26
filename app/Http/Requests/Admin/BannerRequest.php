<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image_desktop' => $this->isMethod('POST') ? 'required|image|mimes:jpeg,png,jpg,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];
    }
}

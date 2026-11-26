<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('campaign');
        return [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:campaigns,slug,' . $id,
            'description' => 'nullable|string',
            'banner_desktop' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner_mobile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'priority' => 'nullable|integer',
            'status' => 'required|in:draft,active,expired,archived',
            'is_featured' => 'boolean',
            'show_homepage' => 'boolean',
            'show_bookstore' => 'boolean',
            'books' => 'nullable|array',
            'books.*' => 'exists:books,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ];
    }
}

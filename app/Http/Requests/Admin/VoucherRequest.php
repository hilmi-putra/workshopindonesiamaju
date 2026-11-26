<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
        $id = $this->route('voucher');
        return [
            'code' => 'nullable|string|max:50|unique:vouchers,code,' . $id,
            'voucher_type' => 'required|in:product_discount,shipping_discount,cashback',
            'discount_type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'quota' => 'nullable|integer|min:0',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,paused,expired,archived',
            'description' => 'nullable|string',
        ];
    }
}

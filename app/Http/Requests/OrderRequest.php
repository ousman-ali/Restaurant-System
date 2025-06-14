<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'table_id' => 'nullable|integer',
            'served_by' => 'nullable|sometimes|integer',
            'status' => 'nullable|sometimes|integer',
            'payment' => 'nullable|sometimes|numeric',
            'vat' => 'required|numeric',
            'change_amount' => 'required|numeric',
            'items' => 'required|array',
            'items.*.dish_id' => 'required|integer',
            'items.*.dish_type_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.net_price' => 'required|numeric',
            'items.*.gross_price' => 'required|numeric',
        ];
    }
}

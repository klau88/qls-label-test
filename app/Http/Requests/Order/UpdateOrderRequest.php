<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'product_combination_id' => ['required', 'string', 'max:255'],
            'company_id' => ['required', 'string', 'max:255'],
            'brand_id' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'billing_companyname' => ['required', 'string', 'max:255'],
            'billing_name' => ['required', 'string', 'max:255'],
            'billing_street' => ['required', 'string', 'max:255'],
            'billing_housenumber' => ['required', 'string', 'max:255'],
            'billing_address_line_2' => ['nullable', 'string', 'max:255'],
            'billing_zipcode' => ['required', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:255'],
            'billing_country' => ['required', 'string', 'max:255'],
            'billing_phone' => ['nullable', 'string', 'max:255'],
            'billing_email' => ['nullable', 'string', 'max:255'],
            'delivery_companyname' => ['required', 'string', 'max:255'],
            'delivery_name' => ['required', 'string', 'max:255'],
            'delivery_street' => ['required', 'string', 'max:255'],
            'delivery_housenumber' => ['required', 'string', 'max:255'],
            'delivery_address_line_2' => ['nullable', 'string', 'max:255'],
            'delivery_zipcode' => ['required', 'string', 'max:255'],
            'delivery_city' => ['required', 'string', 'max:255'],
            'delivery_country' => ['required', 'string', 'max:255'],
            'delivery_phone' => ['nullable', 'string', 'max:255'],
            'delivery_email' => ['nullable', 'string', 'max:255'],
            'orderLines' => ['required', 'array', 'min:1'],
            'orderLines.*.amount' => ['required', 'integer', 'min:1'],
            'orderLines.*.name' => ['required', 'string', 'max:255'],
        ];
    }
}

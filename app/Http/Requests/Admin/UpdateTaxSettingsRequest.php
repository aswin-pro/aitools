<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxSettingsRequest extends FormRequest
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
        return [
            'invoice_prefix' => 'required|string|max:40',
            'invoice_name' => 'required|string|max:255',
            'invoice_email' => 'required|email:rfc,dns|max:255',
            'invoice_phone' => 'required|string|max:20',
            'invoice_address' => 'required|string|max:255',
            'invoice_city' => 'required|string|max:255',
            'invoice_state' => 'required|string|max:255',
            'invoice_zipcode' => 'required|string|max:20',
            'invoice_country' => 'required|string|max:255',
            'tax_name' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'tax_value' => 'nullable|string|min:0|max:100',
            'invoice_footer' => 'required|string|max:500',
        ];
    }
}

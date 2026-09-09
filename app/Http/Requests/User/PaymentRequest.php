<?php
namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'billing_name'    => 'required|string|max:255',
            'billing_email'   => 'required|email|max:255',
            'billing_phone'   => 'required|string|max:20',
            'billing_address' => 'required|string',
            'billing_city'    => 'required|string|max:255',
            'billing_state'   => 'required|string|max:255',
            'billing_zipcode' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'type'            => 'required|string|max:255',
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCurrencyRequest extends FormRequest
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
        $currencyId = $this->input('id');

        return [
            'id' => [
                'required',
                'integer',
                'exists:currencies,id',
            ],

            'priority' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('currencies', 'name')
                    ->ignore($currencyId),
            ],

            'iso_code' => [
                'required',
                'string',
                'size:3',
                'alpha',
                Rule::unique('currencies', 'iso_code')
                    ->ignore($currencyId),
            ],

            'iso_numeric' => [
                'nullable',
                'integer',
                'digits_between:1,3',
                Rule::unique('currencies', 'iso_numeric')
                    ->ignore($currencyId),
            ],

            'symbol' => [
                'required',
                'string',
                'max:20',
            ],

            'subunit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'subunit_to_unit' => [
                'nullable',
                'integer',
            ],

            'symbol_first' => [
                'required',
                'in:true,false',
            ],

            'html_entity' => [
                'nullable',
                'string',
                'max:50',
            ],

            'decimal_mark' => [
                'nullable',
                'string',
                'max:5',
            ],

            'thousands_separator' => [
                'nullable',
                'string',
                'max:5',
            ],
        ];
    }
}

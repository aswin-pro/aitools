<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCurrencyRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:currencies,name',
            ],

            'iso_code' => [
                'required',
                'string',
                'size:3',
                'alpha',
                'unique:currencies,iso_code',
            ],

            'iso_numeric' => [
                'nullable',
                'integer',
                'digits_between:1,3',
                'unique:currencies,iso_numeric',
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
                'min:1',
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

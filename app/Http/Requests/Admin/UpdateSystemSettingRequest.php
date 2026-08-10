<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSystemSettingRequest extends FormRequest
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
            'show_website' => [
                'required',
                'in:yes,no',
            ],
            'timezone' => [
                'required',
                'string',
                'max:100',
            ],

            // 'languages' => [
            //     'required',
            //     'array',
            //     'min:1',
            // ],

            // 'default_language' => [
            //     'required',
            //     'string',
            //     'max:10',
            // ],

            'date_time_format' => [
                'required',
                'string',
                'max:100',
            ],

            'currency_format_type' => [
                'required',
                'string',
                    Rule::in([
                        '1,234,567.89',
                        '12,34,567.89',
                        '1.234.567,89',
                        '1 234 567,89',
                        "1'234'567.89",
                    ]),
            ],

            'currency_decimals_place' => [
                'required',
                'integer',
                'min:0',
                'max:4',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],
        ];
    }
}

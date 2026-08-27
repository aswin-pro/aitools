<?php

namespace App\Http\Requests\Admin\users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateUserRequest extends FormRequest
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
             'user_id' => [
            'required',
            'integer',
            'exists:users,id',
        ],

        'full_name' => [
            'required',
            'string',
            'min:3',
            'max:100',
        ],

        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users', 'email')
                 ->ignore($this->user_id, 'id'),
        ],

        'password' => [
            'nullable',
            'string',
            'min:8',
            'max:255',
        ],
        ];
    }
}

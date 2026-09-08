<?php

namespace App\Http\Requests\Admin\chatgenius;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class updateChatRequest extends FormRequest
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
        $sizeLimit = env('SIZE_LIMIT');

        return [
            'chat_assistant_id' => [
                'required',
                'string',
            ],

            'chat_assistant_name' => [
                'required',
                'string',
                'min:2',
                'max:200',
            ],

            'chat_assistant_expert' => [
                'required',
                'string',
                'min:2',
                'max:200',
            ],

            'chat_assistant_description' => [
                'required',
                'string',
                'min:10',
            ],

            'chat_assistant_message' => [
                'required',
                'string',
                'min:10',
            ],

            'chat_assistant_image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:' . $sizeLimit,
            ],
        ];
   
    }
}

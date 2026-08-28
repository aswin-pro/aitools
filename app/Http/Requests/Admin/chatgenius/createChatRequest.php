<?php

namespace App\Http\Requests\Admin\chatgenius;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class createChatRequest extends FormRequest
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
            'chat_genius_image' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:' . $sizeLimit,
            ],

            'chat_genius_name' => [
                'required',
                'string',
                'min:2',
                'max:200',
            ],

            'chat_genius_expert' => [
                'required',
                'string',
                'min:2',
                'max:200',
            ],

            'chat_genius_description' => [
                'required',
                'string',
                'min:10',
            ],

            'chat_genius_message' => [
                'required',
                'string',
                'min:10',
            ],
        ];
    }

    public function messages(): array
    {
        $sizeLimit = env('SIZE_LIMIT');

        return [
            'chat_genius_image.required' =>
            trans('The image is required.'),

            'chat_genius_image.mimes' =>
            trans('The image must be a file of type: jpg, jpeg, png, webp.'),

            'chat_genius_image.max' =>
            trans(
                'The image may not be greater than ' .
                    ($sizeLimit / 1024) .
                    ' MB.'
            ),

            'chat_genius_name.required' =>
            trans('The name field is required.'),

            'chat_genius_expert.required' =>
            trans('The expert field is required.'),

            'chat_genius_description.required' =>
            trans('The description is required.'),

            'chat_genius_message.required' =>
            trans('The message is required.'),
        ];
    }
}

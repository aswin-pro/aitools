<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WebsiteSettingsRequest extends FormRequest
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
            'site_name' => [
                'required',
                'string',
                'max:255',
            ],

            'app_name' => [
                'required',
                'string',
                'max:255',
            ],

            'app_theme' => [
                'nullable',
                'string',
            ],

            'theme_id' => [
                'required',
            ],

            'site_logo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp,svg',
                'max:' . env('SIZE_LIMIT'),
            ],

            'site_logo_light' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp,svg',
                'max:' . env('SIZE_LIMIT'),
            ],

            'favi_icon' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp,svg',
                'max:' . env('SIZE_LIMIT'),
            ],

            'primary_image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp,svg',
                'max:' . env('SIZE_LIMIT'),
            ],
        ];
    }
}

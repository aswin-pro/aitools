<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PublishBlogRequest extends FormRequest
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
            'blog_cover' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'blog_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'blog_slug' => [
                'required',
                'string',
                'min:3',
                'max:555',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'unique:blogs,slug',
            ],

            'short_description' => [
                'required',
                'string',
                'min:3',
                'max:700',
            ],

            'long_description' => [
                'required',
                'string',
                'min:3',
            ],

            'category_id' => [
                'required',
                'string',
                'exists:blog_categories,blog_category_id',
            ],

            'tags' => [
                'required',
                'string',
                'min:2',
                'max:500',
            ],

            'seo_title' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],

            'seo_description' => [
                'required',
                'string',
                'min:3',
                'max:500',
            ],

            'seo_keywords' => [
                'required',
                'string',
                'min:3',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'blog_cover.required' => 'Please select a cover image.',
            'blog_cover.mimes' => 'The cover image must be a JPG, JPEG, PNG, or WEBP image.',
            'blog_cover.max' => 'The cover image may not be larger than 5 MB.',

            'blog_name.required' => 'Blog name is required.',
            'blog_name.min' => 'Blog name must be at least 3 characters.',

            'blog_slug.required' => 'Blog slug is required.',
            'blog_slug.regex' => 'Blog slug may only contain lowercase letters, numbers, and hyphens.',
            'blog_slug.unique' => 'This blog slug is already in use.',

            'short_description.required' => 'Short description is required.',
            'long_description.required' => 'Blog description is required.',

            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',

            'tags.required' => 'Please add at least one tag.',

            'seo_title.required' => 'SEO title is required.',
            'seo_description.required' => 'SEO description is required.',
            'seo_keywords.required' => 'SEO keywords are required.',
        ];
    }
}

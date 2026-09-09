<?php
namespace App\Http\Requests\User;

use App\Models\Config;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ContentGenerationRequest extends FormRequest
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
        // max words length
        $max_words_length = Config::where('config_key', 'share_content')->first()->config_value ?? 500;

        return [
            'tone'       => 'required|string|max:255',
            'lang'       => 'required|string|max:255',
            'level'      => 'required|numeric|min:0.0|max:1.0',
            'max_length' => 'required|numeric|min:10|max:' . $max_words_length,
            'results'    => 'required|numeric|min:1|max:5',
        ];
    }
}

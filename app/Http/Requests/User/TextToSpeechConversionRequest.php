<?php
namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TextToSpeechConversionRequest extends FormRequest
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
            'name'         => 'required|string|min:3|max:250',
            'voice'        => 'required|in:alloy,echo,fable,onyx,nova,shimmer',
            'speed'        => 'required|numeric|in:0.25,0.5,1.0,1.5,2.0,2.5,3.0,3.5,4.0',
            'audio_format' => 'required|in:mp3,opus,aac,flac',
            'text'         => 'required|string|min:3|max:1000',
        ];
    }
}

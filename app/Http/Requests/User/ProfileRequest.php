<?php
namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends FormRequest
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
        // user id
        $userId = Auth::user()->id;

        // return
        return [
            'name'          => 'required', 'min:3', 'max:255',
            'email'         => [
                'required',
                'email',
                'min:3',
                'max:255',
                'unique:users,email,' . $userId,
            ],
            'profile_image' => [
                'nullable',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:' . env('SIZE_LIMIT'),
            ],
        ];
    }
}

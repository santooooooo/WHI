<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'password' => 'required|regex:/^[a-zA-Z0-9]+$/|min:6|string',
        'newName' => 'nullable|string',
        'newEmail' => 'nullable|email',
        'newPassword' => 'nullable|regex:/^[a-zA-Z0-9]+$/|min:6|string'
        ];
    }
}

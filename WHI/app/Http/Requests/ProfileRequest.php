<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        'icon' => 'nullable|image|max:10000',
        'career' => 'nullable|string|max:1000',
        'title' => 'nullable|string|max:255',
        'text' => 'nullable|string|max:10000',
        'email' => 'nullable|email|max:255',
        'twitter' => 'nullable|string|max:255',
        ];
    }
}

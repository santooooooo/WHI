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
        'name' => 'required|string|max:255',
        'icon' => 'image|max:10000',
        'career' => 'string|max:1000',
        'title' => 'string|max:255',
        'text' => 'string|max:10000',
        'email' => 'email|max:255',
        'twitter' => 'string|max:255',
        ];
    }
}

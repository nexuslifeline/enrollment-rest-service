<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'username' => 'required|string|email:filter|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'userable_type' => 'required|string|in:App\Student,App\Personnel',
            'user_group_id' => 'required_if:userable_type,==,App\Personnel',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEmailUpdateRequest extends FormRequest
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
            'username' => 'required|string|email:filter|max:255|unique:users,username,' . $this->id . ',id',
            'user_group_id' => 'sometimes|required',
        ];
    }
}

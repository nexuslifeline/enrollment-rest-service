<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonnelStoreRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user.username' => 'required|string|email:filter|max:255|unique:users,username',
            'user.password' => 'required|string|min:6|confirmed',
            'user.user_group_id' => 'required',
            'department_id' => 'required',
            //'personnel_status_id' => 'required',
            'job_title' => 'required|string|max:255',
            'birth_date' => 'required|date',
            //'complete_address' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'user.username' => 'email',
            'user.password' => 'password',
            'user.user_group_id' => 'user group',
            'department_id' => 'department',
            'personnel_status_id' => 'status',
        ];
    }
}

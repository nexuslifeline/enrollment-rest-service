<?php

namespace App\Http\Requests;

use App\Rules\IsOldPasswordMatched;
use Illuminate\Foundation\Http\FormRequest;

class PersonnelUpdateRequest extends FormRequest
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
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'user.username' => 'sometimes|required|string|email:filter|max:255|unique:users,username,' . $this->id . ',userable_id',
            'user.old_password' => ['sometimes', 'required', new IsOldPasswordMatched()],
            'user.password' => ['sometimes', 'required', 'string', 'min:6' , 'confirmed'],
            'user.user_group_id' => 'sometimes|required',
            'birth_date' => 'sometimes|required|date',
            'department_id' => 'sometimes|required',
            //'personnel_status_id' => 'sometimes|required',
            'job_title' => 'sometimes|required|string|max:255',
            'complete_address' => 'required|string',
            'birth_date' => 'sometimes|required|date'
        ];
    }

    public function attributes()
    {
        return [
            'user.username' => 'email',
            'user.old_password' => 'old password',
            'user.password' => 'password',
            'user.user_group_id' => 'user group',
            'department_id' => 'department',
            'personnel_status_id' => 'status',
        ];
    }
}

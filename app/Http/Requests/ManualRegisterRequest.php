<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManualRegisterRequest extends FormRequest
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
            'birth_date' => 'required|date',
            'civil_status_id' => 'required',
            'user.username' => 'sometimes|required|string|email|max:255|unique:users,username,' . $this->id . ',userable_id',
            'user.password' => 'sometimes|required|string|min:6|confirmed',
            'academic_record.level_id' => 'required',
            'academic_record.school_year_id' => 'required',
            'academic_record.section_id' => 'sometimes|required',
            'academic_record.course_id' => 'required_if:academic_record.school_category_id,4,5,6',
            'academic_record.semester_id' => 'required_if:academic_record.school_category_id,4,5,6',
            'academic_record_subjects' => 'sometimes|array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'required_if' => 'The :attribute field is required.'
        ];
    }

    public function attributes()
    {
        return [
            'user.username' => 'username/email',
            'user.password' => 'password',
            'academic_record.level_id' => 'level',
            'academic_record.school_year_id' => 'school year',
            'academic_record.course_id' => 'course',
            'academic_record.semester_id' => 'semester',
            'academic_record.section_id' => 'section',
            'academic_record_subjects' => 'subjects'
        ];
    }
}

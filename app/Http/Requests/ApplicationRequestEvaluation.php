<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequestEvaluation extends FormRequest
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
            'level_id' => 'required|not_in:0',
            'course_id' => 'required_if:level_id,13,14,15,16,17,18,19,20,21,22',
            'semester_id' => 'required_if:level_id,13,14,15,16,17,18,19',
            'last_school_attended' => 'required',
            'last_school_year_from' => 'required',
            'last_school_year_to' => 'required',
            'last_school_level_id' => 'required|not_in:0'
        ];
    }

    public function attributes()
    {
        return [
            'level_id' => 'level',
            'course_id' => 'course',
            'semester_id' => 'semester'
        ];
    }

    public function messages()
    {
        return [
            'not_in' => 'The :attribute field is required.'
        ];
    }
}

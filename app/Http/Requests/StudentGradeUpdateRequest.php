<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentGradeUpdateRequest extends FormRequest
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
            'section_id' => 'required|not_in:0',
            'subject_id' => 'required|not_in:0',
            'personnel_id' => 'required|not_in:0',
        ];
    }

    public function attributes()
    {
        return [
            'section_id' => 'section',
            'subject_id' => 'subject',
            'personnel_id' => 'personnel'
        ];
    }

    public function messages()
    {
        return [
            'not_in' => 'The :attribute field is required.'
        ];
    }
}

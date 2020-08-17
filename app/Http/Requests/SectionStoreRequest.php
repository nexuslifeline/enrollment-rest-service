<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionStoreRequest extends FormRequest
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
            'name' => 'required|max:191',
            'description' => 'required|max:191',
            'school_year_id' => 'required',
            'school_category_id' => 'required',
            'level_id' => 'required',
            'course_id' => 'required_if:school_category_id,4,5,6',
            'semester_id' => 'required_if:school_category_id,4,5,6'
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
            'school_year_id' => 'school year',
            'school_category_id' => 'school category',
            'level_id' => 'level',
            'course_id' => 'course',
            'semester_id' => 'semester'
        ];
    }
}

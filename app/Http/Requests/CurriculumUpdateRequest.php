<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurriculumUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            // 'school_category_id' => 'required|numeric',
            'course_id' => 'required_if:school_categories.0,4,5,6,7',
            // 'level_id' => 'required_if:school_category_id,1,2,3,6,7',
            'school_categories' => 'min:1',
            'effective_year' => 'required|digits:4|integer|min:1950|max:2100'
        ];
    }

    public function messages()
    {
        return [
            'school_categories.min' => 'The :attribute must have atleast 1.',
            'required_if' => 'The :attribute field is required.',
            'effective_year.digits' => 'The :attribute field is invalid.'
        ];
    }

    public function attributes()
    {
        return [
            // 'school_category_id' => 'school category',
            'course_id' => 'course'
        ];
    }
}

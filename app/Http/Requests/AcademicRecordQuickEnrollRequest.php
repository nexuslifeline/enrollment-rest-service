<?php

namespace App\Http\Requests;

use App\Rules\IsLevelValidInSchoolCategory;
use Illuminate\Foundation\Http\FormRequest;

class AcademicRecordQuickEnrollRequest extends FormRequest
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
            'school_year_id' => 'required|not_in:0',
            'school_category_id' => 'required|not_in:0',
            'level_id' => ['required','not_in:0', new IsLevelValidInSchoolCategory(
                $this->level_id,
                $this->school_category_id
            )],
            'semester_id' => 'required_if:school_category_id,4,5,6|not_in:0',
            'course_id' => 'required_if:school_category_id,4,5,6|not_in:0',
        ];
    }

    public function attributes()
    {
        return [
            'school_year_id' => 'school year',
            'school_category_id' => 'school category'
        ];
    }

    public function messages()
    {
        return [
            'not_in' => 'The :attribute field is required.',
            'required_if' => 'The :attribute field is required.'
        ];
    }
}

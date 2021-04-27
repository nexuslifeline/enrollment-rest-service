<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradingPeriodUpdateRequest extends FormRequest
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
            'name' => 'required|max:255',
            'school_year_id' => 'required',
            'school_category_id' => 'required',
            'semester_id' => 'required_if:school_category_id,4,5'
        ];
    }

    public function attributes()
    {
        return [
            'school_category_id' => 'school category',
            'school_year_id' => 'school year',
            'semester' => 'semester'
        ];
    }

    public function messages()
    {
        return [
            'required_if' => 'The :attribute field is required.'
        ];
    }
}

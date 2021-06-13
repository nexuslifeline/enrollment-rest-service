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
            'last_school_attended' => 'required',
            'last_school_year_from' => 'required',
            'last_school_year_to' => 'required',
            'last_school_level_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'level_id' => 'level'
        ];
    }

    public function messages()
    {
        return [
            'level.not_in' => 'The :attribute field is required.'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolCategoryModeUpdateRequest extends FormRequest
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
            'is_open' => 'required',
            'school_year_id' => 'required',
             //'school_category_id' => 'required', // this is already in path params
        ];
    }

    public function attributes()
    {
        return [
            //'school_category_id' => 'school category',
            'school_year_id' => 'school year',
            'is_open' => 'mode'
        ];
    }
}

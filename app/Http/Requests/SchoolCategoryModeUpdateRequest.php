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

    protected function prepareForValidation()
    {
        $this->merge(['school_category_id' => (int)$this->route('schoolCategoryId')]);
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
            'school_category_id' => 'required', // this is already in path params
            'semester_id' => 'required_if:school_category_id,4,5', // this is already in path params
        ];
    }

    public function attributes()
    {
        return [
            'school_category_id' => 'school category',
            'school_year_id' => 'school year',
            'semester_id' => 'semester',
            'is_open' => 'mode'
        ];
    }
}

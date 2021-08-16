<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequirementUpdateRequest extends FormRequest
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
            'school_category_id' => 'required|not_in:0',
            'document_type_id' => 'required|not_in:0|'. Rule::unique('requirements', 'document_type_id')->ignore($this->id)->where('school_category_id', $this->school_category_id)
        ];
    }

    public function messages()
    {
        return [
            'not_in' => 'The :attribute field is required.',
            'document_type_id.unique' => 'This document type is already added.'
        ];
    }

    public function attributes()
    {
        return [
            'school_category_id' => 'school category',
            'document_type_id' => 'document type'
        ];
    }
}

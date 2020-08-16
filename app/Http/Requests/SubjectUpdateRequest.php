<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectUpdateRequest extends FormRequest
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
            'description' => 'required|max:191|'.Rule::unique('subjects', 'description')->ignore($this->id)->where('name', $this->name),
            'school_category_id' => 'required'
        ];
    }
    
    public function attributes()
    {
        return [
            'school_category_id' => 'school category'
        ];
    }

    public function messages()
    {
        return [
            'description.unique' => 'The code and description are already taken'
        ];
    }
}

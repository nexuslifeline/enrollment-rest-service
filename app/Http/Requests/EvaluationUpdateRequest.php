<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationUpdateRequest extends FormRequest
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
            'curriculum_id' => 'sometimes|required',
            'student_curriculum_id' => 'sometimes|required'
        ];
    }

    public function messages()
    {
        return [
            'curriculum_id.required' => 'Please select an active curriculum',
            'student_curriculum_id.required' => 'Please specify the curriculum that the student is using.'
        ];
    }
}

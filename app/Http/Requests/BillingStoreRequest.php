<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingStoreRequest extends FormRequest
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
            'term_id' => 'sometimes|required',
            'student_id' => 'sometimes|required',
            'due_date' => 'required|date'
        ];
    }

    public function attributes()
    {
        return [
            'term_id' => 'term',
            'student_id' => 'student'
        ];
    }
}

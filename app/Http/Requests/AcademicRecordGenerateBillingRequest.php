<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademicRecordGenerateBillingRequest extends FormRequest
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
            'term_id' => 'required_if:billing_type_id,2|not_in:0',
            'due_date' => 'required|date',
            'amount' => 'required|not_in:0',
            'billing_type_id' => 'required|not_in:0'
        ];
    }

    public function attributes()
    {
        return [
            'term_id' => 'term',
            'billing_type_id' => 'billing type'
        ];
    }

    public function messages()
    {
        return [
            'not_in' => 'The :attribute field is required.',
            'required_if' => 'The :attribute field is required.',
        ];
    }
}

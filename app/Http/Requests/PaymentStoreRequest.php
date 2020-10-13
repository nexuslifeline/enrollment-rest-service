<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
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
            'student_id' => 'sometimes|required',
            'billing_id' => 'sometimes|required',
            'transaction_no' => 'sometimes|required',
            'reference_no' => 'sometimes|required',
            'payment_mode_id' => 'sometimes|required',
            'date_paid' => 'sometimes|required|date',
            'amount' => 'sometimes|required|numeric|gt:0',
            'notes' => 'sometimes|required_if:payment_mode_id,==,3'
        ];
    }

    public function attributes()
    {
        return [
            'payment_mode_id' => 'payment mode',
            'date_paid' => 'date paid is required',
            'student_id' => 'student'
        ];
    }

    public function messages()
    {
        return [
            'notes.required_if' => 'Notes is required when payment mode is OTHERS.',
            'billing_id' => 'Please select a biling.'
        ];
    }
}

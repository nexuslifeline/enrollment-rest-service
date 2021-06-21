<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitPaymentRequest extends FormRequest
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
            'amount' => 'required|not_in:0',
            'transaction_no' => 'required',
            'payment_mode_id' => 'required|not_in:0',
            'date_paid' => 'required|date'
        ];
    }

    public function attributes()
    {
        return [
            'payment_mode_id' => 'payment mode'
        ];
    }

    public function messages()
    {
        return [
            'not_id' => 'The :attribute field is required.'
        ];
    }
}

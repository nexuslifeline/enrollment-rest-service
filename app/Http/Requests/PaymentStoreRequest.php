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
            'amount' => 'required|numeric',
            'payment_mode_id' => 'required'
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
            'notes.required_if' => 'Notes is required when payment mode is OTHERS.'
        ];
    }
}

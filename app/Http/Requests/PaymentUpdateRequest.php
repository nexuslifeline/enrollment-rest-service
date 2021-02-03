<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
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
            'amount' => 'sometimes|required|numeric|min:0|not_in:0',
            'reference_no' => 'sometimes|required|max:191|unique:payments,reference_no,' . $this->id,
            'date_paid' => 'sometimes|required',
            'payment_mode_id' => 'sometimes|required',
            'notes' => 'sometimes|required_if:payment_mode_id,==,3'
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
            'notes.required_if' => 'Notes is required when payment mode is OTHERS.',
        ];
    }
}

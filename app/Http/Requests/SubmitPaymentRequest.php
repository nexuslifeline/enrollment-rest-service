<?php

namespace App\Http\Requests;

use App\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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
            'amount' => ['required', function ($attribute, $value, $fail) {
                $initialBillingType = Config::get('constants.billing_type.INITIAL_FEE');
                $billing = Payment::find($this->id)->billing;
                Log::info($billing);
                if ($billing && $billing->billing_type_id === $initialBillingType) {

                    if ($value < $billing->total_amount) {
                        $fail("The initial fee amount must be fully paid.");
                    }
                }
            },],
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
            'not_in' => 'The :attribute field is required.'
        ];
    }
}

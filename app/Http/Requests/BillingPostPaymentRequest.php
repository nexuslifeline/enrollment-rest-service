<?php

namespace App\Http\Requests;

use App\Billing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class BillingPostPaymentRequest extends FormRequest
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
            'reference_no' => 'required',
            'amount' => ['required',function ($attribute, $value, $fail) {
                $initialBillingType = Config::get('constants.billing_type.INITIAL_FEE');
                $billing = Billing::find($this->id);
                if ($billing->billing_type_id === $initialBillingType) {
                    
                    if ($value < $billing->total_remaining_due) {
                        $fail("The " . $attribute . " mustn't be less than the remaining balance.");
                    }
                }
            },],
            'date_paid' => 'required|date'
        ];
    }
}

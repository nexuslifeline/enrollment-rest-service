<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Config;

class IsBillingTypeIdExistsInBillingTypes implements Rule
{
    private $_billingTypeId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($billingTypeId)
    {
        $this->_billingTypeId = $billingTypeId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $billingTypes = Config::get('constants.billing_type');
        return in_array($this->_billingTypeId, $billingTypes);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Billing Type is not valid.';
    }
}

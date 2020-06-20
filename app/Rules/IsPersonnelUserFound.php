<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class IsPersonnelUserFound implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $user = User::where('username', $value)
        ->where('userable_type', 'App\Personnel')
        ->first();
        return $user ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sorry! We couldn\'t find the :attribute you provided.';
    }
}

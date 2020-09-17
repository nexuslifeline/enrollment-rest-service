<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EWalletAccountUpdateRequest extends FormRequest
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
            'provider' => 'required|max:255',
            'account_name' => 'required|max:255',
            'account_id' => 'required|max:255',
        ];
    }
}

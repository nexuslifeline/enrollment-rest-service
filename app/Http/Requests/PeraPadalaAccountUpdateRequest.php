<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeraPadalaAccountUpdateRequest extends FormRequest
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
            'receiver_name' => 'required|max:255',
            'receiver_mobile_no' => 'required|max:255',
        ];
    }
}

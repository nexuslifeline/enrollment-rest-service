<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonnelUserStoreRequest extends FormRequest
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

    protected function prepareForValidation()
    {
      $this->merge([
        'userable_id' => (int)$this->route('personnelId'),
        'userable_type' => 'App\Personnel'
      ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|email:filter|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed'
        ];
    }
}

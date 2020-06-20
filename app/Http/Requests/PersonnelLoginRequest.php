<?php

namespace App\Http\Requests;

use App\Rules\IsPersonnelUserFound;
use App\Rules\IsPersonnelUserMatchPword;
use Illuminate\Foundation\Http\FormRequest;

class PersonnelLoginRequest extends FormRequest
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
            'username' => ['required', 'email', new IsPersonnelUserFound()],
            'password' => [
                'required',
                'min:6',
                new IsPersonnelUserMatchPword(
                    $this->username
                )
            ],
        ];
    }
}

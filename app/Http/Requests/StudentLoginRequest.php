<?php

namespace App\Http\Requests;

use App\Rules\IsStudentUserFound;
use App\Rules\IsStudentUserMatchPword;
use Illuminate\Foundation\Http\FormRequest;

class StudentLoginRequest extends FormRequest
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
            'username' => ['required', 'email', new IsStudentUserFound()],
            'password' => [
                'required',
                'min:6',
                new IsStudentUserMatchPword(
                    $this->username
                )
            ],
        ];
    }
}

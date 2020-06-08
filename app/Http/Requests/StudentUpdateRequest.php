<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
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
        $activeApplication = $this->active_application ?? false;
        $activeAdmission = $this->active_admission ?? false;
        return [
            'active_application.*' => [
                function ($attribute, $value, $fail) use($activeApplication) {
                    if ($activeApplication && !Arr::get($activeApplication, 'id')) {
                        $fail('Application id is required.');
                    }
                }
            ],
            'active_admission.*' => [
                function ($attribute, $value, $fail) use($activeAdmission) {
                    if ($activeAdmission && !Arr::get($activeAdmission, 'id')) {
                        $fail('Admission id is required.');
                    }
                }
            ]
        ];
    }
}

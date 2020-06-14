<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
              ],
            // student
            'student_no' => 'sometimes|required|string',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'birth_date' => 'sometimes|required|date',
            'civil_status_id' => 'sometimes|required',
            'parent_guardian_name' => 'sometimes|required|string|max:255',
            'parent_guardian_contact_no' => 'sometimes|required|string|max:255',
            // student address
            'address.current_house_no_street' => 'sometimes|required|string|max:255',
            'address.current_city_town' => 'sometimes|required|string|max:255',
            'address.current_province' => 'sometimes|required|string|max:255',
            'address.current_postal_code' => 'sometimes|required|string|max:255',
            'address.current_country_id' => 'sometimes|required',
            'address.current_home_landline_mobile_no' => 'sometimes|required|string|max:255',
            'address.permanent_house_no_street' => 'sometimes|required|string|max:255',
            'address.permanent_city_town' => 'sometimes|required|string|max:255',
            'address.permanent_province' => 'sometimes|required|string|max:255',
            'address.permanent_postal_code' => 'sometimes|required|string|max:255',
            'address.permanent_country_id' => 'sometimes|required',
            'address.permanent_home_landline_mobile_no' => 'sometimes|required|string|max:255',
            // student family
            'family.father_name' => 'sometimes|required|string|max:255',
            'family.mother_name' => 'sometimes|required|string|max:255',
            'family.father_email' => 'sometimes|nullable|email',
            'family.mother_email' => 'sometimes|nullable|email',
            // transcript
            'transcript.level_id' => 'sometimes|required',
            'subjects' => 'sometimes|array|min:1'
        ];
    }

    public function attributes()
    {
        return [
            'civil_status_id' => 'civil status',
            'address.current_house_no_street' => 'house no/street',
            'address.current_city_town' => 'city/town',
            'address.current_province' => 'province',
            'address.current_postal_code' => 'postal code',
            'address.current_country_id' => 'country',
            'address.current_home_landline_mobile_no' => 'home landline/mobile no',
            'address.permanent_house_no_street' => 'house no/street',
            'address.permanent_city_town' => 'city/town',
            'address.permanent_province' => 'province',
            'address.permanent_postal_code' => 'postal code',
            'address.permanent_country_id' => 'country',
            'address.permanent_home_landline_mobile_no' => 'home landline/mobile no',
            'family.father_name' => 'father name',
            'family.mother_name' => 'mother name',
            'family.father_email' => 'email',
            'family.mother_email' => 'email',
            'transcript.level_id' => 'level',
        ];
    }
    
}

<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Rules\IsOldPasswordMatched;
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
        // $activeAdmission = $this->active_admission ?? false;

        return [
            'active_application.*' => [
                function ($attribute, $value, $fail) use($activeApplication) {
                    if ($activeApplication && !Arr::get($activeApplication, 'id')) {
                        $fail('Application id is required.');
                    }
                }
            ],
            // 'active_admission.*' => [
            //     function ($attribute, $value, $fail) use($activeAdmission) {
            //         if ($activeAdmission && !Arr::get($activeAdmission, 'id')) {
            //             $fail('Admission id is required.');
            //         }
            //     }
            //   ],
            // student
            'student_no' => 'sometimes|required|string',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'birth_date' => 'sometimes|required|date',
            'civil_status_id' => 'sometimes|required',
            'email' => 'sometimes|required|string|email:filter|max:255|unique:users,username,' . $this->id . ',userable_id',
            // student address
            'address.current_house_no_street' => 'sometimes|required|string|max:255',
            'address.current_barangay' => 'sometimes|required|string|max:255',
            'address.current_city_town' => 'sometimes|required|string|max:255',
            'address.current_province' => 'sometimes|required|string|max:255',
            'address.current_postal_code' => 'sometimes|required|string|max:255',
            'address.current_country_id' => 'sometimes|required',
            'address.current_home_landline_mobile_no' => 'sometimes|required|string|max:255',
            'address.current_complete_address' => 'max:755',
            'address.permanent_house_no_street' => 'sometimes|required|string|max:255',
            'address.permanent_barangay' => 'sometimes|required|string|max:255',
            'address.permanent_city_town' => 'sometimes|required|string|max:255',
            'address.permanent_province' => 'sometimes|required|string|max:255',
            'address.permanent_postal_code' => 'sometimes|required|string|max:255',
            'address.permanent_country_id' => 'sometimes|required',
            'address.permanent_home_landline_mobile_no' => 'sometimes|required|string|max:255',
            'address.permanent_complete_address' => 'max:755',
            // student family
            'family.father_name' => 'sometimes|required|string|max:255',
            'family.mother_name' => 'sometimes|required|string|max:255',
            'family.father_email' => 'sometimes|nullable|email:filter',
            'family.mother_email' => 'sometimes|nullable|email:filter',
            'family.parent_guardian_name' => 'sometimes|required|string|max:255',
            'family.parent_guardian_contact_no' => 'sometimes|required|string|max:255',
            // academicRecord
            // remove required 07-27-2020
            // 'academicRecord.section_id' => 'sometimes|required',
            'academic_record.level_id' => 'sometimes|required',
            'academic_record.course_id' => 'sometimes|required_if:academic_record.school_category_id,4,5,6',
            'academic_record.semester_id' => 'sometimes|required_if:academic_record.school_category_id,4,5,6',
            'subjects' => 'sometimes|array|min:1',
            // user account
            'user.username' => 'sometimes|required|string|email:filter|max:255|unique:users,username,'.$this->id.',userable_id',
            'user.old_password' => ['sometimes', 'required', new IsOldPasswordMatched()],
            'user.password' => 'sometimes|required|string|min:6|confirmed|',
            //evaluation
            'evaluation.last_school_attended' => 'sometimes|required',
            'evaluation.last_school_year_from' => 'sometimes|required',
            'evaluation.last_school_year_to' => 'sometimes|required',
            'evaluation.last_school_level_id' => 'sometimes|required',
            // 'evaluation.enrolled_year' => 'sometimes|required_if:evaluation.student_category_id,2',
            // 'evaluation.level_id' => 'sometimes|required', //remove evaluation enhancement
            // 'evaluation.course_id' => 'sometimes|required_if:evaluation.school_category_id,4,5,6', //remove evaluation enhancement
            // 'evaluation.semester_id' => 'sometimes|required_if:evaluation.school_category_id,4,5', //remove evaluation enhancement
        ];
    }

    public function attributes()
    {
        return [
            'civil_status_id' => 'civil status',
            'address.current_house_no_street' => 'house no/street',
            'address.current_barangay' => 'barangay',
            'address.current_city_town' => 'city/town',
            'address.current_province' => 'province',
            'address.current_postal_code' => 'postal code',
            'address.current_country_id' => 'country',
            'address.current_home_landline_mobile_no' => 'home landline/mobile no',
            'address.current_complete_address' => 'complete address',
            'address.permanent_house_no_street' => 'house no/street',
            'address.permanent_barangay' => 'barangay',
            'address.permanent_city_town' => 'city/town',
            'address.permanent_province' => 'province',
            'address.permanent_postal_code' => 'postal code',
            'address.permanent_country_id' => 'country',
            'address.permanent_home_landline_mobile_no' => 'home landline/mobile no',
            'address.permanent_complete_address' => 'complete address',
            'family.father_name' => 'father name',
            'family.mother_name' => 'mother name',
            'family.father_email' => 'email',
            'family.mother_email' => 'email',
            'family.parent_guardian_name' => 'parent/guardian name',
            'family.parent_guardian_contact_no' => 'parent/guardian contact no.',
            'academic_record.level_id' => 'level',
            'academic_record.section_id' => 'section',
            'academic_record.course_id' => 'course',
            'academic_record.semester_id' => 'semester',
            'user.username' => 'username',
            'user.old_password' => 'old password',
            'user.password' => 'password',
            'evaluation.level_id' => 'level',
            'evaluation.last_school_attended' => 'last school attended',
            'evaluation.last_school_year_from' => 'last school year from',
            'evaluation.last_school_year_to' => 'last school year to',
            'evaluation.last_school_level_id' => 'school level',
            // 'evaluation.enrolled_year' => 'enrolled year',
            'evaluation.course_id' => 'course',
            'evaluation.semester_id' => 'semester',
        ];
    }

    public function messages()
    {
        return [
            'required_if' => 'The :attribute field is required.'
        ];
    }
}

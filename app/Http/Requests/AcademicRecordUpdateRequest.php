<?php

namespace App\Http\Requests;

use App\Curriculum;
use App\SchoolCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AcademicRecordUpdateRequest extends FormRequest
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
        $transcriptRecord = $this->transcript_record ?? false;
        return [
            'level_id' => ['required','not_in:0', function ($attribute, $value, $fail) {
                $levelIds = SchoolCategory::find($this->school_category_id)->levels()->get();
                if (count($levelIds->where('id', $this->level_id)) == 0) {
                    $fail("The level is not applicable in the school category.");
                }
            }],
            'course_id' => 'required_if:level_id,13,14,15,16,17,18,19,20,21,22|not_in:0',
            'semester_id' => 'required_if:level_id,13,14,15,16,17,18,19|not_in:0',
            'school_year_id' => 'required|not_in:0',
            'school_category_id' => 'required|not_in:0',
            'student_category_id' => 'required|not_in:0',
            'transcript_record' => [
                function ($attribute, $value, $fail) use ($transcriptRecord) {
                    $curriculumId = Arr::get($transcriptRecord, 'curriculum_id');
                    // check if curriculum_id exists
                    if ($transcriptRecord && !$curriculumId) {
                        $fail('The curriculum field is required.');
                    }

                    // check if curriculum_id is applicable in course, level, school category
                    if ($curriculumId) {
                        $curriculumIds = Curriculum::whereHas('subjects', function ($q) {
                            return $q->where('level_id', $this->level_id)
                                ->where('course_id', $this->course_id)
                                ->where('curriculum_subjects.school_category_id', $this->school_category_id);
                        })->get()->pluck('id')->flatten();

                        if (in_array($curriculumId, $curriculumIds->all())) {
                            $fail('The curriculum is not applicable.');
                        }
                    }
                }
            ],
        ];
    }

    public function attributes()
    {
        return [
            'level_id' => 'level',
            'course_id' => 'course',
            'semester_id' => 'semester',
            'school_year_id' => 'school year',
            'school_category_id' => 'school category',
            'student_category_id' => 'student category'
        ];
    }

    public function messages()
    {
        return [
            'not_in' => 'The :attribute field is required.'
        ];
    }
}

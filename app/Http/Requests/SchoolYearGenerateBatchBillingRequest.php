<?php

namespace App\Http\Requests;

use App\Rules\IsBillingTypeIdExistsInBillingTypes;
use App\Rules\IsLevelValidInSchoolCategory;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SchoolYearGenerateBatchBillingRequest extends FormRequest
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
            'term_id' => 'required_if:billing_type_id,2|not_in:0',
            'due_date' => 'required|date|after:'.Carbon::now()->addDays(-1)->format('Y-m-d'),
            'billing_type_id' => ['required', 'not_in:0', new IsBillingTypeIdExistsInBillingTypes($this->billing_type_id)],
            'level_id' => [new IsLevelValidInSchoolCategory(
                $this->level_id, $this->school_category_id
            )]
        ];
    }

    public function attributes()
    {
        return [
            'term_id' => 'term',
            'billing_type_id' => 'billing type',
            'school_year_id' => 'school year'
        ];
    }

    public function messages()
    {
        return [
            'not_in' => 'The :attribute field is required.',
            'required_if' => 'The :attribute field is required.'
        ];
    }
}

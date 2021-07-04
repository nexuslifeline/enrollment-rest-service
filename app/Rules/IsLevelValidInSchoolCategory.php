<?php

namespace App\Rules;

use App\SchoolCategory;
use Illuminate\Contracts\Validation\Rule;

class IsLevelValidInSchoolCategory implements Rule
{
    private $_levelId;
    private $_schoolCategoryId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($levelId, $schoolCategoryId)
    {
        $this->_levelId = $levelId;
        $this->_schoolCategoryId = $schoolCategoryId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $levelIds = SchoolCategory::find($this->_schoolCategoryId)->levels()->get();
        return count($levelIds->where('id', $this->_levelId)) > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The level is not applicable in the school category.';
    }
}

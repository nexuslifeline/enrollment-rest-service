<?php

use App\SchoolCategory;
use App\UserGroup;
use Illuminate\Database\Seeder;

class SuperUserSchoolCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schoolCategoryIds = SchoolCategory::all()->pluck('id');
        UserGroup::find(1)->schoolCategories()->sync($schoolCategoryIds);
    }
}

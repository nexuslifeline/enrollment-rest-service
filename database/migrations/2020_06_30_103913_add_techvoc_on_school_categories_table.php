<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTechvocOnSchoolCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('school_categories')->insert(
            [
                ['name' => 'Vocational', 'description' => 'Vocational']
            ]
        );
    }
}

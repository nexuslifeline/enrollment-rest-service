<?php

namespace App\Console\Commands;

use App\Curriculum;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InsertCurriculumSchoolCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert-curriculum-school-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert school category id in curriculum_school_categories based on curriculum_subjects';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $curriculums = Curriculum::doesntHave('schoolCategories')->get();
        foreach ($curriculums as $curriculum) {
            $schoolCategoryIds = $curriculum->subjects()->pluck('curriculum_subjects.school_category_id');
            $curriculum->schoolCategories()->sync($schoolCategoryIds);
        }
    }
}

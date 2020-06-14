<?php

use App\SchoolFee;
use Illuminate\Database\Seeder;

class SchoolFeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Tuition Fee'],
            ['name' => 'Tuition Fee per Unit'],
            ['name' => 'Registration Fee'],
            ['name' => 'Library Fee'],
            ['name' => 'Medical/Dental Fee'],
            ['name' => 'Athletic Fee'],
            ['name' => 'Guidance and Counselling Fee'],
            ['name' => 'Audio Visual Fee'],
            ['name' => 'Socio Cultural Fee'],
            ['name' => 'Development Fee'],
            ['name' => 'E-Learning Management System/OES'],
            ['name' => 'Energy Fee'],
            ['name' => 'Testing Materials Fee'],
            ['name' => 'Faculty Development Fee'],
            ['name' => 'Forinsic 1( 1 unit)'],
            ['name' => 'Forinsic 2'],
            ['name' => 'Forinsic 3'],
            ['name' => 'Forinsic 4'],
            ['name' => 'Forinsic 5'],
            ['name' => 'Crim tic 6'],
            ['name' => 'Chemistry'],
            ['name' => 'Physical Education'],
            ['name' => 'Internet Fee'],
            ['name' => 'Research Journal'],
            ['name' => 'Chemistry'],
            ['name' => 'Botany'],
            ['name' => 'Zoology'],
            ['name' => 'Biology'],
            ['name' => 'Physics'],
            ['name' => 'Natural Science'],
            ['name' => 'Speech'],
            ['name' => 'Physical Education'],
            ['name' => 'Accounting Lab'],
            ['name' => 'Computer Lab'],
            ['name' => 'Food/HRM Lab'],
            ['name' => 'Practicum Fee'],
            ['name' => 'Diocesan Quota Fund'],
            ['name' => 'Red Cross'],
            ['name' => 'Insurance'],
            ['name' => 'Prisaa'],
            ['name' => 'Students Affair Fee'],
            ['name' => 'Department Fee'],
            ['name' => 'Community Extension'],
            ['name' => 'Campus Ministry Fee'],
            ['name' => 'Picc'],
            ['name' => 'Review Program'],
            ['name' => 'ROTC (3 units)'],
            ['name' => 'ROTC'],
            ['name' => 'Certification Fee'],
            ['name' => 'Psycho Testing Fee'],
            ['name' => 'CEAP'],
            ['name' => 'Sports Development Fee'],
            ['name' => 'School ID'],
            ['name' => 'Library ID'],
            ['name' => 'Entrance Exam'],
            ['name' => 'Diploma Fee, 2nd Copy'],
            ['name' => 'Graduation/Diploma Fee'],
            ['name' => 'Special Order, 2nd Copy'],
            ['name' => 'Transcript of Records Per Page'],
            ['name' => 'Honorable Dismissal'],
            ['name' => 'Comprehensive Exam']
        ];
        foreach($data as $item){
            SchoolFee::create($item);
        }
    }
}

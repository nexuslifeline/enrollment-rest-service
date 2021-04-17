<?php

use App\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = [
            ['name' => 'Form 138'],
            ['name' => 'Certificate of Good Moral'],
            ['name' => 'Certificate of Eligibility for Admission'],
            ['name' => 'Honorable Dismissal'],
            ['name' => 'Transcript of Records'],
            ['name' => 'Certification of Subjects Taken'],
            ['name' => 'Alient Certificate of Registration(ACR)'],
            ['name' => 'Student Visa'],
            ['name' => 'Certificate of School Closure'],
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::create($documentType);
        }
    }
}

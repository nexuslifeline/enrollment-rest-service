<?php

namespace App\Services;

use App\AcademicRecord;
use App\SchoolYear;
use App\Student;
use App\StudentFee;
use App\Term;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TermService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Term::with(['schoolYear', 'schoolCategory', 'semester']);

            //filter by school year
            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function($q) use ($schoolYearId) {
                return $q->where('school_year_id', $schoolYearId);
            });

            //filter by active school year
            $activeSchoolYear = $filters['active_school_year'] ?? false;
            $query->when($activeSchoolYear, function ($q) {
                $activeSy = SchoolYear::where('is_active', 1)->first() ?? false;
                $q->when($activeSy, function ($q) use ($activeSy) {
                    return $q->where('school_year_id', $activeSy->id);
                });
            });

            //filter by school category
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
                return $q->where('school_category_id', $schoolCategoryId);
            });

            //filter by semester
            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            });

            $terms = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $terms;
        } catch (Exception $e) {
            Log::info('Error occured during TermService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $term = Term::find($id);
            $term->load(['schoolYear', 'schoolCategory', 'semester']);
            return $term;
        } catch (Exception $e) {
            Log::info('Error occured during TermService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $term = Term::create($data);
            $term->load(['schoolYear', 'schoolCategory', 'semester']);
            DB::commit();
            return $term;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during TermService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $term = Term::find($id);
            $term->update($data);
            $term->load(['schoolYear', 'schoolCategory', 'semester']);
            DB::commit();
            return $term;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during TermService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        DB::beginTransaction();
        try {
            $term = Term::find($id);
            $term->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during TermService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function updateCreateBulk(array $terms, array $filters) {
        DB::beginTransaction();
        try {

            $schoolYearId = $filters['school_year_id'] ?? null;
            $schoolCategoryId = $filters['school_category_id'] ?? null;
            $semesterId = $filters['semester_id'] ?? null;

            foreach ($terms as $term) {
                $term = new Term($term);
                $new = Term::updateOrCreate(
                ['id' => $term->id],
                [
                    'name' => $term->name,
                    'description' => $term->description,
                    'school_year_id' => $schoolYearId,
                    'school_category_id' => $schoolCategoryId,
                    'semester_id' => $semesterId,
                ]);
            }

            DB::commit();

            $query = Term::where('school_year_id', $schoolYearId)
                        ->where('school_category_id', $schoolCategoryId)
                        ->where('semester_id', $semesterId);

            return $query->get();

        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during TermService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getStudentFeeTermsOfStudent(int $studentId, array $filters)
    {
        try {
            $query = AcademicRecord::where('student_id', $studentId);

            // school year
            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function($q) use ($schoolYearId) {
                return $q->where('school_year_id', $schoolYearId);
            });
            // semester
            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            });

            $studentFee = $query->first()->studentFee;
            Log::info($query->first()->studentFee);
            $terms = [];
            if ($studentFee) {
                Log::info($studentFee->id);
                $terms = $studentFee->terms()->get();
                $terms->append('previous_balance');
                $terms->load(['schoolYear', 'semester']);
            }
            return $terms;
        } catch (Exception $e) {
            Log::info('Error occured during TermService getStudentFeeTermsOfStudent method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getStudentFeeTermsOfAcademicRecord(int $academicRecordId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $studentFee = StudentFee::where('academic_record_id', $academicRecordId)->first();
            $query = $studentFee->terms();

            $terms = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $terms;
        } catch (Exception $e) {
            Log::info('Error occured during TermService getStudentFeeTermsOfAcademicRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function updateStudentFeeTermsOfAcademicRecord(array $data, int $academicRecordId, int $termId)
    {
        DB::beginTransaction();
        try {
            $studentFee = StudentFee::where('academic_record_id', $academicRecordId)->first();
            $studentFee->terms()->updateExistingPivot($termId, $data);
            DB::commit();
            return $studentFee->terms()->find($termId);
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during TermService updateStudentFeeTermsOfAcademicRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function deleteStudentFeeTermsOfAcademicRecord(int $academicRecordId, int $termId)
    {
        DB::beginTransaction();
        try {
            $studentFee = StudentFee::where('academic_record_id', $academicRecordId)->first();
            $studentFee->terms()->detach($termId);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during TermService deleteStudentFeeTermsOfAcademicRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function storeStudentFeeTermsOfAcademicRecord(array $data, int $academicRecordId)
    {
        DB::beginTransaction();
        try {
            $studentFee = StudentFee::where('academic_record_id', $academicRecordId)->first();
            if ($studentFee->terms()->find($data['term_id'])) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['This term has billing already.']
                ]);
            }
            $academicRecord = $studentFee->academicRecord;
            $term = Term::find($data['term_id']);
            if ($academicRecord->school_year_id !== $term->school_year_id) {
                throw ValidationException::withMessages([
                    'non_field_error' => ['This term is not applicable to this academic record school year.']
                ]);
            }

            $studentFee->terms()->attach($data['term_id'], 
                [
                    'amount' => $data['amount'],
                    'is_billed' => Arr::exists($data, 'is_billed') ? $data['is_billed'] : false
                ]
            );
            DB::commit();
            return $studentFee->terms()->find($data['term_id']);
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during TermService updateStudentFeeTermsOfAcademicRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}

<?php

namespace App\Services;

use App\Term;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TermService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Term::with(['schoolYear', 'schoolCategory', 'semester']);

            //filter by school category
            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function($q) use ($schoolYearId) {
                return $q->where('school_year_id', $schoolYearId);
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
            Log::info('Error occured during SchoolFeeService list method call: ');
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
            Log::info('Error occured during SchoolFeeCategoryService get method call: ');
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
            Log::info('Error occured during SchoolFeeService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $term = SchoolFee::find($id);
            $term->update($data);
            $term->load(['schoolYear', 'schoolCategory', 'semester']);
            DB::commit();
            return $schoolFee;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SchoolFeeService update method call: ');
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
            Log::info('Error occured during SchoolFeeService delete method call: ');
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
            Log::info('Error occured during SchoolFeeService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
<?php

namespace App\Services;

use App\RateSheet;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RateSheetService
{
    public function index(object $request)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $query = RateSheet::with(['level', 'course', 'semester', 'fees']);
    
            // filters
            $levelId = $request->level_id ?? false;
            $query->when($levelId, function($q) use ($levelId) {
                return $q->where('level_id', $levelId);
            });
    
            $courseId = $request->course_id ?? false;
            $query->when($courseId, function($q) use ($courseId) {
                return $q->where('course_id', $courseId);
            });
    
            $semesterId = $request->semester_id ?? false;
            $query->when($semesterId, function($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            });
    
            $rateSheets = !$request->has('paginate') || $request->paginate === 'true'
                ? $query->paginate($perPage)
                : $query->get();

            return $rateSheets;

        } catch (Exception $e) {
            Log::info('Error occured during RateSheetService index method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(object $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except(['fees']);
            $rateSheet = RateSheet::create($data);

            if ($request->has('fees')) {
                $fees = $request->fees;
                $items = [];
                foreach ($fees as $fee) {
                    $items[$fee['school_fee_id']] = [
                        'amount' => $fee['amount'],
                        'notes' => $fee['notes']
                    ];
                }
                $rateSheet->fees()->sync($items);
            }

            $rateSheet->load(['level', 'course', 'semester', 'fees']);
            DB::commit();
            return $rateSheet;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during RateSheetService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(object $request, RateSheet $rateSheet)
    {
        DB::beginTransaction();
        try {
            $data = $request->except(['fees']);
            $rateSheet->update($data);
    
            if ($request->has('fees')) {
                $fees = $request->fees;
                $items = [];
                foreach ($fees as $fee) {
                    $items[$fee['school_fee_id']] = [
                        'amount' => $fee['amount'],
                        'notes' => $fee['notes']
                    ];
                }
                $rateSheet->fees()->sync($items);
            }
    
            $rateSheet->load(['level', 'course', 'semester', 'fees']);
            DB::commit();
            return $rateSheet;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during RateSheetService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(RateSheet $rateSheet)
    {
        try {
            $rateSheet->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during RateSheetService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        } 
    }
}

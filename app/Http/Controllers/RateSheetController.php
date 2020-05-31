<?php

namespace App\Http\Controllers;

use App\RateSheet;
use Illuminate\Http\Request;
use App\Http\Resources\RateSheetResource;

class RateSheetController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 20;
        $query = RateSheet::with(['level', 'course', 'fees']);

        // filters
        $levelId = $request->level_id ?? false;
        $query->when($levelId, function($q) use ($levelId) {
            return $q->where('level_id', $levelId);
        });

        $courseId = $request->course_id ?? false;
        $query->when($courseId, function($q) use ($courseId) {
            return $q->where('course_id', $courseId);
        });

        $rates = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->all();
        return RateSheetResource::collection(
            $rates
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'level_id' => 'required'
        ]);

        $data = $request->except(['fees']);
        $rate = RateSheet::create($data);

        if ($request->has('fees')) {
            $fees = $request->fees;
            $items = [];
            foreach ($fees as $fee) {
                $items[$fee['school_fee_id']] = [
                    'amount' => $fee['amount']
                ];
            }
            $rate->fees()->sync($items);
        }

        $rate->load(['level', 'course', 'fees']);
        return new RateSheetResource($rate);
    }

    public function update(Request $request, RateSheet $rateSheet)
    {
        $this->validate($request, [
            'level_id' => 'required'
        ]);

        $data = $request->except(['fees']);
        $rateSheet->update($data);

        if ($request->has('fees')) {
            $fees = $request->fees;
            $items = [];
            foreach ($fees as $fee) {
                $items[$fee['school_fee_id']] = [
                    'amount' => $fee['amount']
                ];
            }
            $rateSheet->fees()->sync($items);
        }

        $rateSheet->load(['level', 'course', 'fees']);
        return new RateSheetResource($rateSheet);
    }

    public function show(RateSheet $rateSheet)
    {
        $rateSheet->load(['level', 'course', 'fees']);
        return new RateSheetResource($rateSheet);
    }

    public function destroy(RateSheet $rateSheet)
    {
        $rateSheet->delete();
        return response()->json([], 204);
    }
}

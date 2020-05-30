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
        $query = RateSheet::with(['items', 'level', 'course', 'items.schoolFee']);
        $rates = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->all();
        return RateSheetResource::collection(
            $rates
        );
    }

    public function getRateSheetOfLevel(Request $request, $levelId)
    {
        $perPage = $request->perPage ?? 20;
        $query = RateSheet::with(['items', 'level', 'course', 'items.schoolFee'])->where('level_id', $levelId);
        $rates = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return RateSheetResource::collection(
            $rates
        );
    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function show($levelId, $rateSheetId)
    {
        $rate = RateSheet::with(['items', 'level', 'course', 'items.schoolFee'])
            ->where('id', $rateSheetId)
            ->get();
        return new RateSheetResource($rate);
    }
}

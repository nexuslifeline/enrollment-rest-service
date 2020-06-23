<?php

namespace App\Http\Controllers;

use App\Billing;
use Illuminate\Http\Request;
use App\Http\Resources\BillingResource;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = Billing::with(['schoolYear', 'semester', 'billingType', 'studentFee', 'payments']);

        // filters

        // student
        $studentId = $request->student_id ?? false;
        $query->when($studentId, function($q) use ($studentId) {
            return $q->whereHas('student', function($query) use ($studentId) {
                return $query->where('student_id', $studentId);
            });
        });

        // school year
        $schoolYearId = $request->school_year_id ?? false;
        $query->when($schoolYearId, function($q) use ($schoolYearId) {
            return $q->whereHas('schoolYear', function($query) use ($schoolYearId) {
                return $query->where('school_year_id', $schoolYearId);
            });
        });

        // semester
        $semesterId = $request->semester_id ?? false;
        $query->when($semesterId, function($q) use ($semesterId) {
            return $q->whereHas('semester', function($query) use ($semesterId) {
                return $query->where('semester_id', $semesterId);
            });
        });

        // billing type
        $billingTypeId = $request->billing_type_id ?? false;
        $query->when($billingTypeId, function($q) use ($billingTypeId) {
            return $q->whereHas('billingType', function($query) use ($billingTypeId) {
                return $query->where('billing_type_id', $billingTypeId);
            });
        });

        // // filter by student name
        // $criteria = $request->criteria ?? false;
        // $query->when($criteria, function($q) use ($criteria) {
        //   return $q->whereHas('student', function($query) use ($criteria) {
        //     return $query->where('name', 'like', '%'.$criteria.'%')
        //               ->orWhere('first_name', 'like', '%'.$criteria.'%')
        //               ->orWhere('middle_name', 'like', '%'.$criteria.'%')
        //               ->orWhere('last_name', 'like', '%'.$criteria.'%');
        //   });
        // });

        $billings = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();

        return BillingResource::collection(
            $billings
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show(Billing $billing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Billing $billing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        //
    }
}

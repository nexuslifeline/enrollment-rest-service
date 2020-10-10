<?php

namespace App\Http\Controllers;

use App\StudentFee;
use App\AcademicRecord;
use Illuminate\Http\Request;
use App\Http\Resources\StudentFeeResource;
use App\Services\StudentFeeService;
use App\Services\StudentService;

class StudentFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $studentFeeService = new StudentFeeService();
        $data = $request->except('student_fee_items');
        $studentFeeItems = $request->student_fee_items ?? [];
        $studentFee = $studentFeeService->update($id, $data, $studentFeeItems);
        return new StudentFeeResource($studentFee);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getStudentFeeOfAcademicRecord($academicRecordId)
    {
        $studentFeeService = new StudentFeeService();
        $studentFee = $studentFeeService->getStudentFeeOfAcademicRecord($academicRecordId);
        return new StudentFeeResource($studentFee);
    }

    public function getStudentFeesOfStudent($studentId, Request $request)
    {
        $studentFeeService = new StudentFeeService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $studentFees = $studentFeeService->getStudentFeesOfStudent($studentId, $isPaginated, $perPage, $filters);
        return StudentFeeResource::collection($studentFees);
    }
}

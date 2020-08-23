<?php

namespace App\Http\Controllers;

use App\StudentFee;
use App\Transcript;
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
        //
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

    public function getStudentFeeOfTranscript($transcriptId)
    {
        $studentFeeService = new StudentFeeService();
        $studentFee = $studentFeeService->getStudentFeeOfTranscript($transcriptId);
        return new StudentFeeResource($studentFee);
    }

}

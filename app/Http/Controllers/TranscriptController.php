<?php

namespace App\Http\Controllers;

use App\Transcript;
use Illuminate\Http\Request;
use App\Http\Resources\TranscriptResource;

class TranscriptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = Transcript::with([
            'schoolYear', 
            'level', 
            'course', 
            'semester', 
            'schoolCategory', 
            'studentCategory',
            'studentType', 
            'application', 
            'admission',
            'student' => function($query) {
                $query->with(['address']);
            }]);

        // filters
        // student
        $studentId = $request->student_id ?? false;
        $query->when($studentId, function($q) use ($studentId) {
            return $q->whereHas('student', function($query) use ($studentId) {
                return $query->where('student_id', $studentId);
            });
        });

        // course
        $courseId = $request->course_id ?? false;
        $query->when($courseId, function($q) use ($courseId) {
            return $q->whereHas('course', function($query) use ($courseId) {
                return $query->where('course_id', $courseId);
            });
        });

        // school category
        $schoolCategoryId = $request->school_category_id ?? false;
        $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
            return $q->whereHas('schoolCategory', function($query) use ($schoolCategoryId) {
                return $query->where('school_category_id', $schoolCategoryId);
            });
        });

        // application status
        $applicationStatusId = $request->application_status_id ?? false;
        $query->when($applicationStatusId, function($q) use ($applicationStatusId) {
            return $q->where(function($q) use ($applicationStatusId) {
                return $q->whereHas('application', function($query) use ($applicationStatusId) {
                    return $query->where('application_status_id', $applicationStatusId);
                })->orWhereHas('admission', function($query) use ($applicationStatusId) {
                    return $query->where('application_status_id', $applicationStatusId);
                });
            });
        });

        // transcript status
        $transcriptStatusId = $request->transcript_status_id ?? false;
        $query->when($transcriptStatusId, function($query) use ($transcriptStatusId) {
            return $query->where('transcript_status_id', $transcriptStatusId);
        });

        $transcripts = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->all();

        // $registrar = $request->registrar ?? false;
        // $students->when($registrar, function($students) {
        //     return $students->append(['active_admission', 'active_application', 'transcript']);
        // });

        return TranscriptResource::collection(
            $transcripts
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
}

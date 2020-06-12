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
    public function update(Request $request, Transcript $transcript)
    {
        try {
          // return $request;
          $except = ['application', 'admission', 'student_fee', 'subjects', 'fees'];
          $data = $request->except($except);
          // return $data;
          $transcript->update($data);

          if ($request->has('application') && count($request->application) > 0) {
              $application = $transcript->application();
              if ($application) {
                  $application->update($request->application);
              }
          }

          if ($request->has('admission') && count($request->admission) > 0) {
              $admission = $transcript->admission();
              if ($admission) {
                  $admission->update($request->admission);
              }
          }

          if ($request->has('student_fee')) {
              $student = $transcript->student()->first();
              $student->studentFees()->updateOrCreate(['transcript_id' => $transcript->id], $request->student_fee);
              if ($request->has('fees')) {
                $fees = $request->fees;
                $items = [];
                foreach ($fees as $fee) {
                    $items[$fee['school_fee_id']] = [
                        'amount' => $fee['amount'],
                        'notes' => $fee['notes']
                    ];
                }
                $student->studentFees()->first()->studentFeeItems()->sync($items);
              }
          }

          if ($request->has('subjects')) {
              $transcript->subjects()->sync($request->subjects);
          }

          $transcript->load([
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
            }])->fresh();

          return new TranscriptResource($transcript);
        } catch (Throwable $e) {
            Log::info($e->getMessage());
            return response()->json([], 400); // Note! add error here
        }
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

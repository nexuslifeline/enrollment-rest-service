<?php

namespace App\Http\Controllers;

use App\Level;
use App\Subject;
use App\Transcript;
use App\Evaluation;
use App\Http\Requests\SubjectStoreRequest;
use App\Http\Requests\SubjectUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\SubjectResource;
use App\Services\SubjectService;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjectService = new SubjectService();
        $subjects = $subjectService->index($request);

        return SubjectResource::collection(
            $subjects
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectStoreRequest $request)
    {
        $subjectService = new SubjectService();
        $subject = $subjectService->store($request->all());
        
        return (new SubjectResource($subject))
            ->response()
            ->setStatusCode(201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $subject->load(['schoolCategory']);
        return new SubjectResource($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectUpdateRequest $request, Subject $subject)
    {
        $subjectService = new SubjectService();
        $subject = $subjectService->update($request->all(), $subject);

        return (new SubjectResource($subject))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subjectService = new SubjectService();
        $subjectService->delete($subject);
        return response()->json([], 204);
    }

    public function getSubjectsOfLevel($levelId, Request $request)
    {
        $subjectService = new SubjectService();
        $subjects = $subjectService->getSubjectsOfLevel($levelId, $request);    

        return SubjectResource::collection($subjects);
    }

    public function getSubjectsOfTranscript($transcriptId, Request $request)
    {
        $subjectService = new SubjectService();
        $subjects = $subjectService->getSubjectsOfTranscript($transcriptId, $request);
        return SubjectResource::collection($subjects);
    }

    public function getSubjectsOfEvaluation($evaluationId, Request $request)
    {
        $subjectService = new SubjectService();
        $subjects = $subjectService->getSubjectsOfEvaluation($evaluationId, $request);
        return SubjectResource::collection($subjects);
    }
}

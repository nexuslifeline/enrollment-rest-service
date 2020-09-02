<?php

namespace App\Http\Controllers;

use App\Level;
use App\Subject;
use App\Transcript;
use App\Evaluation;
use App\SectionSchedule;
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
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $subjects = $subjectService->list($isPaginated, $perPage, $filters);

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
    public function show(int $id)
    {
        $subjectService = new SubjectService();
        $subject = $subjectService->get($id);
        return new SubjectResource($subject);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(SubjectUpdateRequest $request, int $id)
    {
        $subjectService = new SubjectService();
        $subject = $subjectService->update($request->all(), $id);

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
    public function destroy(int $id)
    {
        $subjectService = new SubjectService();
        $subjectService->delete($id);
        return response()->json([], 204);
    }

    public function getSectionsOfSubject(bool $isPaginated, int $perPage, array $filters, int $subjectId) {
        try {

            $query = Section::with(['schoolYear','schoolCategory','level','course','semester']);


            $schoolYearId = $filters['school_year_id'] ?? false;
            $query->when($schoolYearId, function($q) use ($schoolYearId) {
                return $q->where('school_year_id', $schoolYearId);
            });

            $query->whereHas('schedules', function($q) use ($subjectId) {
                return $q->where('subject_id', $subjectId);
            });

            $query->with(['schedules' => function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
                return $q->with(['personnel']);
            }]);

            $sections = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

          return $sections;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SectionService get sections of subject method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfLevel($levelId, Request $request)
    {
        $subjectService = new SubjectService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $subjects = $subjectService->getSubjectsOfLevel($levelId, $isPaginated, $perPage, $filters);

        return SubjectResource::collection($subjects);
    }

    public function getSubjectsOfTranscript($transcriptId, Request $request)
    {
        $subjectService = new SubjectService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $subjects = $subjectService->getSubjectsOfTranscript($transcriptId, $isPaginated, $perPage);
        return SubjectResource::collection($subjects);
    }

    public function getSubjectsOfEvaluation($evaluationId, Request $request)
    {
        $subjectService = new SubjectService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $subjects = $subjectService->getSubjectsOfEvaluation($evaluationId, $isPaginated, $perPage);
        return SubjectResource::collection($subjects);
    }

    public function getScheduledSubjectsOfSection($sectionId, Request $request)
    {
        $subjectService = new SubjectService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $subjects = $subjectService->getSectionScheduledSubjects($sectionId, $isPaginated, $perPage);
        return SubjectResource::collection($subjects);
    }

    public function getSectionUnscheduledSubjects($evaluationId, Request $request)
    {
        $subjectService = new SubjectService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $subjects = $subjectService->getSectionUnscheduledSubjects($evaluationId, $isPaginated, $perPage);
        return SubjectResource::collection($subjects);
    }
}

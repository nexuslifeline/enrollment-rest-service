<?php

namespace App\Http\Controllers;

use App\Curriculum;
use Illuminate\Http\Request;
use App\Http\Resources\CurriculumResource;

class CurriculumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $query = Curriculum::with(['schoolCategory', 'course', 'level']);

        // filters
        $schoolCategoryId = $request->school_category_id ?? false;
        $query->when($schoolCategoryId, function($q) use ($schoolCategoryId) {
            return $q->where('school_category_id', $schoolCategoryId);
        });

        $courseId = $request->course_id ?? false;
        $query->when($courseId, function($q) use ($courseId) {
            return $q->where('course_id', $courseId);
        });

        $levelId = $request->level_id ?? false;
        $query->when($levelId && !$courseId, function($q) use ($levelId) {
            return $q->where('level_id', $levelId);
        });

        $active = $request->active ?? false;
        $query->when($active, function($q) use ($active) {
            return $q->where('active', $active);
        });

        $subjects = $request->subjects ?? false;
        $query->when($subjects, function($q) use ($request) {
            return $q->with(['subjects' => function ($query) use ($request) {
                $semesterId = $request->semester_id ?? false;
                $query->when($semesterId, function($q) use ($semesterId) {
                    return $q->where('semester_id', $semesterId);
                });
                $levelId = $request->level_id ?? false;
                $query->when($levelId, function($q) use ($levelId) {
                    return $q->where('level_id', $levelId);
                });
            }]);
        });

        $curriculums = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();        

        // $curriculums->load(['schoolCategory', 'course', 'level']);
          
        return CurriculumResource::collection(
            $curriculums
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
      // return $request->prerequisites;
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'school_category_id' => 'required|numeric',
            'course_id' => 'required_if:school_category_id,4,5,6,7',
            'level_id' => 'required_if:school_category_id,1,2,3,6,7',
            'effective_year' => 'required|digits:4|integer|min:1950|max:2100'
        ],
        [
            'required_if' => 'The :attribute field is required.',
            'effective_year.digits' => 'The :attribute field is invalid.'
        ], 
        [
            'school_category_id' => 'school category',
            'course_id' => 'course'
        ]);

        $data = $request->except('subjects', 'prerequisites');

        $curriculum = Curriculum::create($data);
        
        if ($request->has('subjects')) {
            $subjects = $request->subjects;
            $items = [];
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'course_id' => $request->course_id,
                    'school_category_id' => $request->school_category_id,
                    'level_id' => $subject['level_id'],
                    'semester_id' => $subject['semester_id']
                ];

                if ($request->has('prerequisites')) {
                    $prerequisites = $request->prerequisites;
                    $prerequisiteItems = [];
                    foreach ($prerequisites as $prerequisite) {
                        if ($subject['subject_id'] === $prerequisite['subject_id']) {
                            $prerequisiteItems[$prerequisite['prerequisite_subject_id']] = [
                                'subject_id' => $prerequisite['subject_id'],
                            ];
                        }
                    }
                    $curriculum->prerequisites()
                    ->wherePivot('subject_id', $subject['subject_id'])
                    ->sync($prerequisiteItems);
                }
            }
            $curriculum->subjects()->sync($items);
        }

        if ($request->active) {
          $curriculums = Curriculum::where('school_category_id', $request->school_category_id)
          ->where('course_id', $request->course_id)
          ->where('level_id', $request->level_id)
          ->where('id', '!=', $curriculum->id);
          $curriculums->update([
            'active' => 0
          ]);
        }   

        $curriculum->load(['schoolCategory', 'course', 'level']);
        return (new CurriculumResource($curriculum))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function show(Curriculum $curriculum)
    {
        // return $curriculum->id;
        $curriculum->load(['schoolCategory', 'course', 'level', 'subjects' => function($query) use ($curriculum) {
            return $query->with(['prerequisites' => function ($query) use ($curriculum) {
                $query->where('curriculum_id', $curriculum->id);
            }]);
        }]);
        return new CurriculumResource($curriculum);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curriculum $curriculum)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'school_category_id' => 'required|numeric',
            'course_id' => 'required_if:school_category_id,4,5,6,7',
            'level_id' => 'required_if:school_category_id,1,2,3,6,7',
            'effective_year' => 'required|digits:4|integer|min:1950|max:2100'
        ],
        [
            'required_if' => 'The :attribute field is required.',
            'effective_year.digits' => 'The :attribute field is invalid.'
        ], 
        [
            'school_category_id' => 'school category',
            'course_id' => 'course'
        ]);

        $data = $request->except('subjects', 'prerequisites');

        $success = $curriculum->update($data);

        if ($request->has('subjects')) {
            $subjects = $request->subjects;
            $items = [];
            foreach ($subjects as $subject) {
                $items[$subject['subject_id']] = [
                    'course_id' => $request->course_id,
                    'school_category_id' => $request->school_category_id,
                    'level_id' => $subject['level_id'],
                    'semester_id' => $subject['semester_id']
                ];

                if ($request->has('prerequisites')) {
                    $prerequisites = $request->prerequisites;
                    $prerequisiteItems = [];
                    foreach ($prerequisites as $prerequisite) {
                        if ($subject['subject_id'] === $prerequisite['subject_id']) {
                            $prerequisiteItems[$prerequisite['prerequisite_subject_id']] = [
                                'subject_id' => $prerequisite['subject_id'],
                            ];
                        }
                    }
                    $curriculum->prerequisites()
                    ->wherePivot('subject_id', $subject['subject_id'])
                    ->sync($prerequisiteItems);
                }
            }
            $curriculum->subjects()->sync($items);
        }
        
        if ($request->active) {
          $curriculums = Curriculum::where('school_category_id', $request->school_category_id)
          ->where('course_id', $request->course_id)
          ->where('level_id', $request->level_id)
          ->where('id', '!=', $curriculum->id);
          $curriculums->update([
            'active' => 0
          ]);
        }   
        
        if($success){
            $curriculum->load(['schoolCategory', 'course', 'level']);
            return (new CurriculumResource($curriculum))
            ->response()
            ->setStatusCode(200);
        }
      return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Curriculum  $curriculum
     * @return \Illuminate\Http\Response
     */
    public function destroy(Curriculum $curriculum)
    {
        $curriculum->delete();
        return response()->json([], 204);
    }
}

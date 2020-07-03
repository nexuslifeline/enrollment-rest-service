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
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'major' => 'required|string|max:191'
        ]);

        $data = $request->except('subjects');

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
            }
            $curriculum->subjects()->sync($items);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Curriculum $curriculum)
    {
        $curriculum->load(['subjects']);
        return new CurriculumResource($curriculum);
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

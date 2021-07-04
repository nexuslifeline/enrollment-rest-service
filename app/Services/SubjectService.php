<?php

namespace App\Services;

use App\Level;
use Exception;
use App\Subject;
use App\Evaluation;
use App\AcademicRecord;
use App\SectionSchedule;
use App\TranscriptRecord;
use App\TranscriptRecordSubject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectService
{
    public function list(bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Subject::with(['department', 'schoolCategory']);

            //filter by school category
            $schoolCategoryId = $filters['school_category_id'] ?? false;
            $query->when($schoolCategoryId, function ($q) use ($schoolCategoryId) {
                return $q->where('school_category_id', $schoolCategoryId);
            });

            $sectionId = $filters['section_id'] ?? false;
            $query->when($sectionId, function ($q) use ($sectionId) {
                return $q->whereHas('schedules', function ($q) use ($sectionId) {
                    return $q->where('section_id', $sectionId);
                });
            });

            $curriculumId = $filters['curriculum_id'] ?? false;
            $levelId = $filters['level_id'] ?? false;
            $courseId = $filters['course_id'] ?? false;
            $semesterId = $filters['semester_id'] ?? false;
            $query->when($curriculumId, function ($q) use ($curriculumId, $levelId, $courseId, $semesterId) {
                return $q->whereHas('curriculums', function ($q) use ($curriculumId, $levelId, $courseId, $semesterId) {
                    return $q->where('curriculum_id', $curriculumId)
                        ->when($levelId, function ($q) use ($levelId) {
                            return $q->where('level_id', $levelId);
                        })
                        ->when($courseId, function ($q) use ($courseId) {
                            return $q->where('course_id', $courseId);
                        })
                        ->when($semesterId, function ($q) use ($semesterId) {
                            return $q->where('semester_id', $semesterId);
                        });
                });
            });
            
            

            $criteria = $filters['criteria'] ?? false;
            $query->when($criteria, function ($query) use ($criteria) {
                return $query->where(function ($q) use ($criteria) {
                    return $q->where('name', 'like', '%' . $criteria . '%')
                        ->orWhere('description', 'like', '%' . $criteria . '%')
                        ->orWhere('code', 'like', '%' . $criteria . '%');
                });
            });

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService list method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function get(int $id)
    {
        try {
            $subject = Subject::find($id);
            $subject->load(['schoolCategory']);
            return $subject;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService get method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $subject = Subject::create($data);
            $subject->load(['schoolCategory']);
            DB::commit();
            return $subject;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SubjectService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $subject = Subject::find($id);
            $subject->update($data);
            $subject->load(['schoolCategory']);
            DB::commit();
            return $subject;
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SubjectService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $subject = Subject::find($id);
            $subject->delete();
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Error occured during SubjectService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfLevel(int $levelId, bool $isPaginated, int $perPage, array $filters)
    {
        try {
            $query = Level::find($levelId)->subjects();

            // filters
            $courseId = $filters['course_id'] ?? false;
            $query->when($courseId, function ($q) use ($courseId) {
                return $q->where('course_id', $courseId);
            });

            $semesterId = $filters['semester_id'] ?? false;
            $query->when($semesterId, function ($q) use ($semesterId) {
                return $q->where('semester_id', $semesterId);
            });

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects->unique('id');
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjectsOfLevel method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfAcademicRecord(int $academicRecordId, bool $isPaginated, int $perPage)
    {
        try {
            $query = AcademicRecord::find($academicRecordId)->subjects();

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            $subjects->append('section');
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjectsOfAcademicRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfAcademicRecordWithSchedules(int $academicRecordId, bool $isPaginated, int $perPage)
    {
        try {
            $query = AcademicRecord::find($academicRecordId)->subjects();

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            $subjects->append('section_schedule');
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjectsOfAcademicRecordWithSchedules method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSubjectsOfTranscriptRecord(int $transcriptRecordId, bool $isPaginated, int $perPage)
    {
        try {
            $transriptRecord = TranscriptRecord::find($transcriptRecordId);
            $query = $transriptRecord->subjects()
                ->with(['prerequisites' => function ($query) use ($transriptRecord) {
                    return $query->with(['prerequisites' => function ($query) use ($transriptRecord) {
                        $query->where('curriculum_id', $transriptRecord->curriculum_id);
                    }]);
                }]);

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSubjectsOfTranscriptRecord method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }


    public function getSectionScheduledSubjects(int $sectionId, bool $isPaginated, int $perPage)
    {
        try {
            $subjectIds = SectionSchedule::select('subject_id')
                ->where('section_id', $sectionId)
                ->get()
                ->pluck('subject_id');

            $query = Subject::with(['schedules' => function ($q) use ($sectionId) {
                return $sectionId ? $q->where('section_id', $sectionId)->with(['personnel', 'section']) : $q;
            }])->whereIn('id', $subjectIds);

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSectionScheduledSubjects method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSectionUnscheduledSubjects(int $transcriptRecordId, int $studentId, int $curriculumId, bool $isPaginated, int $perPage)
    {
        try {

            if (!$studentId) {
                throw new Exception('Student id not found!');
            }

            if (!$transcriptRecordId) {
                throw new Exception('Transcript Record id not found!');
            }

            if (!$curriculumId) {
                throw new Exception('Curriculum id not found!');
            }

            $transcriptRecord = TranscriptRecord::find($transcriptRecordId);

            $query = $transcriptRecord->subjects();

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            foreach ($subjects as $subject) {
                $subject->is_allowed = $this->isAllowedToTake(
                    $studentId,
                    $subject->id,
                    $curriculumId
                );
            }

            $subjects->append(['is_allowed']);
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSectionUnscheduledSubjects method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function getSectionScheduledSubjectsWithStatus(int $sectionId, int $studentId, int $curriculumId, bool $isPaginated, int $perPage)
    {
        try {
            if (!$studentId) {
                throw new Exception('Student id not found!');
            }

            if (!$sectionId) {
                throw new Exception('Section id not found!');
            }

            if (!$curriculumId) {
                throw new Exception('Curriculum id not found!');
            }

            $subjectIds = SectionSchedule::select('subject_id')
                ->where('section_id', $sectionId)
                ->get()
                ->pluck('subject_id');

            $query = Subject::with(['schedules' => function ($q) use ($sectionId) {
                return $sectionId ? $q->where('section_id', $sectionId)->with(['personnel', 'section']) : $q;
            }])->whereIn('id', $subjectIds);

            $subjects = $isPaginated
                ? $query->paginate($perPage)
                : $query->get();

            foreach ($subjects as $subject) {

                $subject->is_allowed = $this->isAllowedToTake(
                    $studentId,
                    $subject->id,
                    $curriculumId
                );
            }

            $subjects->append(['is_allowed']);
            return $subjects;
        } catch (Exception $e) {
            Log::info('Error occured during SubjectService getSectionScheduledSubjectsWithStatus method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    private function isAllowedToTake(int $studentId, int $subjectId, $curriculumId)
    {

        // - allowed if
        // - subject is not taken yet or subject is taken but with failed grade
        // - and all pre requisite were taken with passed grade
        if (
            !$this->isTaken($studentId, $subjectId) &&
            $this->arePrereqPassed($studentId, $subjectId, $curriculumId)
        ) {
            return true;
        }

        if (
            $this->isTaken($studentId, $subjectId) &&
            !$this->isPassed($studentId, $subjectId) &&
            $this->arePrereqPassed($studentId, $subjectId, $curriculumId)
        ) {
            return true;
        }

        return false;
    }

    private function isTaken(int $studentId, int $subjectId)
    {
        //disabled this for now for checking purposes
        //because it returns error on this part
        //need further debugging
        // return false;
        // $transcriptRecordIds = TranscriptRecord::where('student_id', $studentId)
        //     ->get()
        //     ->pluck('id')
        //     ->flatten();

        $transcriptRecords = TranscriptRecord::where('student_id', $studentId)
            ->whereHas('subjects', function ($q) use ($subjectId) {
                return $q->where('subject_id', $subjectId)
                ->where('is_taken', 1);
            })
            ->get();

        return $transcriptRecords->count() > 0;
        // $transcriptRecordSubjects = TranscriptRecordSubject::whereIn('transcript_record_id', $transcriptRecordIds)
            // ->where('subject_id', $subjectId)
            // ->where('is_taken', 1)
            // ->get()
            // ->count() > 0;
    }

    // return true if grade is passed, if not taken yet this will return false
    private function isPassed(int $studentId, int $subjectId)
    {
        // $transcriptRecordIds = TranscriptRecord::where('student_id', $studentId)
        //     ->get()
        //     ->pluck('id')
        //     ->flatten();

        $transcriptRecords = TranscriptRecord::where('student_id', $studentId)
            ->whereHas('subjects', function ($q) use ($subjectId) {
                return $q->where('subject_id', $subjectId)
                ->where('grade', '>', 74.4);
            })
            ->get();

        return $transcriptRecords->count() > 0;

        // return TranscriptRecordSubject::whereIn('transcript_record_id', $transcriptRecordIds)
        //     ->where('subject_id', $subjectId)
        //     ->where('grade', '>', 74.4)
        //     ->get()
        //     ->count() > 0;
    }

    private function arePrereqPassed(int $studentId, int $subjectId, int $curriculumId)
    {
        $subjects = Subject::with(['prerequisites' => function ($query) use ($curriculumId) {
            return $query->where('curriculum_id', $curriculumId);
        }])->find($subjectId);

        $prerequisites = $subjects->prerequisites;

        if (count($prerequisites) === 0) { // if there are no pre requisite, automatically return passed
            return true;
        }

        $passed = true;
        foreach ($prerequisites as $prerequisite) {
            $passed = $passed && $this->isPassed(
                $studentId,
                $prerequisite->id
            );
            if (!$passed) break;
        }

        return $passed;
    }
}

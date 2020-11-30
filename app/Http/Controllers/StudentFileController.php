<?php

namespace App\Http\Controllers;

use App\Student;
use App\StudentFile;
use App\Http\Resources\StudentFileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\StudentFileService;
use App\Http\Requests\StudentFileStoreRequest;
use App\Http\Requests\StudentFileUpdateRequest;

class StudentFileController extends Controller
{
    public function index(Request $request, $studentId)
    {
        $studentFileService = new StudentFileService();
        $files = $studentFileService->index($request, $studentId);
        return StudentFileResource::collection(
            $files
        );
    }

    public function store(StudentFileStoreRequest $request, $studentId)
    {

        try {
            $file = $request->file('file');
            $studentFileService = new StudentFileService();
            $studentFile = $studentFileService->store($studentId, $file);
            return (new StudentFileResource($studentFile))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function show($studentId, $fileId)
    {
        $studentFile = StudentFile::find($fileId);
        return new StudentFileResource($studentFile);
    }


    public function update(StudentFileUpdateRequest $request, $studentId,  $fileId)
    {
        try {
            $data = $request->all();
            $studentFileService = new StudentFileService();
            $studentFile = $studentFileService->update($data, $fileId);
            return (new StudentFileResource($studentFile))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function preview($studentId, $fileId)
    {

        try {
            $studentFileService = new StudentFileService();
            return $studentFileService->preview($fileId);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentFile  $studentFile
     * @return \Illuminate\Http\Response
     */
    public function destroy($studentId, $fileId)
    {
        try {
            $studentFileService = new StudentFileService();
            if ($studentFileService->delete($fileId)) {
                return response()->json([], 204);
            }
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }
}

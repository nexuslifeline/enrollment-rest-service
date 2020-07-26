<?php

namespace App\Http\Controllers;

use Image;
use App\StudentPhoto;
use Illuminate\Http\Request;
use App\Services\StudentPhotoService;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\StudentPhotoResource;
use App\Http\Requests\StudentPhotoStoreRequest;

class StudentPhotoController extends Controller
{
    public function store(StudentPhotoStoreRequest $request, $studentId)
    {
        try {
            $file = $request->file('photo');
            $photoService = new StudentPhotoService();
            $studentPhoto = $photoService->store($studentId, $file);
            return (new StudentPhotoResource($studentPhoto))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function destroy($studentId)
    {
        try {
            $photoService = new StudentPhotoService();
            if ($photoService->delete($studentId)) {
                return response()->json([], 204);
            }
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\StudentPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\StudentPhotoResource;

class StudentPhotoController extends Controller
{
    public function store(Request $request, $studentId)
    {
        try {
            $this->validate($request, [
                'photo' => 'required'
            ]);
            // Note! will add resize here

            $path = $request->file('photo')->store('photos');
            $studentPhoto = StudentPhoto::updateOrCreate(
            ['student_id' => $studentId],
            [
                'path' => $path,
                'name' => $request->file('photo')->getClientOriginalName()
            ]);
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
            $query = StudentPhoto::where('student_id', $studentId);
            $photo = $query->first();
            if ($photo) {
                Storage::delete($photo->path);
                $query->delete();
                return response()->json([], 204);
            }
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }
}

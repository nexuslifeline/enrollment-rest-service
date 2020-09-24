<?php

namespace App\Services;

use Image;
use App\StudentPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StudentPhotoService
{

    public function store($studentId, $file)
    {
        try {
            if (!$studentId) {
                throw new \Exception('Student id not found!');
            }

            if (!$file) {
                throw new \Exception('File not found!');
            }

            $image = Image::make($file)->resize(null, 350, function ($constraint) {
                $constraint->aspectRatio();
            });
            //$path = $request->file('photo')->store('public');
            $path = 'public/' . $file->hashName();
            Storage::put($path, $image->stream());

            $studentPhoto = StudentPhoto::updateOrCreate(
                ['student_id' => $studentId],
                [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'hash_name' => $file->hashName()
                ]
            );

            return $studentPhoto;
        } catch (Exception $e) {
            Log::info('Error occured during StudentPhotoService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete($studentId)
    {
        try {
            if (!$studentId) {
                throw new \Exception('Student id not found!');
            }

            $query = StudentPhoto::where('student_id', $studentId);
            $photo = $query->first();
            if ($photo) {
                Storage::delete($photo->path);
                $query->delete();
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::info('Error occured during StudentPhotoService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
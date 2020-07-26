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
        } catch (Throwable $e) {
            ValidationException::withMessages([
                'photo' => $e->getMessage()
            ]);
        }
    }

    public function delete($studentId)
    {
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
    }
}
<?php

namespace App\Services;

use Image;
use App\PersonnelPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PersonnelPhotoService
{

    public function store($personnelId, $file)
    {
        try {
            if (!$personnelId) {
                throw new \Exception('Personnel id not found!');
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

            $personnelPhoto = PersonnelPhoto::updateOrCreate(
                ['personnel_id' => $personnelId],
                [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'hash_name' => $file->hashName()
                ]
            );

            return $personnelPhoto;
        } catch (Exception $e) {
            Log::info('Error occured during StudentPhotoService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete($personnelId)
    {
        try {
            if (!$personnelId) {
                throw new \Exception('Personnel id not found!');
            }

            $query = PersonnelPhoto::where('personnel_id', $studentId);
            $photo = $query->first();
            if ($photo) {
                Storage::delete($photo->path);
                $query->delete();
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentReceiptFileService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
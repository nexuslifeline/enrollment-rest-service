<?php

namespace App\Services;

use Image;
use App\StudentFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StudentFileService
{

    public function index(object $request, $studentId)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $query = StudentFile::where('student_id', $studentId)
                ->with('documentType');

            // $evaluationId = $request->evaluation_id ?? false;
            // $query->when($evaluationId, function ($q) use ($evaluationId) {
            //     return $q->where('evaluation_id', $evaluationId);
            // });

            $files = !$request->has('paginate') || $request->paginate === 'true'
                ? $query->paginate($perPage)
                : $query->get();

            return $files;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentFileService index method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store($studentId, $file)
    {
        try {
            if (!$studentId) {
                throw new \Exception('Student id not found!');
            }

            if (!$file) {
                throw new \Exception('File not found!');
            }

            $extension = $file->extension();
            $imageExtensions = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'bmp', 'jfif', 'tiff', 'tif'];

            //if there's a better condition to check if the file is an image or not
            //and the resize value
            if (in_array($extension, $imageExtensions)) {

                $width = Image::make($file)->width();
                $image = Image::make($file);

                //if image width is less than 1024 dont resize
                //not sure if this is necessary ?
                $image->resize(null, 600, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $path = 'files/student/' . $file->hashName();
                Storage::put($path, $image->stream());
            } else {
                $path = $file->store('files/student');
            }

            $studentFile = StudentFile::Create(
                [
                    'student_id' => $studentId,
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'hash_name' => $file->hashName()
                ]
            );

            return $studentFile;
        } catch (Exception $e) {
            Log::info('Error occured during StudentFileService store method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function delete($fileId)
    {
        try {
            if (!$fileId) {
                throw new \Exception('File id not found!');
            }

            $query = StudentFile::where('id', $fileId);
            $file = $query->first();
            if ($file) {
                Storage::delete($file->path);
                $query->delete();
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::info('Error occured during StudentFileService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function preview($fileId)
    {
        try {
            if (!$fileId) {
                throw new \Exception('File id not found!');
            }

            $query = StudentFile::where('id', $fileId);
            $studentFile = $query->first();

            if ($studentFile) {
                return  response()->file(
                    storage_path('app/' . $studentFile->path)
                );
            }
            return null;
        } catch (Exception $e) {
            Log::info('Error occured during StudentFileService preview method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update($data, $fileId)
    {
        try {
            if (!$fileId) {
                throw new \Exception('File id not found!');
            }

            $query = StudentFile::where('id', $fileId);
            $studentFile = $query->first();

            $studentFile->update($data);

            return  $studentFile;
        } catch (Exception $e) {
            Log::info('Error occured during StudentFileService preview method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}

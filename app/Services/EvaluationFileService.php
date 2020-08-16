<?php

namespace App\Services;

use Image;
use App\EvaluationFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EvaluationFileService
{

    public function store($evaluationId, $file)
    {
        try {
            if (!$evaluationId) {
                throw new \Exception('Student id not found!');
            }

            if (!$file) {
                throw new \Exception('File not found!');
            }
            
            $extension = $file->extension();
            $imageExtensions = ['jpg','png','jpeg','gif','svg','bmp'];
            
            //if there's a better condition to check if the file is an image or not
            //and the resize value
            if (in_array($extension, $imageExtensions )) {
                
                $width = Image::make($file)->width();
                $image = Image::make($file);
                
                //if image width is less than 1024 dont resize
                //not sure if this is necessary ?
                if ($width > 1024) {
                    $image->resize(null, 1024, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                
                $path = 'files/evaluation/' . $file->hashName();
                Storage::put($path, $image->stream());
            }
            else {
                $path = $file->store('files/evaluation');
            }

            $evaluationFile = EvaluationFile::Create(
                [
                    'evaluation_id' => $evaluationId,
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'hash_name' => $file->hashName()
                ]
            );

            return $evaluationFile;
        } catch (Throwable $e) {
            ValidationException::withMessages([
                'file' => $e->getMessage()
            ]);
        }
    }

    public function delete($fileId)
    {
        if (!$fileId) {
            throw new \Exception('File id not found!');
        }

        $query = EvaluationFile::where('id', $fileId);
        $file = $query->first();
        if ($file) {
            Storage::delete($file->path);
            $query->delete();
            return true;
        }
        return false;
    }

    public function preview($fileId) {

        if (!$fileId) {
            throw new \Exception('File id not found!');
        }

        $query = EvaluationFile::where('id', $fileId);
        $evaluationFile = $query->first();
        
        if ($evaluationFile) {
            return  response()->file(
                storage_path('app/' . $evaluationFile->path)
            );
        }
        return null;
    }

    public function update($data, $fileId) {

        if (!$fileId) {
            throw new \Exception('File id not found!');
        }

        $query = EvaluationFile::where('id', $fileId);
        $evaluationFile = $query->first();
        
        $evaluationFile->update($data);
        
        return  $evaluationFile;
    }
}
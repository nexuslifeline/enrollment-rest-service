<?php

namespace App\Http\Controllers;

use App\Admission;
use App\AdmissionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AdmissionFileResource;

class AdmissionFileController extends Controller
{
    public function index(Request $request, $admissionId)
    {
        $perPage = $request->per_page ?? 20;
        $query = Admission::where('id', $admissionId)->first()->files();
        $files = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return AdmissionFileResource::collection(
            $files
        );
    }

    public function store(Request $request, $admissionId)
    {
        try {
            $this->validate($request, [
                'file' => 'required'
            ]);
            $path = $request->file('file')->store('files');
            $admissionFile = AdmissionFile::create([
                'admission_id' => $admissionId,
                'path' => $path,
                'name' => $request->file('file')->getClientOriginalName()
            ]);
            return (new AdmissionFileResource($admissionFile))
            ->response()
            ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function update(Request $request, $admissionId,  $fileId)
    {
        $this->validate($request, [
            'notes' => 'required|max:191',
        ]);

        $data = $request->all();

        $admissionFile = AdmissionFile::find($fileId);
        
        $success = $admissionFile->update($data);
    
        if ($success) {
            return (new AdmissionFileResource($admissionFile))
                ->response()
                ->setStatusCode(200);
        }
        //return response()->json([], 400); // Note! add error here
    }

    public function show($admissionId, $fileId)
    {
        $admissionFile = AdmissionFile::find($fileId);
        return new AdmissionFileResource($admissionFile);
    }

    public function preview($admissionId, $fileId)
    {
        try {
            $admissionFile = AdmissionFile::find($fileId);
            return response()->file(
                storage_path('app/' . $admissionFile->path)
            );
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function destroy($admissionId, $fileId)
    {
        $file = AdmissionFile::find($fileId);

        Admission::find($admissionId)
            ->files()
            ->where('id', $fileId)
            ->first()
            ->delete();
        Storage::delete($file->path);
        return response()->json([], 204);
    }

}

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
        $perPage = $request->perPage ?? 20;
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
        //AdmissionFile::find($fileId)->delete();
        Admission::find($admissionId)
            ->files()
            ->where('id', $fileId)
            ->first()
            ->delete();
        return response()->json([], 204);
    }

}

<?php

namespace App\Http\Controllers;
use Image;
use App\PersonnelPhoto;
use Illuminate\Http\Request;
use App\Services\PersonnelPhotoService;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PersonnelPhotoResource;
use App\Http\Requests\PersonnelPhotoStoreRequest;


class PersonnelPhotoController extends Controller
{
    public function store(PersonnelPhotoStoreRequest $request, $personnelId)
    {
        try {
            $file = $request->file('photo');
            $photoService = new PersonnelPhotoService();
            $personnelPhoto = $photoService->store($personnelId, $file);
            return (new PersonnelPhotoResource($personnelPhoto))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function destroy($personnelId)
    {
        try {
            $photoService = new PersonnelPhotoService();
            if ($photoService->delete($personnelId)) {
                return response()->json([], 204);
            }
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }
}

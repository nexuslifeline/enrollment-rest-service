<?php

namespace App\Http\Controllers;

use App\Evaluation;
use App\EvaluationFile;
use App\Http\Resources\EvaluationFileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvaluationFileController extends Controller
{
    public function index(Request $request, $evaluttionId)
    {
        $perPage = $request->per_page ?? 20;
        $query = Evaluation::where('id', $evaluttionId)->first()->files();
        $files = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return EvaluationFileResource::collection(
            $files
        );
    }

    public function store(Request $request, $evaluationId)
    {
        try {
            $this->validate($request, [
                'file' => 'required'
            ]);
            $path = $request->file('file')->store('files/evaluation');
            $evaluationFile = EvaluationFile::create([
                'evaluation_id' => $evaluationId,
                'path' => $path,
                'name' => $request->file('file')->getClientOriginalName()
            ]);
            return (new EvaluationFileResource($evaluationFile))
            ->response()
            ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function show($evaluationId, $fileId)
    {
        $evaluationFile = EvaluationFile::find($fileId);
        return new EvaluationFileResource($evaluationFile);
    }


    public function update(Request $request, $evaluationId,  $fileId)
    {
        $this->validate($request, [
            'notes' => 'required',
        ]);

        $data = $request->all();

        $evaluationFile = EvaluationFile::find($fileId);
        
        $success = $evaluationFile->update($data);
    
        if ($success) {
            return (new EvaluationFileResource($evaluationFile))
                ->response()
                ->setStatusCode(200);
        }
    }

    public function preview($evaluationId, $fileId)
    {
        try {
            $evaluationFile = EvaluationFile::find($fileId);
            return response()->file(
                storage_path('app/' . $evaluationFile->path)
            );
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EvaluationFile  $evaluationFile
     * @return \Illuminate\Http\Response
     */
    public function destroy($evaluationId, $fileId)
    {
        $file = EvaluationFile::find($fileId);

        Evaluation::find($evaluationId)
            ->files()
            ->where('id', $fileId)
            ->first()
            ->delete();
        Storage::delete($file->path);
        return response()->json([], 204);
    }
}

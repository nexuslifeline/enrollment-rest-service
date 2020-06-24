<?php

namespace App\Http\Controllers;

use App\Payment;
use App\PaymentFile;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentFileResource;
use Illuminate\Support\Facades\Storage;

class PaymentFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $paymentId)
    {
        $perPage = $request->per_age ?? 20;
        $query = Payment::where('id', $paymentId)->first()->files();
        $files = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return PaymentFileResource::collection(
            $files
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $paymentId)
    {
        try {

            $this->validate($request, [
                'file' => 'required'
            ]);

            $path = $request->file('file')->store('files');

            $paymentFile = PaymentFile::create([
                'payment_id' => $paymentId,
                'path' => $path,
                'name' => $request->file('file')->getClientOriginalName(),
                'hash_name' => $request->file('file')->hashName()
            ]);
            return (new PaymentFileResource($paymentFile))
            ->response()
            ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function preview($paymentId, $fileId)
    {
        try {
            $paymentFile = PaymentFile::find($fileId);
            return response()->file(
                storage_path('app/' . $paymentFile->path)
            );
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $paymentId,  $fileId)
    {
        $this->validate($request, [
            'notes' => 'required|max:191',
        ]);

        $data = $request->all();

        $paymentFile = PaymentFile::find($fileId);
        
        $success = $paymentFile->update($data);
    
        if ($success) {
            return (new PaymentFileResource($paymentFile))
                ->response()
                ->setStatusCode(200);
        }
        //return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($paymentId, $fileId)
    {
        $file = PaymentFile::find($fileId);

        Payment::find($paymentId)
            ->files()
            ->where('id', $fileId)
            ->first()
            ->delete();
            
        Storage::delete($file->path);
        return response()->json([], 204);
    }
}

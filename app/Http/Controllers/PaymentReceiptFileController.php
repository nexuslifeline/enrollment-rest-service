<?php

namespace App\Http\Controllers;

use App\PaymentReceiptFile;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PaymentReceiptFileResource;

class PaymentReceiptFileController extends Controller
{
    public function index(Request $request, $paymentId)
    {
        $perPage = $request->per_page ?? 20;
        $query = Payment::where('id', $paymentId)->first()->paymentReceiptFiles();
        $files = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return PaymentReceiptFileResource::collection(
            $files
        );
    }

    public function store(Request $request, $paymentId)
    {
        try {
            $this->validate($request, [
                'file' => 'required'
            ]);
            $path = $request->file('file')->store('files');
            $paymentReceiptFile = PaymentReceiptFile::create([
                'payment_id' => $paymentId,
                'path' => $path,
                'name' => $request->file('file')->getClientOriginalName(),
                'hash_name' => $request->file('file')->hashName(),
                'student_id' => $request->student_id
            ]);
            return (new PaymentReceiptFileResource($paymentReceiptFile))
            ->response()
            ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function update(Request $request, $paymentId,  $fileId)
    {
        $this->validate($request, [
            'notes' => 'required',
        ]);

        $data = $request->all();

        $paymentReceiptFile = PaymentReceiptFile::find($fileId);
        
        $success = $paymentReceiptFile->update($data);
    
        if ($success) {
            return (new PaymentReceiptFileResource($paymentReceiptFile))
                ->response()
                ->setStatusCode(200);
        }
        //return response()->json([], 400); // Note! add error here
    }

    public function show($paymentId, $fileId)
    {
        $paymentReceiptFile = PaymentReceiptFile::find($fileId);
        return new PaymentReceiptFileResource($paymentReceiptFile);
    }

    public function preview($paymentId, $fileId)
    {
        try {
            $paymentReceiptFile = PaymentReceiptFile::find($fileId);
            return response()->file(
                storage_path('app/' . $paymentReceiptFile->path)
            );
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function destroy($paymentId, $fileId)
    {  
        $file = PaymentReceiptFile::find($fileId);

        Payment::find($paymentId)
            ->paymentReceiptFiles()
            ->where('id', $fileId)
            ->first()
            ->delete();
        Storage::delete($file->path);
        return response()->json([], 204);
    }
}

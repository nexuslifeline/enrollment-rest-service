<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentReceiptFileStoreRequest;
use App\Http\Requests\PaymentReceiptFileUpdateRequest;
use App\PaymentReceiptFile;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PaymentReceiptFileResource;
use App\Services\PaymentReceiptFileService;

class PaymentReceiptFileController extends Controller
{
    public function index(Request $request, $paymentId)
    {
        $paymentFileReceiptService = new PaymentReceiptFileService();
        $files = $paymentFileReceiptService->index($request, $paymentId);
        return PaymentReceiptFileResource::collection(
            $files
        );
    }

    public function store(PaymentReceiptFileStoreRequest $request, $paymentId)
    {
        try {
            $file = $request->file('file');
            $studentId = $request->student_id;
            $paymentFileReceiptService = new PaymentReceiptFileService();
            $paymentReceiptFile = $paymentFileReceiptService->store($paymentId, $studentId, $file);
            return (new PaymentReceiptFileResource($paymentReceiptFile))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function update(PaymentReceiptFileUpdateRequest $request, $paymentId,  $fileId)
    {
        try {
            $data = $request->all();
            $paymentReceiptFileService = new PaymentReceiptFileService();
            $paymentReceiptFile = $paymentReceiptFileService->update($data, $fileId);
            return (new PaymentReceiptFileResource($paymentReceiptFile))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function show($paymentId, $fileId)
    {
        $paymentReceiptFile = PaymentReceiptFile::find($fileId);
        return new PaymentReceiptFileResource($paymentReceiptFile);
    }

    public function preview($paymentId, $fileId)
    {
        try {
            $paymentReceiptFileService = new PaymentReceiptFileService();
            return $paymentReceiptFileService->preview($fileId);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function destroy($paymentId, $fileId)
    {  
        try {
            $paymentReceiptFileService = new PaymentReceiptFileService();
            if ($paymentReceiptFileService->delete($fileId)) {
                return response()->json([], 204);
            }
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentFileStoreRequest;
use App\Http\Requests\PaymentFileUpdateRequest;
use App\Payment;
use App\PaymentFile;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentFileResource;
use App\Services\PaymentFileService;
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
        $paymentFileService = new PaymentFileService();
        $files = $paymentFileService->index($request, $paymentId);
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
    public function store(PaymentFileStoreRequest $request, $paymentId)
    {
        try {
            $file = $request->file('file');
            $paymentFileService = new PaymentFileService();
            $paymentFile = $paymentFileService->store($paymentId, $file);
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
            $paymentFileService = new PaymentFileService();
            return $paymentFileService->preview($fileId);
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
    public function update(PaymentFileUpdateRequest $request, $paymentId,  $fileId)
    {
        try {
            $data = $request->all();
            $paymentFileService = new PaymentFileService();
            $paymentFile = $paymentFileService->update($data, $fileId);
            return (new PaymentFileResource($paymentFile))
                ->response()
                ->setStatusCode(201);
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($paymentId, $fileId)
    {
        try {
            $paymentFileService = new PaymentFileService();
            if ($paymentFileService->delete($fileId)) {
                return response()->json([], 204);
            }
        } catch (Throwable $e) {
            Log::error('Message occured => ' . $e->getMessage());
            return response()->json([], 400);
        }
    }

    public function storeMultiple($paymentId, Request $request) {

        $paymentFileService = new PaymentFileService();
        $files = $request->file ?? [];
        $paymentFiles = $paymentFileService->storeMultiple($paymentId, $files);
        return PaymentFileResource::collection(
            $paymentFiles
        );
    }
}

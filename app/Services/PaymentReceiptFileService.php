<?php

namespace App\Services;

use App\Payment;
use Image;
use App\PaymentReceiptFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PaymentReceiptFileService
{
    public function index(object $request, $paymentId)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $query = Payment::where('id', $paymentId)->first()->paymentReceiptFiles();
            $files = !$request->has('paginate') || $request->paginate === 'true'
                ? $query->paginate($perPage)
                : $query->get();

            return $files;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentReceiptFileService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }

    }

    public function store($paymentId, $studentId, $file)
    {
        try {
            if (!$paymentId) {
                throw new \Exception('Payment id not found!');
            }

            if (!$file) {
                throw new \Exception('File not found!');
            }

            $extension = $file->extension();
            $imageExtensions = ['jpg','png','jpeg','gif','svg','bmp', 'jfif', 'tiff', 'tif'];

            //if there's a better condition to check if the file is an image or not
            //and the resize value
            if (in_array($extension, $imageExtensions )) {

                // $width = Image::make($file)->width();
                $image = Image::make($file);

                $image->resize(null, 600, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $path = 'files/payment-receipt/' . $file->hashName();
                Storage::put($path, $image->stream());
            }
            else {
                $path = $file->store('files/payment-receipt');
            }

            $paymentReceiptFile = PaymentReceiptFile::create(
                [
                    'payment_id' => $paymentId,
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'hash_name' => $file->hashName(),
                    'student_id' => $studentId
                ]
            );

            return $paymentReceiptFile;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentReceiptFileService store method call: ');
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

            $query = PaymentReceiptFile::where('id', $fileId);
            $file = $query->first();
            if ($file) {
                Storage::delete($file->path);
                $query->delete();
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentReceiptFileService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function preview($fileId) {
        try {

            if (!$fileId) {
                throw new \Exception('File id not found!');
            }

            $query = PaymentReceiptFile::where('id', $fileId);
            $paymentReceiptFile = $query->first();

            if ($paymentReceiptFile) {
                return  response()->file(
                    storage_path('app/' . $paymentReceiptFile->path)
                );
            }
            return null;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentReceiptFileService preview method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update($data, $fileId) {

        try {

            if (!$fileId) {
                throw new \Exception('File id not found!');
            }

            $query = PaymentReceiptFile::where('id', $fileId);
            $paymentReceiptFile = $query->first();
            $paymentReceiptFile->update($data);

            return  $paymentReceiptFile;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentReceiptFileService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
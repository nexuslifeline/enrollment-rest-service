<?php

namespace App\Services;

use App\Payment;
use Image;
use App\PaymentFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PaymentFileService
{
    public function index(object $request, $paymentId)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $query = Payment::where('id', $paymentId)->first()->files();
            $files = !$request->has('paginate') || $request->paginate === 'true'
                ? $query->paginate($perPage)
                : $query->get();

            return $files;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentFileService index method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function store($paymentId, $file)
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

                $path = 'files/payment/' . $file->hashName();
                Storage::put($path, $image->stream());
            }
            else {
                $path = $file->store('files/payment');
            }

            $paymentFile = PaymentFile::create(
                [
                    'payment_id' => $paymentId,
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'hash_name' => $file->hashName()
                ]
            );

            return $paymentFile;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentFileService store method call: ');
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

            $query = PaymentFile::where('id', $fileId);
            $file = $query->first();
            if ($file) {
                Storage::delete($file->path);
                $query->delete();
                return true;
            }
            return false;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentFileService delete method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function preview($fileId) {
        try {
            if (!$fileId) {
                throw new \Exception('File id not found!');
            }

            $query = PaymentFile::where('id', $fileId);
            $paymentFile = $query->first();

            if ($paymentFile) {
                return  response()->file(
                    storage_path('app/' . $paymentFile->path)
                );
            }
            return null;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentFileService preview method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }

    public function update($data, $fileId) {
        try {
            if (!$fileId) {
                throw new \Exception('File id not found!');
            }

            $query = PaymentFile::where('id', $fileId);
            $paymentFile = $query->first();

            $paymentFile->update($data);

            return  $paymentFile;
        } catch (Exception $e) {
            Log::info('Error occured during PaymentFileService update method call: ');
            Log::info($e->getMessage());
            throw $e;
        }
    }
}
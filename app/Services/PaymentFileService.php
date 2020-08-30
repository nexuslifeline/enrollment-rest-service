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
        $perPage = $request->per_page ?? 20;
        $query = Payment::where('id', $paymentId)->first()->files();
        $files = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();

        return $files;
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
            $imageExtensions = ['jpg','png','jpeg','gif','svg','bmp'];

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
        } catch (Throwable $e) {
            ValidationException::withMessages([
                'file' => $e->getMessage()
            ]);
        }
    }

    public function delete($fileId)
    {
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
    }

    public function preview($fileId) {

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
    }

    public function update($data, $fileId) {

        if (!$fileId) {
            throw new \Exception('File id not found!');
        }

        $query = PaymentFile::where('id', $fileId);
        $paymentFile = $query->first();

        $paymentFile->update($data);

        return  $paymentFile;
    }
}
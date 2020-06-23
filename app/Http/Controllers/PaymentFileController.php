<?php

namespace App\Http\Controllers;

use App\Payment;
use App\PaymentFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PaymentFileResource;

class PaymentFileController extends Controller
{
    
    public function index(Request $request, $paymentFile)
    {
        $perPage = $request->per_page ?? 20;
        $query = Payment::where('id', $paymentFile)->first()->files();
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

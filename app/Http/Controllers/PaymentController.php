<?php

namespace App\Http\Controllers;

use App\Payment;
use App\PaymentFile;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_age ?? 20;
        $query = Payment::with(['paymentFiles']);
        $payment = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();
        return PaymentResource::collection(
            $payment
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'payment_mode_id' => 'required',
            'notes' => 'required_if:payment_mode_id,==,3'
        ], 
        [
            'notes.required_if' => 'Notes is required when payment mode is OTHERS.'
        ], 
        [
            'payment_mode_id' => 'payment mode'
        ]);

        $data = $request->all();

        $payment = Payment::create($data);

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $path = $file->store('files');
                $paymentFile = PaymentFile::create([
                    'payment_id' => $payment->id,
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'hash_name' => $file->hashName()
                ]);
            }
        }
       
        return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        // return $request->notes;
        $this->validate($request, [
            'amount' => 'required|numeric|min:0|not_in:0',
            'reference_no' => 'required|max:191',
            'date_paid' => 'required',
            'payment_mode_id' => 'required',
            'notes' => 'required_if:payment_mode_id,==,3'
        ], 
        [
            'notes.required_if' => 'Notes is required when payment mode is OTHERS.'
        ], 
        [
            'payment_mode_id' => 'payment mode'
        ]);

        $data = $request->all();

        $success = $payment->update($data);

        // $files = $request->file('files');
        // foreach ($files as $file) {
        //     $path = $file->store('files');
        //     $paymentFile = PaymentFile::create([
        //         'payment_id' => $payment->id,
        //         'path' => $path,
        //         'name' => $file->getClientOriginalName(),
        //         'hash_name' => $file->hashName()
        //     ]);
        // }

        if($success){
            return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(200);
        }
        return response()->json([], 400); // Note! add error here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}

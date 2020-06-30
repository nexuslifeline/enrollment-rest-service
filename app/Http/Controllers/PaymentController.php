<?php

namespace App\Http\Controllers;

use App\Payment;
use App\PaymentFile;
use App\Student;
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
        $perPage = $request->per_page ?? 20;

        $query = Payment::with(['paymentMode', 'student' => function($query) {
                        $query->with(['address', 'photo']);
                    }])
                    ->where('payment_status_id', '!=', 1);
        //filter
        //payment status
        $paymentStatusId = $request->payment_status_id ?? false;
        $query->when($paymentStatusId, function($q) use ($paymentStatusId) {
            return $q->where('payment_status_id', $paymentStatusId);
        });

        //criteria
        $criteria = $request->criteria ?? false;
        $query->when($criteria, function($q) use ($criteria) {
            return $q->where(function($q) use ($criteria) {
                return $q->where('date_paid', 'like', '%'.$criteria.'%')
                    ->orWhere('amount', 'like', '%'.$criteria.'%')
                    ->orWhere('reference_no', 'like', '%'.$criteria.'%')
                    ->orWhereHas('student', function($query) use ($criteria) {
                        return $query->where(function($q) use ($criteria) {
                            return $q->where('name', 'like', '%'.$criteria.'%')
                                ->orWhere('first_name', 'like', '%'.$criteria.'%')
                                ->orWhere('middle_name', 'like', '%'.$criteria.'%')
                                ->orWhere('last_name', 'like', '%'.$criteria.'%');
                        });
                    });
            });
        });

        $payments = !$request->has('paginate') || $request->paginate === 'true'
            ? $query->paginate($perPage)
            : $query->get();

        
        return PaymentResource::collection(
            $payments
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
            'amount' => 'sometimes|required|numeric|min:0|not_in:0',
            'reference_no' => 'sometimes|required|max:191',
            'date_paid' => 'sometimes|required',
            'payment_mode_id' => 'sometimes|required',
            'notes' => 'sometimes|required_if:payment_mode_id,==,3'
        ], 
        [
            'notes.required_if' => 'Notes is required when payment mode is OTHERS.'
        ], 
        [
            'payment_mode_id' => 'payment mode'
        ]);

        $data = $request->all();

        if ($request->has('payment_status_id')) {
            if ($request->payment_status_id === 2) {
              $student = $payment->student()->first();
              $transcript = $student->transcripts()->get()->last();
              //check if student is new or old
              if ($transcript['student_category_id'] === 1) {
                $students = Student::with(['transcripts'])
                    ->whereHas('transcripts', function ($query) {
                        return $query->where('student_category_id',1)
                            ->where('transcript_status_id', 3);
                    })
                    ->get();
                
                $student->update([
                  'student_no' => '11'. str_pad(count($students) + 1, 8, '0', STR_PAD_LEFT)
                ]);
              }
            }
        }

        $success = $payment->update($data);

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

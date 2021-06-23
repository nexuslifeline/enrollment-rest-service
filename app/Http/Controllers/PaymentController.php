<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Http\Requests\SubmitPaymentRequest;
use App\Payment;
use App\PaymentFile;
use App\Student;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
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
        $paymentService = new PaymentService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $payments = $paymentService->list($isPaginated, $perPage, $filters);

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
    public function store(PaymentStoreRequest $request)
    {
        $paymentService = new PaymentService();
        $data = $request->all();
        $payment = $paymentService->store($data);

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
    public function show(int $id)
    {
        $paymentService = new PaymentService();
        $payment = $paymentService->get($id);
        return new PaymentResource($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentUpdateRequest $request, int $id)
    {
        $data = $request->all();
        $paymentService = new PaymentService();
        $payment = $paymentService->update($data, $id);

        return (new PaymentResource($payment))
        ->response()
        ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $paymentService = new PaymentService();
        $paymentService->delete($id);
        return response()->json([], 204);
    }

    public function submitPayment(SubmitPaymentRequest $request, int $id)
    {
        $paymentService = new PaymentService();
        $data = $request->all();
        $payment = $paymentService->submitPayment($data, $id);

        return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(201);
    }

    public function approve(Request $request, int $id)
    {
        $paymentService = new PaymentService();
        $data = $request->all();
        $payment = $paymentService->approve($data, $id);

        return (new PaymentResource($payment))
            ->response()
            ->setStatusCode(201);
    }
}

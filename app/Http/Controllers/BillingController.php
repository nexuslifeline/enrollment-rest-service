<?php

namespace App\Http\Controllers;

use App\Billing;
use App\Http\Requests\BillingPostPaymentRequest;
use Illuminate\Http\Request;
use App\Services\BillingService;
use App\Http\Resources\BillingResource;
use App\Http\Requests\BillingStoreRequest;
use App\Http\Resources\BillingItemResource;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $billingService = new BillingService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $filters = $request->except('per_page', 'paginate');
        $billings = $billingService->list($isPaginated, $perPage, $filters);
        return BillingResource::collection(
            $billings
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillingStoreRequest $request)
    {
        $billingService = new BillingService();
        $data = $request->except('billing_items');
        $billingItems = $request->billing_items ?? [];
        $billing = $billingService->store($data, $billingItems);

        return (new BillingResource($billing))
            ->response()
            ->setStatusCode(201);
    }

    public function storeBatchSoa(BillingStoreRequest $request)
    {
        $billingService = new BillingService();
        $data = $request->except('billing_items');
        $billingItems = $request->billing_items;
        $billings = $billingService->storeBatchSoa($data, $billingItems);
        return BillingResource::collection($billings);
    }

    public function storeBatchOtherBilling(BillingStoreRequest $request)
    {
        $billingService = new BillingService();
        $data = $request->except('billing_items');
        $billingItems = $request->billing_items ?? [];
        $billings = $billingService->storeBatchOtherBilling($data, $billingItems);
        return BillingResource::collection($billings);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $billingService = new BillingService();
        $billing = $billingService->get($id);
        return new BillingResource($billing);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function edit(Billing $billing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $billingService = new BillingService();
        $data = $request->except('billing_items');
        $billingItems = $request->billing_items ?? [];
        $billing = $billingService->update($id, $data, $billingItems);
        return (new BillingResource($billing))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $billingService = new BillingService();
        $billingService->delete($id);
        return response()->json([], 204);
    }

    public function getBillingItemsOfBilling(int $id)
    {
        $billingService = new BillingService();
        $schoolFee = $billingService->getBillingItemsOfBilling($id);
        return BillingItemResource::collection($schoolFee);
    }

    public function postPayment(BillingPostPaymentRequest $request, int $id)
    {
        $billingService = new BillingService();
        $data = $request->all();
        $billing = $billingService->postPayment($data, $id);

        return (new BillingResource($billing))
            ->response()
            ->setStatusCode(201);
    }

    public function updateInitialBilling(Request $request, int $academicRecordId, int $id)
    {
        $billingService = new BillingService();
        $data = $request->all();
        $billing = $billingService->updateInitialBilling($data, $academicRecordId, $id);
        return (new BillingResource($billing))
            ->response()
            ->setStatusCode(200);
    }

    public function cancelPayments(int $id)
    {
        $billingService = new BillingService();
        $billing = $billingService->cancelPayments($id);

        return (new BillingResource($billing))
            ->response()
            ->setStatusCode(201);
    }
}

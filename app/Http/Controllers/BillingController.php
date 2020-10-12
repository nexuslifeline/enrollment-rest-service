<?php

namespace App\Http\Controllers;

use App\Billing;
use App\Http\Requests\BillingStoreRequest;
use Illuminate\Http\Request;
use App\Http\Resources\BillingResource;
use App\Services\BillingService;

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
        $data = $request->all();
        $schoolFee = $billingService->storeBatchSoa($data);
        return BillingResource::collection($schoolFee);
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
    public function update(Request $request, Billing $billing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billing  $billing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        //
    }
}

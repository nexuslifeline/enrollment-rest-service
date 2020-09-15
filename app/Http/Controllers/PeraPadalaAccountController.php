<?php

namespace App\Http\Controllers;

use App\PeraPadalaAccount;
use Illuminate\Http\Request;
use App\Services\PeraPadalaAccountService;
use App\Http\Resources\PeraPadalaAccountResource;
use App\Http\Requests\PeraPadalaAccountStoreRequest;
use App\Http\Requests\PeraPadalaAccountUpdateRequest;

class PeraPadalaAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $peraPadalaAccountService = new PeraPadalaAccountService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $peraPadalaAccounts = $peraPadalaAccountService->list($isPaginated, $perPage);
        return PeraPadalaAccountResource::collection(
            $peraPadalaAccounts
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PeraPadalaAccountStoreRequest $request)
    {
        $peraPadalaAccountService = new PeraPadalaAccountService();
        $peraPadalaAccount = $peraPadalaAccountService->store($request->all());
        return (new PeraPadalaAccountResource($peraPadalaAccount))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PeraPadalaAccount  $peraPadalaAccount
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $peraPadalaAccountService = new PeraPadalaAccountService();
        $peraPadalaAccount = $peraPadalaAccountService->get($id);
        return new PeraPadalaAccountResource($peraPadalaAccount);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PeraPadalaAccount  $peraPadalaAccount
     * @return \Illuminate\Http\Response
     */
    public function update(PeraPadalaAccountUpdateRequest $request, int $id)
    {
        $peraPadalaAccountService = new PeraPadalaAccountService();
        $peraPadalaAccount = $peraPadalaAccountService->update($request->all(), $id);

        return (new PeraPadalaAccountResource($peraPadalaAccount))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PeraPadalaAccount  $peraPadalaAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $peraPadalaAccountService = new PeraPadalaAccountService();
        $peraPadalaAccountService->delete($id);
        return response()->json([], 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\EWalletAccount;
use Illuminate\Http\Request;
use App\Services\EWalletAccountService;
use App\Http\Resources\EWalletAccountResource;
use App\Http\Requests\EWalletAccountStoreRequest;
use App\Http\Requests\EWalletAccountUpdateRequest;

class EWalletAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $eWalletAccountService = new EWalletAccountService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $eWalletAccounts = $eWalletAccountService->list($isPaginated, $perPage);
        return EWalletAccountResource::collection(
            $eWalletAccounts
        );
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EWalletAccountStoreRequest $request)
    {
        $eWalletAccountService= new EWalletAccountService();
        $eWalletAccount = $eWalletAccountService->store($request->all());
        return (new EWalletAccountResource($eWalletAccount))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EWalletAccount  $eWalletAccount
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $eWalletAccountService= new EWalletAccountService();
        $eWalletAccount = $eWalletAccountService->get($id);
        return new EWalletAccountResource($eWalletAccount);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EWalletAccount  $eWalletAccount
     * @return \Illuminate\Http\Response
     */
    public function update(EWalletAccountUpdateRequest $request, int $id)
    {
        $eWalletAccountService= new EWalletAccountService();
        $eWalletAccount = $eWalletAccountService->update($request->all(), $id);

        return (new EWalletAccountResource($eWalletAccount))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EWalletAccount  $eWalletAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $eWalletAccountService= new EWalletAccountService();
        $eWalletAccountService->delete($id);
        return response()->json([], 204);
    }
}

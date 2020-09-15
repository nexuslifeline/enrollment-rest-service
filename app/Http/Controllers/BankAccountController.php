<?php

namespace App\Http\Controllers;

use App\BankAccount;
use Illuminate\Http\Request;
use App\Services\BankAccountService;
use App\Http\Resources\BankAccountResource;
use App\Http\Requests\BankAccountStoreRequest;
use App\Http\Requests\BankAccountUpdateRequest;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bankAccountService = new BankAccountService();
        $perPage = $request->per_page ?? 20;
        $isPaginated = !$request->has('paginate') || $request->paginate === 'true';
        $bankAccounts = $bankAccountService->list($isPaginated, $perPage);
        return BankAccountResource::collection(
            $bankAccounts
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankAccountStoreRequest $request)
    {
        $bankAccountService = new BankAccountService();
        $bankAccount = $bankAccountService->store($request->all());
        return (new BankAccountResource($bankAccount))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $bankAccountService = new BankAccountService();
        $bankAccount = $bankAccountService->get($id);
        return new BankAccountResource($bankAccount);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(BankAccountUpdateRequest $request, int $id)
    {
        $bankAccountService = new BankAccountService();
        $bankAccount = $bankAccountService->update($request->all(), $id);

        return (new BankAccountResource($bankAccount))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankAccount  $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $bankAccountService = new BankAccountService();
        $bankAccountService->delete($id);
        return response()->json([], 204);
    }
}

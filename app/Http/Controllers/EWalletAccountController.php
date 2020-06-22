<?php

namespace App\Http\Controllers;

use App\EWalletAccount;
use Illuminate\Http\Request;
use App\Http\Resources\EWalletAccountResource;

class EWalletAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 20;
        $eWalletAccounts = !$request->has('paginate') || $request->paginate === 'true'
            ? EWalletAccount::paginate($perPage)
            : EWalletAccount::all();
        return EWalletAccountResource::collection(
            $eWalletAccounts
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EWalletAccount  $eWalletAccount
     * @return \Illuminate\Http\Response
     */
    public function show(EWalletAccount $eWalletAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EWalletAccount  $eWalletAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(EWalletAccount $eWalletAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EWalletAccount  $eWalletAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EWalletAccount $eWalletAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EWalletAccount  $eWalletAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(EWalletAccount $eWalletAccount)
    {
        //
    }
}

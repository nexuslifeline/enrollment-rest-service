<?php

namespace App\Http\Controllers;

use App\PeraPadalaAccount;
use App\Http\Resources\PeraPadalaAccountResource;
use App\Services\PeraPadalaAccountService;
use Illuminate\Http\Request;

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
     * @param  \App\PeraPadalaAccount  $peraPadalaAccount
     * @return \Illuminate\Http\Response
     */
    public function show(PeraPadalaAccount $peraPadalaAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PeraPadalaAccount  $peraPadalaAccount
     * @return \Illuminate\Http\Response
     */
    public function edit(PeraPadalaAccount $peraPadalaAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PeraPadalaAccount  $peraPadalaAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PeraPadalaAccount $peraPadalaAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PeraPadalaAccount  $peraPadalaAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(PeraPadalaAccount $peraPadalaAccount)
    {
        //
    }
}

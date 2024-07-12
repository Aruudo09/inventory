<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockTrackRequest;
use App\Http\Requests\UpdateStockTrackRequest;
use App\Models\StockTrack;

class StockTrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreStockTrackRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStockTrackRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockTrack  $stockTrack
     * @return \Illuminate\Http\Response
     */
    public function show(StockTrack $stockTrack)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockTrack  $stockTrack
     * @return \Illuminate\Http\Response
     */
    public function edit(StockTrack $stockTrack)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStockTrackRequest  $request
     * @param  \App\Models\StockTrack  $stockTrack
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStockTrackRequest $request, StockTrack $stockTrack)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockTrack  $stockTrack
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockTrack $stockTrack)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pemakaian;
use App\Models\BeritaAcara;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/dashboard', [
            'items' => Item::with('category', 'berita_acara', 'pemakaian')->get()
        ]);
    }

    public function chart() {
        
        $j = 1;
        for ($i=0; $i < 12; $i++) { 
            $itemBa[] = BeritaAcara::with('item')->whereMonth('created_at', $j)->get();
            $itemUse[] = Pemakaian::with('item')->whereMonth('created_at', $j)->get();
            $countBa[] = count($itemBa[$i]);
            $countUse[] = count($itemUse[$i]);
            for ($k=0; $k < $countBa[$i]; $k++) { 
                $ba[] = $itemBa[$i][$k]->item->sum('pivot.qtyBa');
            }
            for ($l=0; $l < $countUse[$i]; $l++) { 
                $use[] = $itemUse[$i][$l]->item->sum('pivot.qtyUse');
            }
            $sumBa[] = array_sum($ba);
            $sumUse[] = array_sum($use);
            $filterBa = array_filter($sumBa);
            $filterUse = array_filter($sumUse);
            $ba = [];
            $use = [];
            $j++;
        }

        $total = [$filterBa, $filterUse];

        return response()->json($total);
    }

    public function report() {
        return view('reports.report');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

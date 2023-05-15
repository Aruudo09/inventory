<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Supplier;
use App\Models\BeritaAcara;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBeritaAcaraRequest;
use App\Http\Requests\UpdateBeritaAcaraRequest;

class BeritaAcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('beritaAcara.beritaAcaraIndex', [
            'berita_acaras' => BeritaAcara::with(['user', 'supplier', 'purchase_order'])
                               ->filter(request(['search']))->paginate(7)
        ]);
    }

    public function setDetail($ba_id) {
        $input = beritaAcara::where('id', $ba_id)->first();

        $ba_item = $input->item;
     
        return response()->json($ba_item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('beritaAcara.beritaAcaraCreate', [
            'berita_acaras' => BeritaAcara::all(),
            'purchase_orders' => PurchaseOrder::select('id', 'poCode', 'sp_id')->get()
        ]);
    }

    public function setText($po_id) {
        $input = PurchaseOrder::where('id', $po_id)->first();

        $sp_name = $input->supplier->spName;
        $sp_id = $input->sp_id;
        $item_name = $input->item;
        $output = [
            $sp_name,
            $item_name,
            $sp_id
        ];
        
     
        return response()->json($output);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBeritaAcaraRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $count = BeritaAcara::count();
        $number = $count + 1;
        $prefix = $request->divSelect . '-' . sprintf('%04d', $number) . '/' . date('y');
        
        $request['baCode'] = $prefix;
        $request['user_id'] = Auth::id();

        $rules = [
            'baCode' => 'required',
            'po_id' => 'required',
            'user_id' => 'required',
            'sp_id' => 'required',
            'description' => 'required'
        ];
        
        $items = [];

        for ($i=0; $i < count($request->item_id); $i++) { 
            if ($request->qtyBa[$i] != '') {
                $items[$request->item_id[$i]] = ['qtyBa' => $request->qtyBa[$i], 'satuan' => $request->satuan[$i]]; 
            }
        }

        // // DD(count($request->item_id));
        // DD($items);
        // // DD($request->item_id[0]);

        $validated = $request->validate($rules);

        if (BeritaAcara::create($validated)) {
            $ba_id = BeritaAcara::select('id')->where('baCode', $prefix)->get();

            BeritaAcara::find($ba_id[0]['id'])->item()->sync($items);
            
            $request->session()->flash('success', 'Berita Acara baru berhasil ditambah!');
            return redirect('beritaAcara');
        } else {
            $request->session()->flash('danger', 'Berita Acara baru gagal ditambah!');
            return redirect('beritaAcara');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BeritaAcara  $beritaAcara
     * @return \Illuminate\Http\Response
     */
    public function show(BeritaAcara $beritaAcara)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BeritaAcara  $beritaAcara
     * @return \Illuminate\Http\Response
     */
    public function edit(BeritaAcara $beritaAcara)
    {
        return view('beritaAcara.beritaAcaraEdit', [
            'berita_acara' => $beritaAcara
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBeritaAcaraRequest  $request
     * @param  \App\Models\BeritaAcara  $beritaAcara
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BeritaAcara $beritaAcara)
    {
        $limit = count($request->item_id);
        $sync = [];

        if (isset($request->divSelect)) {
            $baCode = BeritaAcara::select('baCode')->where('id', $beritaAcara->id)->first();
            $fix = $request->divSelect;
            $prefix = substr_replace($baCode->baCode, $fix, 0, 2);
            // DD($prefix);
            $request['baCode'] = $prefix;
            $rule['baCode'] = 'required';
        }

        $rule['description'] = 'required';

        for ($i=0; $i < $limit; $i++) { 
            $sync[$request->item_id[$i]] = ['qtyBa' => $request->qtyBa[$i], 'satuan' => $request->satuan[$i]];
        }

        $validated = $request->validate($rule);

        if (BeritaAcara::where('id', $beritaAcara->id)
                    ->update($validated)) {
            
                BeritaAcara::find($beritaAcara->id)->item()->sync($sync);
            
            return redirect('beritaAcara')->with('success', 'Berita Acara berhasil diubah!');
        } else {
            return redirect('beritaAcara')->with('danger', 'Berita Acara gagal diubah!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BeritaAcara  $beritaAcara
     * @return \Illuminate\Http\Response
     */
    public function destroy(BeritaAcara $beritaAcara)
    {
        if (BeritaAcara::destroy($beritaAcara->id)) {
            return redirect('beritaAcara')->with('success', 'Berita Acara berhasil dihapus');
        } else {
            return redirect('beritaAcara')->with('danger', 'Berita Acara gagal dihapus');
        }
    }
}

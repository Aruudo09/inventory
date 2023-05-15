<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Pemakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePemakaianRequest;
use App\Http\Requests\UpdatePemakaianRequest;

class PemakaianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('masterBarang.pemakaian.pemakaianIndex', [
            'pemakaians' => Pemakaian::all()
        ]);
    }

    public function setDetail($use_id) {
        $input = Pemakaian::where('id', $use_id)->first();

        $use_item = $input->item;
     
        return response()->json($use_item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masterBarang.pemakaian.pemakaianCreate', [
            'items' => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePemakaianRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $limit = count($request->item_id);
        $count = Pemakaian::all()->count();
        $number = $count + 1;
        $prefix = sprintf('%04d', $number);
        $request['noUsed'] = $prefix;
        $request['user_id'] = Auth::id();
        
        $rules = [
            'noUsed' => 'required',
            'user_id' => 'required',
            'description' => 'required'
        ];

        $validated = $request->validate($rules);
        

        if (Pemakaian::create($validated)) {
            $use_id = Pemakaian::select('id')->where('noUsed', $prefix)->get();
            // DD($use_id);

            for ($i=0; $i < $limit; $i++) { 
                Pemakaian::find($use_id[0]['id'])->item()->attach($request->item_id[$i], ['qtyUse' => $request->qtyUse[$i]]);
            }
            
            $request->session()->flash('success', 'Pemakaian baru berhasil ditambah!');
            return redirect('pemakaian/create');
        } else {
            $request->session()->flash('danger', 'Pemakaian baru gagal ditambah!');
            return redirect('pemakaian/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pemakaian  $pemakaian
     * @return \Illuminate\Http\Response
     */
    public function show(Pemakaian $pemakaian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pemakaian  $pemakaian
     * @return \Illuminate\Http\Response
     */
    public function edit(Pemakaian $use_id)
    {
        return view('masterBarang.pemakaian.pemakaianEdit', [
            'pemakaians' => $use_id,
            'items' => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePemakaianRequest  $request
     * @param  \App\Models\Pemakaian  $pemakaian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pemakaian $pemakaian)
    {
        $limit = count($request->item_id);
        $sync = [];

        for ($i=0; $i < $limit; $i++) { 
            $sync[$request->item_id[$i]] = ['qtyUse' => $request->qtyUse[$i]];
        }

        $validated = $request->validate([
            'description' => 'required'
        ]);


        if (Pemakaian::where('id', $pemakaian->id)
                    ->update($validated)) {
            
                Pemakaian::find($pemakaian->id)->item()->sync($sync);
            
            return redirect('/pemakaian')->with('success', 'Pemakaian berhasil diubah!');
        } else {
            return redirect('/pemakaian')->with('danger', 'Pemakaian gagal diubah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pemakaian  $pemakaian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pemakaian $use_id)
    {
        if (Pemakaian::destroy($use_id->id)) {
            return redirect('pemakaian')->with('success', 'Pemakaian berhasil dihapus');
        } else {
            return redirect('pemakaian')->with('danger', 'Pemakaian gagal dihapus');
        }
    }
}

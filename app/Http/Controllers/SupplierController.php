<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('supplier.supplierIndex', [
            "suppliers" => Supplier::latest()->filter(request(['search']))->paginate(7)
        ]);
    }
    
    public function setText($sp_id) {
        $input = Supplier::where('id', $sp_id)->first();

        $sp_item = $input->item;
     
        return response()->json($sp_item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('supplier.supplierCreate', [
            "items" => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSupplierRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $count = Supplier::count();
        $number = $count + 1;
        $prefix = 'SP' . sprintf('%04d', $number);
        $limit = count($request->item_id);
        
        $request['spCode'] = $prefix;

        $rules = [
            'spCode' => 'required',
            'spName' => 'required',
            'cpName' => 'required',
            'cpNumber' => 'required',
            'faxNumber' => 'required',
            'address' => 'required'
        ];

        $validated = $request->validate($rules);

        if (Supplier::create($validated)) {
            $sp_id = Supplier::select('id')->where('spCode', $prefix)->get();

            for ($i=0; $i < $limit; $i++) { 
                $attach[$request->item_id[$i]] = ['harga' => $request->harga[$i]];
            }
            
            Supplier::find($sp_id[0]['id'])->item()->attach($attach);

            $request->session()->flash('success', 'Supplier baru berhasil ditambah!');
            return redirect('supplier/create');
        } else {
            $request->session()->flash('danger', 'Supplier baru gagal ditambah!');
            return redirect('supplier/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.supplierEdit', [
            'suppliers' => $supplier,
            'items' => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupplierRequest  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $sync = [];
        $rule = [
            'spName' => 'required',
            'spName' => 'required',
            'cpName' => 'required',
            'cpNumber' => 'required',
            'faxNumber' => 'required',
            'address' => 'required'
        ];

        if ($request->item_id != null) {
            $limit = count($request->item_id);

            for ($i=0; $i < $limit; $i++) { 
                $sync[$request->item_id[$i]] = ['harga' => $request->harga[$i]];
            }
        }
        


        $validated = $request->validate($rule);

        if (Supplier::where('id', $supplier->id)
                    ->update($validated)) {
            
                Supplier::find($supplier->id)->item()->sync($sync);
            
            return redirect('supplier')->with('success', 'supplier request berhasil diubah!');
        } else {
            return redirect('supplier')->with('danger', 'supplier request gagal diubah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        if (Supplier::destroy($supplier->id)) {
            return redirect('supplier')->with('success', 'supplier berhasil dihapus');
        } else {
            return redirect('supplier')->with('danger', 'supplier gagal dihapus');
        }
    }
}

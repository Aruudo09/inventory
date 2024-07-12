<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Item;
use App\Models\Division;
use App\Models\Supplier;
use App\Models\StockTrack;
use App\Models\BeritaAcara;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Exports\beritaAcaraExport;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBeritaAcaraRequest;
use App\Http\Requests\UpdateBeritaAcaraRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

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
            'purchase_orders' => PurchaseOrder::where('status', '!=', 1)->select('id', 'poCode', 'sp_id')->get()
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
        $purchase_order = PurchaseOrder::where('id', $request->po_id)->first();
        $limitPo = count($purchase_order->item);

        for ($i=0; $i < count($request->item_id); $i++) { 
            if ($request->created_at != null) {
                // UNTUK NAMBAH ITEM DI BERITA ACARA
                $items[$request->item_id[$i]] = ['qtyBa' => $request->qtyBa[$i], 'satuan' => $request->satuan[$i], 'created_at' => $request->created_at]; 
                // UNTUK NAMBAH QTYBA DI ITEM PO
                $sync[$request->item_id[$i]] = ['qtyBa' => $purchase_order->item[$i]->pivot->qtyBa + $request->qtyBa[$i], 'created_at' => $request->created_at];
            } else {
                // UNTUK NAMBAH ITEM DI BERITA ACARA
                $items[$request->item_id[$i]] = ['qtyBa' => $request->qtyBa[$i], 'satuan' => $request->satuan[$i]]; 
                // UNTUK NAMBAH QTYBA DI ITEM PO
                $sync[$request->item_id[$i]] = ['qtyBa' => $purchase_order->item[$i]->pivot->qtyBa + $request->qtyBa[$i]];
            }
            
                // UNTUK NAMBAH QUANTITY BARANG DI ITEM
                $item[$i] = Item::where('id', $request->item_id[$i])->first();
                // UNTUK CHECK QUANTITY DI PO
                $qtyCheck[] = $purchase_order->item[$i]->pivot->qtyPo - $purchase_order->item[$i]->pivot->qtyBa;

                // MENENTUKAN MAXIMUM PENGINPUTAN QUANTITY BERITA ACARA
                if ($request->qtyBa[$i] > $qtyCheck[$i]) {
                    $request->session()->flash('danger', 'Stok melibihi batas PO!');
                    return redirect('/beritaAcara/create');
                }
        }

        // MEMBUAT KODE BERITA ACARA
        $count = BeritaAcara::count();
        $number = $count + 1;
        $prefix = $request->divSelect . '-' . sprintf('%04d', $number) . '/' . date('y');
        
        $request['baCode'] = $prefix;
        $request['user_id'] = Auth::id();

        if ($request->created_at != null) {
            $rules = [
                'baCode' => 'required',
                'po_id' => 'required',
                'user_id' => 'required',
                'sp_id' => 'required',
                'description' => 'required',
                'created_at' => 'required'
            ];   
        } else {
            $rules = [
                'baCode' => 'required',
                'po_id' => 'required',
                'user_id' => 'required',
                'sp_id' => 'required',
                'description' => 'required'
            ];
        }

        $validated = $request->validate($rules);

        if (BeritaAcara::create($validated)) {
            $ba_id = BeritaAcara::select('id')->where('baCode', $prefix)->get();

            BeritaAcara::find($ba_id[0]['id'])->item()->attach($items);

            // UPDATE QUANTITY BARANG DI ITEM
            for ($i=0; $i < count($request->item_id); $i++) { 
                Item::where('id', $request->item_id[$i])->update(['stock' => $item[$i]->stock + $request->qtyBa[$i]]);

                if ($request->created_at != null) {
                    StockTrack::create(['item_id' => $request->item_id[$i], 'stockTr' => $item[$i]->stock + $request->qtyBa[$i], 'created_at' => $request->created_at]);
                } else {
                    StockTrack::create(['item_id' => $request->item_id[$i], 'stockTr' => $item[$i]->stock + $request->qtyBa[$i]]);    
                }
                
            }

            // UPDATE QUANTITY DI PO ITEM
            if (PurchaseOrder::find($request->po_id)->item()->sync($sync)) {
                for ($i=0; $i < $limitPo; $i++) {
                    $resultBa[] = $purchase_order->item[$i]->pivot->qtyBa + $request->qtyBa[$i];
                    $resultPo[] = $purchase_order->item[$i]->pivot->qtyPo; 
                }
    
                $result = count(array_intersect($resultBa, $resultPo));
    
                if ($result == $limitPo) {
                    PurchaseOrder::where('id', $request->po_id)->update(['status' => 1]);
                    $request->session()->flash('success', 'Berita Acara baru berhasil ditambah!');
                    return redirect('beritaAcara');
                } else {
                    $request->session()->flash('success', 'Berita Acara baru berhasil ditambah!');
                    return redirect('beritaAcara');
                }
            }

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
        // VARIABEL UPDATE BERITA ACARA
        $purchase_order = PurchaseOrder::where('id', $beritaAcara->po_id)->first();
        $berita_acara = BeritaAcara::where('id', $beritaAcara->id)->first();
        $limit = count($request->item_id);
        $limitPo = count($purchase_order->item);
        $sync = [];
        
        for ($i=0; $i < $limit; $i++) { 
            $sync1[$request->item_id[$i]] = ['qtyBa' => $request->qtyBa[$i], 'satuan' => $request->satuan[$i]];
            $sync2[$request->item_id[$i]] = ['qtyBa' => $purchase_order->item[$i]->pivot->qtyBa - $berita_acara->item[$i]->pivot->qtyBa + $request->qtyBa[$i]];
            $item[$i] = Item::where('id', $request->item_id[$i])->first();
        }
        // END VARIABEL UPDATE BERITA ACARA

        // MENGUBAH KODE BERITA ACARA BILA PERLU
        if (isset($request->divSelect)) {
            $baCode = BeritaAcara::select('baCode')->where('id', $beritaAcara->id)->first();
            $fix = $request->divSelect;
            $prefix = substr_replace($baCode->baCode, $fix, 0, 2);
            $request['baCode'] = $prefix;
            $rule['baCode'] = 'required';
        }

        $rule['description'] = 'required';


        $validated = $request->validate($rule);

        if (BeritaAcara::where('id', $beritaAcara->id)
                    ->update($validated)) {
            
                // UPDATE BERITA ACARA ITEM
                BeritaAcara::find($beritaAcara->id)->item()->sync($sync1);
                
                // UPDATE QTYBA DI TABLE PO ITEM
                PurchaseOrder::find($beritaAcara->po_id)->item()->sync($sync2);

                // UPDATE QUANTITY BARANG DI ITEM
                for ($i=0; $i < count($request->item_id); $i++) { 
                    Item::where('id', $request->item_id[$i])->update(['stock' => $item[$i]->stock - $berita_acara->item[$i]->pivot->qtyBa + $request->qtyBa[$i]]);
                    StockTrack::create(['item_id' => $request->item_id[$i], 'stockTr' => $item[$i]->stock - $berita_acara->item[$i]->pivot->qtyBa + $request->qtyBa[$i]]);
                }

                // CHECK COMPATIBILITY QUANTITY BA DI PO ITEM
                for ($i=0; $i < $limitPo; $i++) {
                    $resultBa[] = $purchase_order->item[$i]->pivot->qtyBa - $berita_acara->item[$i]->pivot->qtyBa + $request->qtyBa[$i];
                    $resultPo[] = $purchase_order->item[$i]->pivot->qtyPo; 
                }
    
                $result = count(array_intersect($resultBa, $resultPo));
                // END CHECK COMPATIBILITY QUANTITY BA DI PO ITEM
    
                // VALIDASI STATUS PO
                if ($result != $limitPo) {
                    PurchaseOrder::where('id', $beritaAcara->po_id)->update(['status' => 0]);
                    return redirect('beritaAcara')->with('success', 'Berita Acara berhasil diubah!');
                } elseif ($result == $limitPo) {
                    PurchaseOrder::where('id', $beritaAcara->po_id)->update(['status' => 1]);
                    return redirect('beritaAcara')->with('success', 'Berita Acara berhasil diubah!');
                } else {
                    return redirect('beritaAcara')->with('danger', 'Berita Acara gagal diubah!');
                }

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
        $purchase_order = PurchaseOrder::where('id', $beritaAcara->po_id)->first();
        $berita_acara = BeritaAcara::where('id', $beritaAcara->id)->first();
        $limitPo = count($purchase_order->item);

        for ($i=0; $i < $limitPo; $i++) {
            $result[$purchase_order->item[$i]->pivot->item_id] = ['qtyBa' => $purchase_order->item[$i]->pivot->qtyBa - $berita_acara->item[$i]->pivot->qtyBa];
            $item[$i] = Item::where('id', $purchase_order->item[$i]->pivot->item_id)->first(); 

            Item::where('id', $purchase_order->item[$i]->pivot->item_id)->update(['stock' => $item[$i]->stock - $berita_acara->item[$i]->pivot->qtyBa]);
        }

        if (PurchaseOrder::find($beritaAcara->po_id)->item()->sync($result)) {
            PurchaseOrder::where('id', $beritaAcara->po_id)->update(['status' => 0]);
        }

        if (BeritaAcara::destroy($beritaAcara->id)) {
            return redirect('beritaAcara')->with('success', 'Berita Acara berhasil dihapus');
        } else {
            return redirect('beritaAcara')->with('danger', 'Berita Acara gagal dihapus');
        }
    }

    public function printOut($berita_acara) {

        $beritaAcara = BeritaAcara::where('id', $berita_acara)->with('item')->first();
        $purchaseOrder = PurchaseOrder::where('id', $beritaAcara->po_id)->first();
        $purchaseRequest = PurchaseRequest::where('id', $purchaseOrder->pr_id)->first();
        $supplier = Supplier::where('id', $beritaAcara->sp_id)->first();
        $total = $beritaAcara->item->sum('pivot.qtyBa');

        $data = [
            'berita_acaras' => $beritaAcara,
            'purchase_orders' => $purchaseOrder,
            'purchase_requests' => $purchaseRequest,
            'suppliers' => $supplier,
            'totals' => $total
        ];

        $pdf = PDF::loadView('reports.beritaAcara.beritaAcaraPrintOut', $data);

        //Aktifkan Local File Access supaya bisa pakai file external ( cth File .CSS )
        $pdf->setOption('enable-local-file-access', true);

        // Stream untuk menampilkan tampilan PDF pada browser
        return $pdf->stream('table.pdf');

        // Jika ingin langsung download (tanpai melihat tampilannya terlebih dahulu) kalian bisa pakai fungsi download
        // return $pdf->download('table.pdf);

    }

    public function export(Request $request) {

        // $items = Item::with('category')
        // ->withWhereHas('berita_acara', function (Builder $q) use($request){
        //     $q->whereMonth('berita_acara_item.created_at', $request->month);
        // })
        // ->get();

        // DD($items);

        return (new beritaAcaraExport)->forMonth($request->month)->download('BA.xlsx');
    }
}

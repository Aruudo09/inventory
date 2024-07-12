<?php

namespace App\Http\Controllers;

use PDF;
use Dompdf\Dompdf;
use App\Models\Item;
use App\Models\Division;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\App;
use App\Exports\purchaseOrderExport;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase.purchaseOrder.purchaseOrderIndex', [
            'purchase_orders' => PurchaseOrder::with(['user', 'supplier'])->latest()->filter(request(['search']))->paginate(7)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchase.purchaseOrder.purchaseOrderCreate', [
            'purchase_requests' => PurchaseRequest::select('id', 'prCode')->where('status', '!=', '1')->get(),
            'suppliers' => Supplier::select('id', 'spName')->get()
        ]);
    }

    public function setText($pr_id) {
        $input = PurchaseRequest::where('id', $pr_id)->first();

        $pr_item = $input->item;
     
        return response()->json($pr_item);
    }

    public function setDetail($po_id) {
        $input = PurchaseOrder::where('id', $po_id)->first();

        $po_item = $input->item;
     
        return response()->json($po_item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $initial = Division::where('id', Auth::id())->pluck('initials');
        $count = PurchaseOrder::count();
        $kebutuhan = $request->kebutuhan;
        $number = $count + 1;
        $limit = count($request->item_id);

        $sec = strtotime($request->created_at);
        $newdate = date ("Y-m-d H:i", $sec); 
        $newdate = $newdate . ":00";

        // MENGENERATE KODE STOCK REQUEST
        if ($request->created_at != null) {
            $month = Str::substr($request->created_at, 5, 2);
            $year = Str::substr($request->created_at, 2, 2);
            $prefix = sprintf('%04d', $number) . '/' . $initial[0]. '-' . $kebutuhan . '/' . $month . '/' . $year;
            $request['created_at'] = $newdate;
            $rules = [
                'poCode' => 'required',
                'pr_id' => 'required',
                'user_id' => 'required',
                'sp_id' => 'required',
                'description' => 'required',
                'pymntTerms' => 'required',
                'created_at' => 'required'
            ];

        } else {
            $prefix = sprintf('%04d', $number) . '/' . $initial[0]. '-' . $kebutuhan . '/' . date('m') . '/' . date('y');
            $rules = [
                'poCode' => 'required',
                'pr_id' => 'required',
                'user_id' => 'required',
                'sp_id' => 'required',
                'description' => 'required',
                'pymntTerms' => 'required'
            ];

        }

        $request['poCode'] = $prefix;
        $request['user_id'] = Auth::id();

        $validated = $request->validate($rules);
        

        if (PurchaseOrder::create($validated)) {
            $po_id = PurchaseOrder::select('id')->where('poCode', $prefix)->get();

            // UPDATE STATUS PURCHASE REQUEST
            purchaseRequest::where('id', $request->pr_id)->update(['status' => 1]);

            // KONDISI BILA MENGINPUT WAKTU ATAU TIDAK
            if ($request->created_at != null) {
                for ($i=0; $i < $limit; $i++) { 
                    PurchaseOrder::find($po_id[0]['id'])->item()->attach($request->item_id[$i], ['qtyPo' => $request->qtyPo[$i], 'satuan' => $request->satuan[$i], 'harga' => $request->harga[$i], 'total' => $request->qtyPo[$i]*$request->harga[$i], 'created_at' => $newdate]);
                    Item::where('id', $request->item_id[$i])->update(['satuan' => $request->satuan[$i], 'harga' => $request->harga[$i]]);
                    $sync[$request->item_id[$i]] = ['harga' => $request->harga[$i]];
                }
            } else {
                for ($i=0; $i < $limit; $i++) { 
                    PurchaseOrder::find($po_id[0]['id'])->item()->attach($request->item_id[$i], ['qtyPo' => $request->qtyPo[$i], 'satuan' => $request->satuan[$i], 'harga' => $request->harga[$i], 'total' => $request->qtyPo[$i]*$request->harga[$i]]);
                    Item::where('id', $request->item_id[$i])->update(['satuan' => $request->satuan[$i], 'harga' => $request->harga[$i]]);
                    $sync[$request->item_id[$i]] = ['harga' => $request->harga[$i]];
                }
            }
            
            // UPDATE ITEM DI TABLE SUPPLIER
            Supplier::find($request->sp_id)->item()->sync($sync);

            $request->session()->flash('success', 'PR baru berhasil ditambah!');
            return redirect('purchaseOrder/create');
        } else {
            $request->session()->flash('danger', 'PR baru gagal ditambah!');
            return redirect('purchaseOrder/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        return view('purchase.purchaseOrder.purchaseOrderEdit', [
            'purchase_order' => $purchaseOrder,
            'suppliers' => Supplier::select('id', 'spName')->get(),
            'items' => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseOrderRequest  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $rule = [
            'sp_id' => 'required',
            'description' => 'required',
            'pymntTerms' => 'required'
        ];

        if (isset($request->kebutuhan)) {
            $div = $request->kebutuhan;
            $prefix = $purchaseOrder->poCode;
            $fix = substr_replace($prefix, $div, 10, -6);
            $request['poCode'] = $fix;
            $rule['poCode'] = 'required';
        }

        $limit = count($request->item_id);
        $sync = [];

        
        $validated = $request->validate($rule);
        
        if (PurchaseOrder::where('id', $purchaseOrder->id)
        ->update($validated)) {
            
            for ($i=0; $i < $limit; $i++) { 
                $sync[$request->item_id[$i]] = ['qtyPo' => $request->qtyPo[$i], 'satuan' => $request->satuan[$i], 'harga' => $request->harga[$i], 'total' => $request->qtyPo[$i]*$request->harga[$i]];
                $sync2[$request->item_id[$i]] = ['harga' => $request->harga[$i]];
                if ($request->item_id[$i] != null) {
                    Item::where('id', $request->item_id[$i])->update(['satuan' => $request->satuan[$i], 'harga' => $request->harga[$i]]);
                }
            }
                PurchaseOrder::find($purchaseOrder->id)->item()->sync($sync);

                Supplier::find($request->sp_id)->item()->sync($sync2);
            
            return redirect('purchaseOrder')->with('success', 'Purchase Order berhasil diubah!');
        } else {
            return redirect('purchaseOrder')->with('danger', 'Purchase Order gagal diubah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        purchaseRequest::where('id', $purchaseOrder->pr_id)->update(['status' => 0]);
        if (PurchaseOrder::destroy($purchaseOrder->id)) {
            return redirect('purchaseOrder')->with('success', 'Purchase Order berhasil dihapus');
        } else {
            return redirect('purchaseOrder')->with('danger', 'Purchase Order gagal dihapus');
        }
    }

    public function printOut($po_id) {

        $purchaseOrder = PurchaseOrder::where('id', $po_id)->with('item')->first();

        $suppliers = Supplier::where('id', $purchaseOrder->sp_id)->first();

        $data = [
            'purchase_orders' => $purchaseOrder,
            'purchase_requests' => $purchaseOrder->purchase_request,
            'suppliers' => $suppliers,
        ];
            
        $pdf = PDF::loadView('reports.purchaseOrder.purchaseOrderPrintOut', $data);

        //Aktifkan Local File Access supaya bisa pakai file external ( cth File .CSS )
        $pdf->setOption('enable-local-file-access', true);

        // Stream untuk menampilkan tampilan PDF pada browser
        return $pdf->stream('table.pdf');

        // Jika ingin langsung download (tanpai melihat tampilannya terlebih dahulu) kalian bisa pakai fungsi download
        // return $pdf->download('table.pdf);

    }

    public function export(Request $request) {

        // $items = Item::with('purchaseOrder')->get();

        // $items = Item::with('category')
        //     ->withWhereHas('purchaseOrder', function (Builder $q) use($request) {
        //         $q->whereMonth('purchase_order_item.created_at', $request->month);
        //     })
        //     ->get();

        // DD($items);

        return (new purchaseOrderExport)->forMonth($request->month)->download('PO.xlsx');
    }

}

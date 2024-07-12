<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Item;
use App\Models\Division;
use Illuminate\Support\Str;
use App\Models\StockRequest;
use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use App\Exports\purchaseRequestExport;
use App\Http\Requests\StorePurchaseRequestRequest;
use App\Http\Requests\UpdatePurchaseRequestRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase.purchaseRequest.purchaseRequestIndex', [
            'purchase_requests' => PurchaseRequest::with('user')->latest()
                                ->where('user_id', Auth::id())->filter(request(['search']))->paginate(7)
        ]);
    }

    public function setDetail($pr_id) {
        $input = PurchaseRequest::where('id', $pr_id)->first();

        $pr_item = $input->item;
     
        return response()->json($pr_item);
    }
    
    public function setText($sr_id) {
        $input = StockRequest::where('id', $sr_id)->first();

        $sr_item = $input->item;
     
        return response()->json($sr_item);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('purchase.purchaseRequest.purchaseRequestCreate', [
            'stock_requests' => StockRequest::select('id', 'srCode')->where('status', '!= ', '1')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $initial = Division::where('id', Auth::user()->division_id)->pluck('initials');
        $count = PurchaseRequest::latest('id')->pluck('id')->first();
        $number = $count + 1;
        $limit = count($request->item_id);

        $sec = strtotime($request->created_at);
        $newdate = date ("Y-m-d H:i", $sec); 
        $newdate = $newdate . ":00";

        // MENGENERATE KODE STOCK REQUEST
        if ($request->created_at != null) {
            $month = Str::substr($request->created_at, 5, 2);
            $year = Str::substr($request->created_at, 2, 2);
            $prefix = sprintf('%04d', $number) . '/' . $initial[0]. '-' . $request->divSelect . '/' . $month . '/' . $year;
            $request['created_at'] = $newdate;
            $rules = [
                'sr_id' => 'required',
                'prCode' => 'required',
                'user_id' => 'required',
                'created_at' => 'required',
                'description' => 'required',
                'prUsed' => 'required'
            ];

            for ($i=0; $i < $limit; $i++) { 
                $item[$request->item_id[$i]] = ['qtyPr' => $request->qtyPr[$i], 'created_at' => $newdate];
            }

        } else {
            $prefix = sprintf('%04d', $number) . '/' . $initial[0]. '-' . $request->divSelect . '/' . date('m') . '/' . date('y');
            $rules = [
                'sr_id' => 'required',
                'prCode' => 'required',
                'user_id' => 'required',
                'description' => 'required',
                'prUsed' => 'required'
            ];

            for ($i=0; $i < $limit; $i++) { 
                $item[$request->item_id[$i]] = ['qtyPr' => $request->qtyPr[$i]];
            }

        }

        $request['prCode'] = $prefix;
        $request['user_id'] = Auth::id();

        $validated = $request->validate($rules);
        

        if (PurchaseRequest::create($validated)) {
            $pr_id = PurchaseRequest::select('id')->where('prCode', $prefix)->get();

            PurchaseRequest::find($pr_id[0]['id'])->item()->attach($item);

            StockRequest::where('id', $request->sr_id)->update(['status' => 1]);
            
            $request->session()->flash('success', 'PR baru berhasil ditambah!');
            return redirect('purchaseRequest/create');
        } else {
            $request->session()->flash('danger', 'PR baru gagal ditambah!');
            return redirect('purchaseRequest/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        return view('purchase.purchaseRequest.purchaseRequestEdit', [
            'purchase_requests' => $purchaseRequest,
            'items' => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequestRequest  $request
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $rule = [
            'description' => 'required',
            'prUsed' => 'required'
        ];

        if (isset($request->divSelect)) {
            $div = $request->divSelect;
            $prefix = $purchaseRequest->prCode;
            $fix = substr_replace($prefix, $div, 10, -6);
            $request['prCode'] = $fix;
            $rule['prCode'] = 'required';
        }

        $limit = count($request->item_id);
        $sync = [];

        for ($i=0; $i < $limit; $i++) { 
            $sync[$request->item_id[$i]] = ['qtyPr' => $request->qtyPr[$i]];
        }

        $validated = $request->validate($rule);

        if (PurchaseRequest::where('id', $purchaseRequest->id)
                    ->update($validated)) {
            
                PurchaseRequest::find($purchaseRequest->id)->item()->sync($sync);
            
            return redirect('purchaseRequest')->with('success', 'Purchase request berhasil diubah!');
        } else {
            return redirect('purchaseRequest')->with('danger', 'Purchase request gagal diubah!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        if (PurchaseRequest::destroy($purchaseRequest->id)) {
            StockRequest::where('id', $purchaseRequest->sr_id)->update(['status' => 0]);
            return redirect('purchaseRequest')->with('success', 'Purchase Request berhasil dihapus');
        } else {
            return redirect('purchaseRequest')->with('danger', 'Purchase Request gagal dihapus');
        }
    }

    public function printOut($pr_id) {

        $purchaseRequest = PurchaseRequest::where('id', $pr_id)->with('item')->first();
        $items = Item::with('category')->
        withWhereHas('purchaseRequest', function (Builder $q) use ($pr_id) {
            $q->where('purchase_request_id', $pr_id);
        })
        ->get();
        $stockRequest = StockRequest::where('id', $purchaseRequest->sr_id)->first();

        // DD($items);

        $data = [
            'items' => $items,
            'purchase_requests' => $purchaseRequest,
            'stock_requests' => $stockRequest
        ];

        $pdf = PDF::loadView('reports.purchaseRequest.purchaseRequestPrintOut', $data);

        //Aktifkan Local File Access supaya bisa pakai file external ( cth File .CSS )
        $pdf->setOption('enable-local-file-access', true);

        // Stream untuk menampilkan tampilan PDF pada browser
        return $pdf->stream('table.pdf');

        // Jika ingin langsung download (tanpai melihat tampilannya terlebih dahulu) kalian bisa pakai fungsi download
        // return $pdf->download('table.pdf);

    }

    public function export(Request $request) {

        return (new purchaseRequestExport)->forMonth($request->month)->download('PR.xlsx');
    }

}

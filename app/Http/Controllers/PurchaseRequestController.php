<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Division;
use App\Models\StockRequest;
use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePurchaseRequestRequest;
use App\Http\Requests\UpdatePurchaseRequestRequest;

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
            'stock_requests' => StockRequest::select('id', 'srCode')->get()
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
        $initial = Division::where('id', Auth::id())->pluck('initials');
        $count = PurchaseRequest::count();
        $number = $count + 1;
        $limit = count($request->item_id);

        $prefix = sprintf('%04d', $number) . '/' . $initial[0]. '-' . $request->divSelect . '/' . date('m') . '/' . date('y');

        $request['prCode'] = $prefix;
        $request['user_id'] = Auth::id();

        $rules = [
            'sr_id' => 'required',
            'prCode' => 'required',
            'user_id' => 'required',
            'description' => 'required',
            'prUsed' => 'required'
        ];

        $validated = $request->validate($rules);
        

        if (PurchaseRequest::create($validated)) {
            $pr_id = PurchaseRequest::select('id')->where('prCode', $prefix)->get();

            for ($i=0; $i < $limit; $i++) { 
                PurchaseRequest::find($pr_id[0]['id'])->item()->attach($request->item_id[$i], ['qtyPr' => $request->qtyPr[$i]]);
            }

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
            return redirect('purchaseRequest')->with('success', 'Purchase Request berhasil dihapus');
        } else {
            return redirect('purchaseRequest')->with('danger', 'Purchase Request gagal dihapus');
        }
    }
}

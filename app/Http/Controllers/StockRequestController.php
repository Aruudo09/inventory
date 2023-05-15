<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Division;
use App\Models\StockRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Http\Requests\StoreStockRequestRequest;
use App\Http\Requests\UpdateStockRequestRequest;

class StockRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('masterBarang.stock_request.stockRequestIndex', [
            'stock_requests' => StockRequest::with('user')->latest()
                                ->where('user_id', Auth::id())->filter(request(['search']))->paginate(7)
        ]);
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
        return view('masterBarang.stock_request.stockRequestCreate', [
            'items' => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStockRequestRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $initial = Division::where('id', Auth::id())->pluck('initials');
        $count = StockRequest::where('user_id', Auth::id())->count();
        $number = $count + 1;
        // $limit = $request['counter'];
        $limit = count($request->item_id);

        $prefix = sprintf('%03d', $number) . '/' . $initial[0]. '/' . date('m') . '/' . date('y');

        $request['srCode'] = $prefix;
        $request['user_id'] = Auth::id();
        $request['srDate'] = Carbon::now()->toDateTimeString();

        $rules = [
            'srCode' => 'required',
            'user_id' => 'required',
            'srDate' => 'required',
            'srUsed' => 'required'
        ];

        $validated = $request->validate($rules);
        

        if (StockRequest::create($validated)) {
            $sr_id = StockRequest::select('id')->where('srCode', $prefix)->get();

            for ($i=0; $i < $limit; $i++) { 
                StockRequest::find($sr_id[0]['id'])->item()->attach($request->item_id[$i], ['qtySr' => $request->qtySr[$i]]);
            }
            
            $request->session()->flash('success', 'SR baru berhasil ditambah!');
            return redirect('stockRequest/create');
        } else {
            $request->session()->flash('danger', 'SR baru gagal ditambah!');
            return redirect('stockRequest/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockRequest  $stockRequest
     * @return \Illuminate\Http\Response
     */
    public function show(StockRequest $stockRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockRequest  $stockRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(StockRequest $stockRequest)
    {
        return view('masterBarang.stock_request.stockRequestEdit', [
            'stock_requests' => $stockRequest,
            'items' => Item::select('id', 'itemName')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStockRequestRequest  $request
     * @param  \App\Models\StockRequest  $stockRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockRequest $stockRequest)
    {
        $limit = count($request->item_id);
        $sync = [];

        for ($i=0; $i < $limit; $i++) { 
            $sync[$request->item_id[$i]] = ['qtySr' => $request->qtySr[$i]];
        }

        $validated = $request->validate([
            'srUsed' => 'required'
        ]);

        if (StockRequest::where('id', $stockRequest->id)
                    ->update($validated)) {
            
                StockRequest::find($stockRequest->id)->item()->sync($sync);
            
            return redirect('stockRequest')->with('success', 'Stock request berhasil diubah!');
        } else {
            return redirect('stockRequest')->with('danger', 'Stock request gagal diubah!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockRequest  $stockRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockRequest $stockRequest)
    {
        if (StockRequest::destroy($stockRequest->id)) {
            return redirect('stockRequest')->with('success', 'Stock Request berhasil dihapus');
        } else {
            return redirect('stockRequest')->with('danger', 'Stock Request gagal dihapus');
        }
    }
}

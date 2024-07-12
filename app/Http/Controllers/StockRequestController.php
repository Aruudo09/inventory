<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Division;
use Illuminate\Support\Str;
use App\Models\StockRequest;
use Illuminate\Http\Request;
use App\Exports\StockRequestExport;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Http\Requests\StoreStockRequestRequest;
use App\Http\Requests\UpdateStockRequestRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

class StockRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->userLevel == 1) {
            return view('masterBarang.stock_request.stockRequestIndex', [
                'stock_requests' => StockRequest::with('user')->latest('id')
                                    ->filter(request(['search']))->paginate(7)
            ]);
        } else {
            return view('masterBarang.stock_request.stockRequestIndex', [
                'stock_requests' => StockRequest::with('user')->latest('id')
                                    ->where('user_id', Auth::id())->filter(request(['search']))->paginate(7)
            ]);    
        }
        
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
        $sec = strtotime($request->created_at);
        $newdate = date ("Y-d-m H:i", $sec); 
        $newdate = $newdate . ":00";
        

        $initial = Division::where('id', Auth::id())->pluck('initials');
        $count = StockRequest::latest('id')->pluck('id')->first();
        $number = $count + 1;
        $limit = count($request->item_id);
        
        // MENGENERATE KODE STOCK REQUEST
        if ($request->created_at != null) {
            $month = Str::substr($request->created_at, 5, 2);
            $year = Str::substr($request->created_at, 2, 2);
            $prefix = sprintf('%03d', $number) . '/' . $initial[0]. '/' . $month . '/' . $year;
            $request['created_at'] = $newdate;
            $rules = [
                'srCode' => 'required',
                'user_id' => 'required',
                'created_at' => 'required',
                'srUsed' => 'required'
            ];

            for ($i=0; $i < $limit; $i++) { 
                $item[$request->item_id[$i]] = ['qtySr' => $request->qtySr[$i], 'created_at' => $newdate];
            }

        } else {
            $prefix = sprintf('%03d', $number) . '/' . $initial[0]. '/' . date('m') . '/' . date('y');
            $rules = [
                'srCode' => 'required',
                'user_id' => 'required',
                'srUsed' => 'required'
            ];

            for ($i=0; $i < $limit; $i++) { 
                $item[$request->item_id[$i]] = ['qtySr' => $request->qtySr[$i]];
            }

        }
        

        $request['srCode'] = $prefix;
        $request['user_id'] = Auth::id();

        $validated = $request->validate($rules);
        

        if (StockRequest::create($validated)) {
            $sr_id = StockRequest::select('id')->where('srCode', $prefix)->get();

            StockRequest::find($sr_id[0]['id'])->item()->attach($item);
            
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

    public function printOut($sr_id) {

        $items = Item::with('category')->
        withWhereHas('stockRequest', function (Builder $q) use($sr_id) {
            $q->where('stock_request_id', $sr_id);
        })
        ->get();

        $stockRequest = StockRequest::where('id', $sr_id)->first();

        $data = [
            'items' => $items,
            'stock_requests' => $stockRequest
        ];

        $pdf = PDF::loadView('reports.stockRequest.stockRequestPrintOut', $data);

        //Aktifkan Local File Access supaya bisa pakai file external ( cth File .CSS )
        $pdf->setOption('enable-local-file-access', true);

        // Stream untuk menampilkan tampilan PDF pada browser
        return $pdf->stream('table.pdf');

        // Jika ingin langsung download (tanpai melihat tampilannya terlebih dahulu) kalian bisa pakai fungsi download
        // return $pdf->download('table.pdf);
    }

    public function export(Request $request) {

        // $items = Item::with('category')
        // ->withwhereHas('stockRequest', function (Builder $q) use($request){
        //     $q->whereMonth('stock_request_item.created_at', $request->month);
        // })
        // ->get();

        // DD($items);

        // return Excel::download(new ItemExport, 'item.xlsx');
        return (new StockRequestExport)->forMonth($request->month)->download('SR.xlsx');
    }

}

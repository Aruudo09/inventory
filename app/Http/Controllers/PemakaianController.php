<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Division;
use App\Models\Pemakaian;
use App\Models\StockTrack;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\pemakaianExport;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePemakaianRequest;
use App\Http\Requests\UpdatePemakaianRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PemakaianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->userLevel == 1) {
            return view('masterBarang.pemakaian.pemakaianIndex', [
                'pemakaians' => Pemakaian::with('user')->latest('id')
                                ->filter(request(['search']))->paginate(7)
            ]);
        } else {
            return view('masterBarang.pemakaian.pemakaianIndex', [
                'pemakaians' => Pemakaian::with('user')->latest('id')
                                ->where('user_id', Auth::id())->filter(request(['search']))->paginate(7)
            ]);    
        }
        
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
        $initial = Division::where('id', Auth::id())->pluck('initials');
        $count = Pemakaian::latest('id')->pluck('id')->first();
        $number = $count + 1;

        if ($request->created_at != null) {
            $month = Str::substr($request->created_at, 5, 2);
            $year = Str::substr($request->created_at, 2, 2);
            $prefix = $initial[0] . '-' . $number . '/' . $year;
        } else {
            $prefix = $initial[0] . '-' . $number . '/' . date('y');
        }

        // DD($prefix);
        
        $request['useCode'] = $prefix;
        $request['user_id'] = Auth::id();

        for ($i=0; $i < $limit; $i++) { 
            if ($request->created_at != null) {
                $attach[$request->item_id[$i]] = ['qtyUse' => $request->qtyUse[$i], 'created_at' => $request->created_at];
                $rules = [
                    'useCode' => 'required',
                    'user_id' => 'required',
                    'description' => 'required',
                    'created_at' => 'required'
                ];
            } else {
                $attach[$request->item_id[$i]] = ['qtyUse' => $request->qtyUse[$i]];
                $rules = [
                    'useCode' => 'required',
                    'user_id' => 'required',
                    'description' => 'required'
                ];    
            }
            
            // $item[$i] = Item::where('id', $request->item_id[$i])->first();
        }
        

        $validated = $request->validate($rules);
        

        if (Pemakaian::create($validated)) {
            $use_id = Pemakaian::select('id')->where('useCode', $prefix)->get();

            // UPDATE QUANTITY BARANG DI PEMAKAIAN
            Pemakaian::find($use_id[0]['id'])->item()->attach($attach);
    
            // UPDATE QUANTITY BARANG DI ITEM
            // for ($i=0; $i < $limit; $i++) { 
            //     Item::where('id', $request->item_id[$i])->update(['stock' => $item[$i]->stock - $request->qtyUse[$i]]);

            //      if ($request->created_at != null) {
            //         StockTrack::create(['item_id' => $request->item_id[$i], 'stockTr' => $item[$i]->stock - $request->qtyUse[$i], 'created_at' => $request->created_at]);
            //      } else {
            //         StockTrack::create(['item_id' => $request->item_id[$i], 'stockTr' => $item[$i]->stock - $request->qtyUse[$i]]);
            //      }
                 
            // }
            
            $request->session()->flash('success', 'Pemakaian baru berhasil ditambah!');
            return redirect('pemakaian/create');
        } else {
            $request->session()->flash('danger', 'Pemakaian baru gagal ditambah!');
            return redirect('pemakaian/create');
        }
    }

    public function approved(Request $request) {

        // DD($request);

        for ($i=0; $i < count($request->itemName); $i++) { 
            $item[$i] = Item::where('id', $request->item_id[$i])->first();
        }

        if (Pemakaian::where('id', $request->id)->update(['status' => 1])) {
            // UPDATE QUANTITY BARANG DI ITEM
            for ($i=0; $i < count($request->itemName); $i++) { 
                Item::where('id', $request->item_id[$i])->update(['stock' => $item[$i]->stock - $request->qtyUse[$i]]);

                 if ($request->created_at != null) {
                    StockTrack::create(['item_id' => $request->item_id[$i], 'stockTr' => $item[$i]->stock - $request->qtyUse[$i], 'created_at' => $request->created_at]);
                 } else {
                    StockTrack::create(['item_id' => $request->item_id[$i], 'stockTr' => $item[$i]->stock - $request->qtyUse[$i]]);
                 }
                 
            }
            
            $request->session()->flash('success', 'Pemakaian telah disetujui!');
            return redirect('pemakaian');
        }

        $request->session()->flash('danger', 'Pemakaian gagal disetujui!');
            return redirect('pemakaian');

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
        $pemakaian = Pemakaian::where('id', $pemakaian->id)->first();
        $sync = [];

        for ($i=0; $i < $limit; $i++) { 
            $sync[$request->item_id[$i]] = ['qtyUse' => $request->qtyUse[$i]];
            $pakai[$i] = $pemakaian->item[$i]->pivot->qtyUse;
            $item[$i] = Item::where('id', $request->item_id[$i])->first();
        }

        $validated = $request->validate([
            'description' => 'required'
        ]);


        if (Pemakaian::where('id', $pemakaian->id)
                    ->update($validated)) {
            
                        for ($i=0; $i < count($request->item_id); $i++) { 
                            Item::where('id', $request->item_id[$i])->update(['stock' => $item[$i]->stock - $pakai[$i] + $request->qtyBa[$i]]);
                        }

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
        $pemakaian = pemakaian::where('id', $use_id->id)->first();
        $count = count($pemakaian->item);

        for ($i=0; $i < $count; $i++) { 
            $qtyUse[$i] = $pemakaian->item[$i]->pivot->qtyUse;
            $item[$i] = Item::where('id', $pemakaian->item[$i]->pivot->item_id)->first();
            
            Item::where('id', $pemakaian->item[$i]->pivot->item_id)->update(['stock' => $item[$i]->stock + $qtyUse[$i]]);
        }

        if (Pemakaian::destroy($use_id->id)) {
            return redirect('pemakaian')->with('success', 'Pemakaian berhasil dihapus');
        } else {
            return redirect('pemakaian')->with('danger', 'Pemakaian gagal dihapus');
        }
    }

    public function printOut($use_id) {

        $items = Item::with('category')->
        withWhereHas('pemakaian', function (Builder $q) use ($use_id) {
            $q->where('use_id', $use_id);
        })
        ->get();

        $pemakaian = Pemakaian::where('id', $use_id)->first();

        $data = [
            'items' => $items,
            'pemakaians' => $pemakaian
        ];

        $pdf = PDF::loadView('reports.pemakaian.pemakaianPrintOut', $data);

        //Aktifkan Local File Access supaya bisa pakai file external ( cth File .CSS )
        $pdf->setOption('enable-local-file-access', true);

        // Stream untuk menampilkan tampilan PDF pada browser
        return $pdf->stream('table.pdf');

        // Jika ingin langsung download (tanpai melihat tampilannya terlebih dahulu) kalian bisa pakai fungsi download
        // return $pdf->download('table.pdf);
    }

    public function export(Request $request) {

        return (new pemakaianExport)->forMonth($request->month)->download('Pemakaian.xlsx');
    }
}

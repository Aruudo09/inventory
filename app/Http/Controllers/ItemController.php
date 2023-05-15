<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('masterBarang.item.itemIndex', [
            "items" => Item::latest()->filter(request(['search']))->paginate(7)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masterBarang.item.itemCreate', [
            "categories" => Category::select('id', 'categoryName')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $prefix = Category::select('categoryCode')->where('id', $request->category_id)->get()->toArray();

        $config = [
            'table' => 'items',
            'field' => 'partNumber',
            'length' => 8,
            'prefix' => $prefix[0]['categoryCode'] . '-'
        ];   

        $id = IdGenerator::generate($config);

        $request['partNumber'] = $id;

        $validated = $request->validate([
            'category_id' => 'required',
            'partNumber' => 'required',
            'itemName' => 'required',
            'stock' => 'required',
            'firstStock' => 'required',
            'stockIn' => 'required',
            'stockOut' => 'required',
            'satuan' => 'required',
            'harga' => 'required'
        ]);

        if (Item::create($validated)) {
            $request->session()->flash('success', 'Barang baru berhasil ditambah!');
            return redirect('item/create');
        } else {
            $request->session()->flash('danger', 'Barang baru gagal ditambah!');
            return redirect('item/create');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        return view('masterBarang.item.itemEdit', [
            'item' => Item::all()->find($item),
            'categories' => Category::all()->except($item->category_id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemRequest  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        $rule = [
            'category_id' => 'required',
            'itemName' => 'required',
            'stock' => 'required',
            'firstStock' => 'required',
            'stockIn' => 'required',
            'stockOut' => 'required',
            'satuan' => 'required',
            'harga' => 'required'
        ];

        if ($request->category_id != $item->category_id) {
            $cut = Str::substr($request->partNumber, 1, 8);
            $newCode = Category::where('id', $request->category_id)->pluck('categoryCode');
            $prefix = $newCode[0] . $cut;

            $request['partNumber'] = $prefix;
            $rule['partNumber'] = ['required', 'max:8'];

        }

        $validated = $request->validate($rule);

        if (Item::where('id', $item->id)
                    ->update($validated)) {
            return redirect('item')->with('success', 'Barang berhasil diubah!');
        } else {
            return redirect('item')->with('danger', 'Barang gagal diubah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        if (Item::destroy($item->id)) {
            return redirect('item')->with('success', 'Barang berhasil dihapus');
        } else {
            return redirect('item')->with('danger', 'Barang gagal dihapus');
        }
    }
}

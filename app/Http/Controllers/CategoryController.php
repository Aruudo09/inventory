<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('masterBarang.category.categoryIndex', [
            'categories' => Category::latest()->filter(request(['search']))->paginate(7)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masterBarang.category.categoryCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoryCode' => ['required', 'max:3'],
            'categoryName' => 'required'
        ]);
        
        if (Category::create($validated)) {
            $request->session()->flash('success', 'Kategori baru berhasil ditambah!');
            return redirect('category/create');
        } else {
            $request->session()->flash('danger', 'Kategori baru gagal ditambah!');
            return redirect('category/create');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($category)
    {
        return view('masterBarang.category.categoryEdit', [
            'categories' => Category::all()->find($category)
        ]);

        // return DD(Category::all()->find($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'categoryName' => 'required'
        ];

        if ($request->categoryCode != $category->categoryCode) {
            $rules['categoryCode'] = ['required', 'max:3'];
        }

        $validated = $request->validate($rules);

        if (Category::where('id', $category->id)
                    ->update($validated)) {
            return redirect('category')->with('success', 'Kategori baru berhasil diubah!');
        } else {
            return redirect('category')->with('danger', 'Kategori baru gagal ditambah!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (Category::destroy($category->id)) {
            return redirect('category')->with('success', 'Kategori berhasil dihapus');
        } else {
            return redirect('category')->with('danger', 'Kategori gagal dihapus');
        }
    }
}

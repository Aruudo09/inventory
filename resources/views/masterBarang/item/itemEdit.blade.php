@extends('layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Update Barang</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
      <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
      <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
      <span data-feather="calendar" class="align-text-bottom"></span>
      This week
    </button>
  </div>
</div>

<div class="mt-3">
    <form action="/item/update/{{ $item->id }}" method="post">
      {{-- @method('put') --}}
      @csrf
      <div class="mb-3 col-lg-5">
        <label for="category_id" class="form-label">Kategori</label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" id="category_id">
          <option value="{{ $item->category_id }}" selected>{{ $item->category->categoryName }}</option>
          @foreach ($categories as $category)
              <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
          @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
        @enderror
      </div>
      <div class="mb-3 col-lg-5">
        <!--hidden input-->
        <input type="hidden" name="partNumber" value="{{ $item->partNumber }}">
        <!--end hidden input-->
          <label for="itemName" class="form-label">Nama Barang:</label>
          <input type="text" name="itemName" class="form-control @error('itemName') is-invalid @enderror" value="{{ $item->itemName }}" id="itemName">
          @error('itemName')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="mb-3 col-lg-5">
          <label for="stock" class="form-label">Stock:</label>
          <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ $item->stock }}" id="stock">
          @error('stock')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="mb-3 col-lg-5">
          <label for="firstStock" class="form-label">Stock Awal:</label>
          <input type="number" name="firstStock" class="form-control @error('firstStock') is-invalid @enderror" value="{{ $item->firstStock }}" id="firstStock">
          @error('firstStock')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="mb-3 col-lg-5">
          <label for="stockIn" class="form-label">Stock Masuk:</label>
          <input type="number" name="stockIn" class="form-control @error('stockIn') is-invalid @enderror" value="{{ $item->stockIn }}" id="stockIn">
          @error('stockIn')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="mb-3 col-lg-5">
          <label for="stockOut" class="form-label">Stock Keluar:</label>
          <input type="text" name="stockOut" class="form-control @error('stockOut') is-invalid @enderror" value="{{ $item->stockOut }}" id="stockOut">
          @error('stockOut')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="mb-3 col-lg-5">
          <label for="satuan" class="form-label">Satuan:</label>
          <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ $item->satuan }}" id="satuan">
          @error('satuan')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="mb-3 col-lg-5">
          <label for="harga" class="form-label">Harga:</label>
          <input type="text" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ $item->harga }}" id="harga">
          @error('harga')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
@endsection
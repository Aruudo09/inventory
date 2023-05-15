@extends('masterBarang.items')

@section('view')
<div class="mt-3">

    <!--FLASH MESSAGE-->
    @if(session()->has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
    @endif
    <!-- END FLASH MESSAGE-->

    <!--FLASH MESSAGE-->
    @if(session()->has('danger'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
    @endif
    <!-- END FLASH MESSAGE-->

    <form action="/item/store" method="post">
      @csrf
        <div class="mb-3 col-lg-5">
          <label for="category_id" class="form-label">Kategori</label>
          <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" id="category_id">
            <option value="" selected>Pilih kategori</option>
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
            <label for="itemName" class="form-label">Nama Barang:</label>
            <input type="text" name="itemName" class="form-control @error('itemName') is-invalid @enderror" id="itemName">
            @error('itemName')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="stock" class="form-label">Stock:</label>
            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" id="stock">
            @error('stock')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="firstStock" class="form-label">Stock Awal:</label>
            <input type="number" name="firstStock" class="form-control @error('firstStock') is-invalid @enderror" id="firstStock">
            @error('firstStock')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="stockIn" class="form-label">Stock Masuk:</label>
            <input type="number" name="stockIn" class="form-control @error('stockIn') is-invalid @enderror" id="stockIn">
            @error('stockIn')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="stockOut" class="form-label">Stock Keluar:</label>
            <input type="text" name="stockOut" class="form-control @error('stockOut') is-invalid @enderror" id="stockOut">
            @error('stockOut')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="satuan" class="form-label">Satuan:</label>
            <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" id="satuan">
            @error('satuan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="harga" class="form-label">Harga:</label>
            <input type="text" name="harga" class="form-control @error('harga') is-invalid @enderror" id="harga">
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
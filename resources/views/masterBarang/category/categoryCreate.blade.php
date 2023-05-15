@extends('masterBarang.category')

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

    <form action="/category/store" method="post">
      @csrf
        <div class="mb-3 col-lg-5">
          <label for="categoryCode" class="form-label">Kode</label>
          <input type="text" name="categoryCode" class="form-control @error('categoryCode') is-invalid @enderror" id="categoryCode">
          @error('categoryCode')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <div class="mb-3 col-lg-5">
          <label for="categoryName" class="form-label">Kategori</label>
          <input type="text" name="categoryName" class="form-control @error('categoryName') is-invalid @enderror" id="categoryName">
          @error('categoryName')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
</div>
@endsection
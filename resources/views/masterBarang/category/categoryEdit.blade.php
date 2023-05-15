@extends('layouts.main')

@section('container')
  <div class="mt-3">
    <h1>UPDATE KATEGORI</h1>
    <form action="/category/update/{{ $categories->id }}" method="post">
      @method('put')
      @csrf
      <div class="mb-3">
        <input type="hidden" name="id" value="{{ $categories->id }}">
        <label for="categoryCode" class="form-label">Kode:</label>
        <input type="text" name="categoryCode" class="form-control @error('categoryCode') is-invalid @enderror" id="categoryCode" value="{{ $categories->categoryCode }}">
        @error('categoryCode')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="categoryName" class="form-label">Jenis Kategori:</label>
        <input type="text" name="categoryName" class="form-control @error('categoryName') is-invalid @enderror" id="categoryName" value="{{ $categories->categoryName }}">
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
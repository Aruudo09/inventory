@extends('supplier.supplier')

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

    <form action="/supplier/store" method="post">
      @csrf
          <div class="mb-3 col-lg-5">
            <label for="spName" class="form-label">Nama Supplier:</label>
            <input type="text" name="spName" class="form-control @error('spName') is-invalid @enderror" id="spName">
            @error('spName')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="cpName" class="form-label">Contact Person:</label>
            <input type="text" name="cpName" class="form-control @error('cpName') is-invalid @enderror" id="cpName">
            @error('cpName')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="cpNumber" class="form-label">Nomor Telp/Hp:</label>
            <input type="number" name="cpNumber" class="form-control @error('cpNumber') is-invalid @enderror" id="cpNumber">
            @error('cpNumber')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="faxNumber" class="form-label">Nomor Fax:</label>
            <input type="number" name="faxNumber" class="form-control @error('faxNumber') is-invalid @enderror" id="faxNumber">
            @error('faxNumber')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <h6>Pilih Barang:</h6>
          <div class="mb-3 col-lg-5">
              <select class="js-example-basic-single" style="width: 75%" name="itemSelect" id="supplierSelect">
                <option value="" selected disabled>Pilih Barang....</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->itemName }}</option>
                @endforeach
              </select>
          </div>
          <div class="mb-5">
            <table class="supplierTable table" id="supplierTable">
              <thead class="text-center">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                {{-- APPEND TALE ROW --}}
              </tbody>
            </table>
          </div>
          <div class="mb-3 col-lg-8">
            <label for="x" class="form-label">Alamat:</label>
            <input id="x" type="hidden" name="address">
            <trix-editor input="x"></trix-editor>
          </div>
          <input type="hidden" name="counter" id="counter" value="0">
        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Submit</button>
      </form>
    </div>    
@endsection
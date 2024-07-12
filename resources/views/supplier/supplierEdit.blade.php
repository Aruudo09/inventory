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

    <form action="/supplier/update/{{ $suppliers->id }}" method="post">
      @csrf
      @method('put')
          <div class="mb-3 col-lg-5">
            <label for="spName" class="form-label">Nama Supplier:</label>
            <input type="text" name="spName" class="form-control @error('spName') is-invalid @enderror" id="spName" value="{{ $suppliers->spName }}">
            @error('spName')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="cpName" class="form-label">Contact Person:</label>
            <input type="text" name="cpName" class="form-control @error('cpName') is-invalid @enderror" id="cpName" value="{{ $suppliers->cpName }}">
            @error('cpName')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="cpNumber" class="form-label">Nomor Telp/Hp:</label>
            <input type="number" name="cpNumber" class="form-control @error('cpNumber') is-invalid @enderror" id="cpNumber" value="{{ $suppliers->cpNumber }}">
            @error('cpNumber')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="faxNumber" class="form-label">Nomor Fax:</label>
            <input type="number" name="faxNumber" class="form-control @error('faxNumber') is-invalid @enderror" id="faxNumber" value="{{ $suppliers->faxNumber }}">
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
                  <th scope="col">Harga</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach ($suppliers->item as $supplier)
                  <tr id="supplierRow{{ $loop->iteration }}">
                    <td>{{ $loop->iteration }}</td>
                    <td><input type="hidden" name="item_id[]" value="{{ $supplier->pivot->item_id }}">{{ $supplier->itemName }}</td>
                    <td><input type="number" name="harga[]" value="{{ $supplier->pivot->harga }}"></td>
                    <td><button type="button" class="removeBtn btn btn-danger" id="supplierBtn{{ $loop->iteration }}" data-id="{{ $loop->iteration }}"><i class="bi bi-dash-square"></i></button></td>
                  </tr>
                @endforeach
                {{-- APPEND TALE ROW --}}
              </tbody>
            </table>
          </div>
          <div class="mb-3 col-lg-8">
            <label for="x" class="form-label">Alamat:</label>
            <input id="x" type="hidden" name="address" value="{{ $suppliers->address }}">
            <trix-editor input="x"></trix-editor>
          </div>
          @if (count($suppliers->item) != 0)
              <input type="hidden" name="counter" id="counter" value="{{ count($suppliers->item) }}">
          @else
              <input type="hidden" name="counter" id="counter" value="0">
          @endif
        <button type="submit" class="btn btn-primary" id="submit">Submit</button>
      </form>
    </div>    
@endsection
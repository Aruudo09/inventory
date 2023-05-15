@extends('purchase.purchaseOrder')

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

    <form action="/purchaseOrder/update/{{ $purchase_order->id }}" method="post">
      @csrf
      @method('put')
      <h6 class="mt-3 col-lg-5">Pilih Supplier:</h6>
      <div class="mb-3 col-lg-5">
          <select class="js-example-basic-single @error('sp_id') is-invalid @enderror" style="width: 75%" name="sp_id" id="sp_id">
              @foreach ($suppliers as $supplier)
                @if ($supplier->id == $purchase_order->sp_id)
                    <option value="{{ $supplier->id }}" selected>{{ $supplier->spName }}</option>             
                @else
                    <option value="{{ $supplier->id }}">{{ $supplier->spName }}</option>
                @endif
              @endforeach
          </select>
          @error('sp_id')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
          @enderror
      </div>
      <h6>Pilih Barang:</h6>
      <div class="mb-3 col-lg-5">
          <select class="js-example-basic-single" style="width: 75%" name="itemSelect" id="purchaseOrderSelect">
              <option value="" selected disabled>Pilih Barang....</option>
              @foreach ($items as $item)
                  <option value="{{ $item->id }}">{{ $item->itemName }}</option>
              @endforeach
          </select>
      </div>
      <div class="mb-3 col-lg-5">
          <label for="kebutuhan" class="form-label">Kebutuhan:</label>
          <select name="kebutuhan" class="form-select" id="kebutuhan">
              <option value="" selected disabled>Pilih Kebutuhan</option>
              <option value="P">Produksi</option>
              <option value="U">Umum</option>
          </select>
      </div>
      <div class="mb-5">
          <table class="purchaseOrderTable table" id="purchaseOrderTable">
          <thead class="text-center">
              <tr>
              <th scope="col">No</th>
              <th scope="col">Nama Barang</th>
              <th scope="col">Quantity</th>
              <th scope="col">Satuan</th>
              <th scope="col">Harga</th>
              <th scope="col">Action</th>
              </tr>
          </thead>
          <tbody class="text-center">
                @foreach ($purchase_order->item as $purchase_orders)
                  <tr id="poRow{{ $loop->iteration }}">
                    <td>{{ $loop->iteration }}</td>
                    <td><input type="hidden" name="item_id[]" value="{{ $purchase_orders->pivot->item_id }}">{{ $purchase_orders->itemName }}</td>
                    <td><input type="number" name="qtyPo[]" value="{{ $purchase_orders->pivot->qtyPo }}"></td>
                    <td><select style="width: 75%" name="satuanSelect[]" id="satuanPoSelect">  
                        <option value="{{ $purchase_orders->pivot->satuan }}" selected>{{ $purchase_orders->pivot->satuan }}</option> 
                        <option value="Pcs">Pcs</option> 
                        <option value="Kg">Kg</option> 
                        <option value="Liter">Liter</option> 
                        </select></td>
                    <td><input type="number" name="harga[]" value="{{ $purchase_orders->pivot->harga }}" required></td>
                    <td><button type="button" class="btn btn-danger deletePo" id="{{ $loop->iteration }}"><i class="bi bi-dash-square"></i></button></td>
                  </tr>
                @endforeach
              {{-- APPEND TALE ROW --}}
          </tbody>
          </table>
      </div>
      <div class="mb-3 col-lg-8">
          <label for="x" class="form-label">Deskripsi:</label>
          <input id="x" type="hidden" name="description" value="{{ $purchase_order->description }}">
          <trix-editor input="x"></trix-editor>
      </div>
      <div class="mb-3 col-lg-8">
          <label for="z" class="form-label">Payment Terms:</label>
          <input id="z" type="hidden" value="{{ $purchase_order->pymntTerms }}" name="pymntTerms">
          <trix-editor input="z"></trix-editor>
      </div>
      <input type="hidden" name="counter" id="counter" value="0">
      <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
      </form>
      </div>    
@endsection
@extends('purchase.purchaseRequest')

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

    <form action="/purchaseRequest/update/{{ $purchase_requests->id }}" method="post">
      @csrf
      @method('put')
          <div class="mb-3 col-lg-5">
            <label for="prUsed" class="form-label">Keterangan Pakai:</label>
            <input type="text" name="prUsed" class="form-control @error('prUsed') is-invalid @enderror" id="prUsed" value="{{ $purchase_requests->prUsed }}">
            @error('prUsed')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <div class="mb-3 col-lg-5">
            <label for="divSelect" class="form-label">Diutunjukan:</label>
            <select class="form-select" style="width: 75%" name="divSelect">
              <option value="" selected disabled>Pilih Divisi....</option>
              <option value="ST">Store</option>
              <option value="GS">General Service</option>
            </select>
          </div>
          <h6>Pilih Barang:</h6>
          <div class="mb-3 col-lg-5">
              <select class="js-example-basic-single" style="width: 75%" name="itemSelect" id="purchaseRequestSelect">
                <option value="" selected disabled>Pilih Barang....</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->itemName }}</option>
                @endforeach
              </select>
          </div>
          <div class="mb-5">
            <table class="purchaseRequestTable table" id="purchaseRequestTable">
              <thead class="text-center">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach ($purchase_requests->item as $purchase_request)
                  <tr id="prRow{{ $loop->iteration }}">
                    <td>{{ $loop->iteration }}</td>
                    <td><input type="hidden" name="item_id[]" value="{{ $purchase_request->pivot->item_id }}">{{ $purchase_request->itemName }}</td>
                    <td><input type="number" name="qtyPr[]" value="{{ $purchase_request->pivot->qtyPr }}"></td>
                    <td><button type="button" class="deletePr btn btn-danger" id="{{ $loop->iteration }}">
                      <i class="bi bi-dash-square"></i></button></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="mb-3 col-lg-8">
            <label for="x" class="form-label">Deskripsi:</label>
            <input id="x" type="hidden" name="description" value="{{ $purchase_requests->description }}">
            <trix-editor input="x"></trix-editor>
          </div>
          <input type="hidden" name="counter" id="counter" value="0">
        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
      </form>
    </div>    
@endsection
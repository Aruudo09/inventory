@extends('masterBarang.stockRequest')

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

    <form action="/stockRequest/update/{{ $stock_requests->id }}" method="post">
      @method('put')
      @csrf
      <div class="mb-3 col-lg-5">
        <label for="srUsed" class="form-label">Keterangan Pakai:</label>
        <input type="text" name="srUsed" class="form-control @error('srUsed') is-invalid @enderror" id="srUsed" value="{{ $stock_requests->srUsed }}">
        @error('srUsed')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
        @enderror
      </div>
      <h6>Pilih Barang:</h6>
      <div class="mb-3 col-lg-5">
          <select class="js-example-basic-single" style="width: 75%" name="itemSelect" id="stockRequestSelect">
            <option value="" selected>Pilih Barang....</option>
            @foreach ($items as $item)
                <option value="{{ $item->id }}">{{ $item->itemName }}</option>
            @endforeach
          </select>
      </div>
      <div class="mb-2">
        <table class="stockRequestTable table" id="stockRequestTable">
          <thead class="text-center">
            <tr>
              <th scope="col">No</th>
              <th scope="col">Nama Barang</th>
              <th scope="col">Quantity</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody class="text-center">
            @foreach ($stock_requests->item as $stock_request)
                <tr id="srRow{{ $loop->iteration }}">
                  <td>{{ $loop->iteration }}</td>
                  <td><input type="hidden" name="item_id[]" value="{{ $stock_request->pivot->item_id }}">{{ $stock_request->itemName }}</td>
                  <td><input type="number" name="qtySr[]" value="{{ $stock_request->pivot->qtySr }}"></td>
                  <td><button type="button" class="deleteSr btn btn-danger" id="{{ $loop->iteration }}">
                    <i class="bi bi-dash-square"></i></button></td>
                </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <input type="hidden" name="counter" id="counter" value="0">
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
@endsection
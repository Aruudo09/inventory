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

    <form action="/purchaseRequest/store" method="post">
      @csrf
      <div class="mb-3 col-lg-5">
        <label for="divSelect" class="form-label">Diutunjukan:</label>
        <select class="form-select" style="width: 75%" name="divSelect" required>
          <option value="" selected disabled>Pilih Divisi....</option>
          <option value="ST">Store</option>
          <option value="GS">General Service</option>
        </select>
      </div>
      <h6>Pilih Stock Request:</h6>
      <div class="mb-3 col-lg-5">
        <select class="js-example-basic-single" style="width: 75%" name="sr_id" id="purchaseRequestSelect">
          <option value="" selected disabled>Pilih SR....</option>
          @foreach ($stock_requests as $stock_request)
          <option value="{{ $stock_request->id }}" data-url="{{ route('setText.purchaseRequest', $stock_request->id) }}">{{ $stock_request->srCode }}</option>
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
            {{-- APPEND TALE ROW --}}
          </tbody>
        </table>
      </div>
      <div class="mb-3 col-lg-5">
        <label for="prUsed" class="form-label">Keterangan Pakai:</label>
        <input type="text" name="prUsed" class="form-control @error('prUsed') is-invalid @enderror" id="prUsed">
        @error('prUsed')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
        @enderror
      </div>
          <div class="mb-3 col-lg-8">
            <label for="x" class="form-label">Deskripsi:</label>
            <input id="x" type="hidden" name="description">
            <trix-editor input="x"></trix-editor>
          </div>
          <input type="hidden" name="counter" id="counter" value="0">
        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Submit</button>
      </form>
    </div>    
@endsection
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
  
    <form action="/purchaseOrder/store" method="post">
        @csrf
        <h6>Pilih Purchase Request:</h6>
        <div class="mb-3 col-lg-5">
            <select class="js-example-basic-single @error ('pr_id') is-invalid @enderror" style="width: 75%" name="pr_id" id="prSelect">
                <option value="" selected disabled>Pilih Purchase Request....</option>
                @foreach ($purchase_requests as $purchase_request)
                    <option data-url="{{ route('setText.purchaseOrder', $purchase_request->id) }}" value="{{ $purchase_request->id }}">{{ $purchase_request->prCode }}</option>
                @endforeach
            </select>
            @error('pr_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <h6 class="mt-3 col-lg-5">Pilih Supplier:</h6>
        <div class="mb-3 col-lg-5">
            <select class="js-example-basic-single @error('sp_id') is-invalid @enderror" style="width: 75%" name="sp_id" id="sp_id">
                <option value="" selected disabled>Pilih Supplier....</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->spName }}</option>
                @endforeach
            </select>
            @error('sp_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
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
                {{-- APPEND TALE ROW --}}
            </tbody>
            </table>
        </div>
        <div class="mb-3 col-lg-8">
            <label for="x" class="form-label">Deskripsi:</label>
            <input id="x" type="hidden" name="description">
            <trix-editor input="x"></trix-editor>
        </div>
        <div class="mb-3 col-lg-8">
            <label for="z" class="form-label">Payment Terms:</label>
            <input id="z" type="hidden" name="pymntTerms">
            <trix-editor input="z"></trix-editor>
        </div>
        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Submit</button>
        </form>
        </div> 
    </form>

</div>
@endsection
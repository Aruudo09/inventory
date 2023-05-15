@extends('beritaAcara.beritaAcara')

@section('view')
<form action="/beritaAcara/store" method="post">
    @csrf
    <div class="mb-3 col-lg-2">
        <label for="divSelect" class="form-label">From:</label>
        <select class="form-select" name="divSelect" id="divSelectt">
            <option value="" selected disabled>Pilih Divisi....</option>
            <option value="GA">General Service</option>
            <option value="ST">Store</option>
        </select>
    </div>
    <h6 class="mt-3 col-lg-5">Pilih Purchase Order:</h6>
    <div class="mb-3 col-lg-5">
        <select class="js-example-basic-single po_id @error('po_id') is-invalid @enderror" style="width: 75%" name="po_id" id="po_id">
            <option value="" selected disabled>Pilih Purchase Order....</option>
            @foreach ($purchase_orders as $purchase_order)
                <option data-url="{{ route('setText.beritaAcara', $purchase_order->id) }}" value="{{ $purchase_order->id }}">{{ $purchase_order->poCode }}</option>
            @endforeach
        </select>
        @error('po_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="mb-3 col-lg-5">
        <label for="spTxt" class="form-label">Supplier:</label>
        <input type="text" name="spTxt" class="form-input" value="" id="spTxt" disabled>
        <input type="hidden" name="sp_id" class="sp_id" id="sp_id">
    </div>
    <div class="mb-5">
        <table class="beritaAcaraTable table" id="beritaAcaraTable">
        <thead class="text-center">
            <tr>
            <th scope="col">No</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Quantity</th>
            <th scope="col">Satuan</th>
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
    <input type="hidden" name="counter" id="counter" value="0">
    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
    </form>
    </div> 
</form>
@endsection
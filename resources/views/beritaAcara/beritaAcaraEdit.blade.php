@extends('beritaAcara.beritaAcara')

@section('view')
<form action="/beritaAcara/update/{{ $berita_acara->id }}" method="post">
    @csrf
    @method('put')
    <div class="my-3 col-lg-2">
        <label for="divSelect" class="form-label">From:</label>
        <select class="form-select" name="divSelect" id="divSelectt">
            <option value="" selected disabled>Pilih Divisi....</option>
            <option value="GA">General Service</option>
            <option value="ST">Store</option>
        </select>
    </div>
    <div class="mb-3 col-lg-5">
        <label for="spTxt" class="form-label">Supplier:</label>
        <input type="text" name="spTxt" class="form-input" value="{{ $berita_acara->supplier->spName }}" id="spTxt" disabled>
        <input type="hidden" name="sp_id" value="{{ $berita_acara->sp_id }}" class="sp_id" id="sp_id">
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
            @foreach ($berita_acara->item as $item)
            <tr>
                <td><input type="hidden" name="item_id[]" value="{{ $item->pivot->item_id }}">{{ $loop->iteration }}</td>
                <td>{{ $item->itemName }}</td>
                <td><input type="number" name="qtyBa[]" value="{{ $item->pivot->qtyBa }}"></td>
                <td><input type="hidden" name="satuan[]" value="{{ $item->pivot->satuan }}">{{ $item->satuan }}</td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    <div class="mb-3 col-lg-8">
        <label for="x" class="form-label">Deskripsi:</label>
        <input id="x" type="hidden" value="{{ $berita_acara->description }}" name="description">
        <trix-editor input="x"></trix-editor>
    </div>
    <input type="hidden" name="counter" id="counter" value="0">
    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
    </form>
    </div> 
</form>
@endsection
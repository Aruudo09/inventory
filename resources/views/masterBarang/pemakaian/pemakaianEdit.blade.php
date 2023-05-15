@extends('masterBarang.pemakaian')

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

    <form action="/pemakaian/update/{{ $pemakaians->id }}" method="post">
      @method('put')
      @csrf
          <h6>Pilih Barang:</h6>
          <div class="mb-3 col-lg-5">
              <select class="js-example-basic-single" style="width: 75%" name="itemSelect" id="pemakaianSelect">
                <option value="" selected disabled>Pilih Barang....</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->itemName }}</option>
                @endforeach
              </select>
          </div>
          <div class="mb-2">
            <table class="pemakaianTable table" id="pemakaianTable">
              <thead class="text-center">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach ($pemakaians->item as $pemakaian)
                <tr id="useRow{{ $loop->iteration }}">
                  <td>{{ $loop->iteration }}</td>
                  <td><input type="hidden" name="item_id[]" value="{{ $pemakaian->pivot->item_id }}">{{ $pemakaian->itemName }}</td>
                  <td><input type="number" name="qtyUse[]" value="{{ $pemakaian->pivot->qtyUse }}"></td>
                  <td><button type="button" class="deleteUse btn btn-danger" id="{{ $loop->iteration }}">
                    <i class="bi bi-dash-square"></i></button></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="mb-3 col-lg-8">
            <label for="x" class="form-label">Deskripsi:</label>
            <input id="x" type="hidden" value="{{ $pemakaians->description }}" name="description">
            <trix-editor input="x"></trix-editor>
            @error('description')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
            @enderror
          </div>
          <input type="hidden" name="counter" id="counter" value="0">
        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
      </form>
    </div>    
@endsection
@extends('beritaAcara.beritaAcara')

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

  <div class="col-lg-5">
    <form action="/beritaAcara">
      <div class="input-group mb-3">
          <input type="text" class="form-control" name="search" placeholder="Cari berita acara....." aria-label="Recipient's username" aria-describedby="button-addon2">
          <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
      </div>
    </form>
  </div>

  <table class="table table-bordered text-center">
      <thead class="table-primary">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Number</th>
          <th scope="col">PO</th>
          <th scope="col">Name</th>
          <th scope="col">Supplier</th>
          <th scope="col">Input Time</th>
          <th scope="col">Update Time</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        @foreach ($berita_acaras as $berita_acara)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $berita_acara->baCode }}</td>
              <td>{{ $berita_acara->purchase_order->poCode }}</td>
              <td>{{ $berita_acara->user->username }}</td>
              <td>{{ $berita_acara->supplier->spName }}</td>
              <td>{{ $berita_acara->created_at }}</td>
              <td>{{ $berita_acara->updated_at }}</td>
              <td>
                <!---DETAIL BUTTON-->
                <button type="button" data-url="{{ route('setDetail.detailBa', $berita_acara->id) }}" data-code="{{ $berita_acara->baCode }}" data-po="{{ $berita_acara->purchase_order->poCode }}" 
                  data-name="{{ $berita_acara->user->username }}" data-description="{{ $berita_acara->description }}"
                  class="badge text-bg-warning border-0 detailBtnBa" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="bi bi-card-text"></i>
                </button>
                 <!--UPDATE BUTTON-->
                 <a href="{{route('beritaAcara.edit', $berita_acara->id )}}" class="badge text-bg-primary"><i class="bi bi-pencil-square"></i></a>
                 <!--END UPDATE BUTTON-->
                 <!--HAPUS BUTTON-->
                 <form action="/beritaAcara/destroy/{{ $berita_acara->id }}" method="post" class="d-inline">
                   @method('delete')
                   @csrf
                   <button class="badge text-bg-danger border-0" type="submit" onclick="return confirm('Anda Yakin?')"><i class="bi bi-x-square"></i></button>
                 </form>
                 <!--END HAPUS BUTTON-->
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>

  <div>
    {{ $berita_acaras->links() }}
  </div>

  <!--MODAL DETAIL PURCHASE ORDER-->
  <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detail BA</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
              <tr>
                <th class="col-1 table-warning">Kode BA</th>
                <td id="baCode"></td>
              </tr>
              <tr>
                <th class="table-warning">Kode PO</th>
                <td id="poCode"></td>
              </tr>
              <tr>
                <th class="table-warning">Nama</th>
                <td id="baName"></td>
              </tr>
              <tr>
                <th class="table-warning">Deskripsi</th>
                <td id="description"></td>
              </tr>
          </table>
          <table class="poDetail table table-bordered" id="poDetail">
            <thead class="text-center table-primary">
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
        <div class="modal-footer">
          <button type="button" id="closeBtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
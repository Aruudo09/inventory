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
  
  <div class="col-lg-5">
    <form action="/supplier">
      <div class="input-group mb-3">
          <input type="text" class="form-control" name="search" placeholder="Cari supplier....." aria-label="Recipient's username" aria-describedby="button-addon2">
          <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
      </div>
    </form>
  </div>

  <table class="table table-bordered text-center">
      <thead class="table-primary">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Code</th>
          <th scope="col">Supplier</th>
          <th scope="col">CP Name</th>
          <th scope="col">CP Number</th>
          <th scope="col">FAX Number</th>
          <th scope="col">Update Time</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        @foreach ($suppliers as $supplier)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $supplier->spCode }}</td>
              <td>{{ $supplier->spName }}</td>
              <td>{{ $supplier->cpName }}</td>
              <td>{{ $supplier->cpNumber }}</td>
              <td>{{ $supplier->faxNumber }}</td>
              <td>{{ $supplier->updated_at }}</td>
              <td>
                <!---DETAIL BUTTON-->
                <button type="button" data-url="{{ route('setText.supplier', $supplier->id) }}" data-code="{{ $supplier->spCode }}"
                   data-name="{{ $supplier->spName }}" data-address="{{ $supplier->address }}" class="badge text-bg-warning border-0 detailBtnSp" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="bi bi-card-text"></i>
                </button>
                 <!--UPDATE BUTTON-->
                 <a href="{{route('supplier.edit', $supplier->id )}}" class="badge text-bg-primary"><i class="bi bi-pencil-square"></i></a>
                 <!--END UPDATE BUTTON-->
                 <!--HAPUS BUTTON-->
                 <form action="/supplier/destroy/{{ $supplier->id }}" method="post" class="d-inline">
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
      {{ $suppliers->links() }}
    </div>

    <!--MODAL DETAIL SUPPLIER-->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Supplier</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                  <th class="col-1 table-warning">Kode</th>
                  <td id="spCode"></td>
                </tr>
                <tr>
                  <th class="table-warning">Supplier</th>
                  <td id="spName"></td>
                </tr>
                <tr>
                  <th class="table-warning">Alamat</th>
                  <td id="spAddress"></td>
                </tr>
            </table>
            <table class="spDetail table table-bordered" id="spDetail">
              <thead class="text-center table-primary">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">harga</th>
                </tr>
              </thead>
              <tbody class="text-center">
                {{-- APPEND TALE ROW --}}
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" id="closeBtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

</div>
@endsection
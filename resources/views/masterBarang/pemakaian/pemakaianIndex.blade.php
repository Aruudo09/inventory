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
    {{ session('danger') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
<!-- END FLASH MESSAGE-->

    {{-- <div class="col-lg-5">
      <form action="/pemakaian">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="search" placeholder="Cari pemakaian....." aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
        </div>
      </form>
    </div> --}}

  <table class="table table-bordered text-center">
      <thead class="table-primary">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Number</th>
          <th scope="col">Name</th>
          <th scope="col">Create</th>
          <th scope="col">Update</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        @foreach ($pemakaians as $pemakaian)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $pemakaian->noUsed }}</td>
              <td>{{ $pemakaian->user->username }}</td>
              <td>{{ $pemakaian->created_at }}</td>
              <td>{{ $pemakaian->updated_at }}</td>
              <td>
                <!---DETAIL BUTTON-->
                <button type="button" data-url="{{ route('setDetail.detailUse', $pemakaian->id) }}" data-code="{{ $pemakaian->noUsed }}"
                   data-name="{{ $pemakaian->user->username }}" class="badge text-bg-warning border-0 detailBtnUse" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="bi bi-card-text"></i>
                </button>
                 <!--UPDATE BUTTON-->
                 <a href="{{route('pemakaian.edit', $pemakaian->id )}}" class="badge text-bg-primary"><i class="bi bi-pencil-square"></i></a>
                 <!--END UPDATE BUTTON-->
                 <!--HAPUS BUTTON-->
                 <form action="/pemakaian/destroy/{{ $pemakaian->id }}" method="post" class="d-inline">
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
      {{-- {{ $stock_requests->links() }} --}}
    </div>

    <!--MODAL DETAIL STOCK REQUEST-->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Pemakaian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                  <th class="col-1 table-warning">Nomor</th>
                  <td id="noUsed"></td>
                </tr>
                <tr>
                  <th class="table-warning">Nama</th>
                  <td id="useName"></td>
                </tr>
            </table>
            <table class="useDetail table table-bordered" id="useDetail">
              <thead class="text-center table-primary">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Quantity</th>
                </tr>
              </thead>
              <tbody class="text-center">
                
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
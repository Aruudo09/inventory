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
    {{ session('danger') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  <!-- END FLASH MESSAGE-->
  
  <div class="col-lg-5">
    <form action="/purchaseOrder">
      <div class="input-group mb-3">
          <input type="text" class="form-control" name="search" placeholder="Cari stock request....." aria-label="Recipient's username" aria-describedby="button-addon2">
          <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
      </div>
    </form>
  </div>
  
  <table class="table table-bordered text-center">
      <thead class="table-primary">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Number</th>
          <th scope="col">Name</th>
          <th scope="col">Status</th>
          <th scope="col">Supplier</th>
          <th scope="col">Input Time</th>
          <th scope="col">Update Time</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        @foreach ($purchase_orders as $purchase_order)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $purchase_order->poCode }}</td>
              <td>{{ $purchase_order->user->username }}</td>
              @if ($purchase_order->status == 0)
                  <td>Belum</td>
              @else
                  <td>Sudah</td>
              @endif
              <td>{{ $purchase_order->supplier->spName }}</td>
              <td>{{ $purchase_order->created_at }}</td>
              <td>{{ $purchase_order->updated_at }}</td>
              <td>
                {{-- PRINT BUTTON --}}
                <a href="/purchaseOrder/printOut/{{ $purchase_order->id }}" class="badge text-bg-success border-0">
                  <i class="bi bi-file-earmark-arrow-down"></i>
                </a>
                <!---DETAIL BUTTON-->
                <button type="button" data-url="{{ route('setDetail.detailPo', $purchase_order->id) }}" data-code="{{ $purchase_order->poCode }}" 
                  data-name="{{ $purchase_order->user->username }}" data-description="{{ $purchase_order->description }}"
                  data-pr="{{ $purchase_order->purchase_request->prCode }}" data-pymnt="{{ $purchase_order->pymntTerms }}"
                  class="badge text-bg-warning border-0 detailBtnPo" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="bi bi-card-text"></i>
                </button>
                @if ($purchase_order->status == 0)
                    <!--UPDATE BUTTON-->
                 <a href="{{route('purchaseOrder.edit', $purchase_order->id )}}" class="badge text-bg-primary"><i class="bi bi-pencil-square"></i></a>
                 <!--END UPDATE BUTTON-->
                 <!--HAPUS BUTTON-->
                 <form action="/purchaseOrder/destroy/{{ $purchase_order->id }}" method="post" class="d-inline">
                   @method('delete')
                   @csrf
                   <button class="badge text-bg-danger border-0" type="submit" onclick="return confirm('Anda Yakin?')"><i class="bi bi-x-square"></i></button>
                 </form>
                 <!--END HAPUS BUTTON-->
                @else
                    {{-- KOSONG --}}
                @endif
              </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  
    <div>
      {{ $purchase_orders->links() }}
    </div>

    <!--MODAL DETAIL PURCHASE ORDER-->
    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail PO</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                  <th class="col-1 table-warning">Kode PO</th>
                  <td id="poCode"></td>
                </tr>
                <tr>
                  <th class="table-warning">Kode PR</th>
                  <td id="prCode"></td>
                </tr>
                <tr>
                  <th class="table-warning">Nama</th>
                  <td id="poName"></td>
                </tr>
                <tr>
                  <th class="table-warning">Deskripsi</th>
                  <td id="description"></td>
                </tr>
                <tr>
                  <th class="table-warning">Payment Terms</th>
                  <td id="pymntTerms"></td>
                </tr>
            </table>
            <table class="poDetail table table-bordered" id="poDetail">
              <thead class="text-center table-primary">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Barang</th>
                  <th scope="col">Quantity</th>
                  <th scope="col">Harga</th>
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
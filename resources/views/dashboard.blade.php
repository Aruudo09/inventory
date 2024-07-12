@extends('layouts.main')

@section('container')


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Dashboard</h1>
</div>

    <!--FLASH MESSAGE-->
    @if(session()->has('danger'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('danger') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <!-- END FLASH MESSAGE-->

    <canvas class="mb-3" id="line-chart" width="900" height="380"></canvas>

    <h2>INVENTORI BARANG</h2>
    <div class="table-responsive">
      <table class="table table-striped table-sm">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Kode</th>
            <th scope="col">Barang</th>
            <th scope="col">Kategori</th>
            <th scope="col">Stock</th>
            <th scope="col">Stock Masuk</th>
            <th scope="col">Stock Keluar</th>
            <th scope="col">Satuan</th>
            <th scope="col">Harga</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $item)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $item->partNumber }}</td>
                <td>{{ $item->itemName }}</td>
                <td>{{ $item->category->categoryName }}</td>
                <td>{{ $item->stock }}</td>
                <td>{{ $item->berita_acara->sum('pivot.qtyBa') }}</td>
                <td>{{ $item->pemakaian->sum('pivot.qtyUse') }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ number_format($item->harga, 0, '', '.') }}</td>
              </tr>
          @endforeach
        </tbody>
      </table>
    </div>
@endsection
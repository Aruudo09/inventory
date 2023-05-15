@extends('masterBarang.items')

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
        <form action="/item">
          <div class="input-group mb-3">
              <input type="text" class="form-control" name="search" placeholder="Cari barang....." aria-label="Recipient's username" aria-describedby="button-addon2">
              <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
          </div>
        </form>
      </div>
  
      <table class="table table-bordered text-center">
          <thead class="table-primary">
            <tr>
              <th scope="col">No</th>
              <th scope="col">Part Number</th>
              <th scope="col">Nama</th>
              <th scope="col">Kategori</th>
              <th scope="col">Stock</th>
              <th scope="col">First Stock</th>
              <th scope="col">Stock Masuk</th>
              <th scope="col">Stock Keluar</th>
              <th scope="col">Satuan</th>
              <th scope="col">Harga</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody class="table-group-divider">
            @foreach ($items as $item)
            <tr>
              <th scope="row">{{ $loop->iteration }}</th>
              <td>{{ $item->partNumber }}</td>
              <td>{{ $item->itemName }}</td>
              <td>{{ $item->category->categoryName }}</td>
              <td>{{ $item->stock }}</td>
              <td>{{ $item->firstStock }}</td>
              <td>{{ $item->stockIn }}</td>
              <td>{{ $item->stockOut }}</td>
              <td>{{ $item->satuan }}</td>
              <td>{{ $item->harga }}</td>
              <td>
                 <!--UPDATE BUTTON-->
                 <a href="{{route('item.edit', $item->id )}}" class="badge text-bg-primary"><i class="bi bi-pencil-square"></i></a>
                 <!--END UPDATE BUTTON-->
                 <!--HAPUS BUTTON-->
                 <form action="/item/destroy/{{ $item->id }}" method="post" class="d-inline">
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
          {{ $items->links() }}
        </div>
        
  </div>
@endsection
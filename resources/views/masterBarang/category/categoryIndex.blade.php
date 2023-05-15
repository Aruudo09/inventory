@extends('masterBarang.category')

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
      <form action="/category">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="search" placeholder="Cari kategori....." aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
        </div>
      </form>
    </div>

    <table class="table table-bordered text-center">
        <thead class="table-primary">
          <tr>
            <th scope="col">No</th>
            <th scope="col">Code</th>
            <th scope="col">Kategori</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          @foreach ($categories as $category)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $category->categoryCode }}</td>
            <td>{{ $category->categoryName }}</td>
            <td>
              <div>
                <!--UPDATE BUTTON-->
                <a href="{{route('category.edit', $category->id )}}" class="badge text-bg-primary"><i class="bi bi-pencil-square"></i></a>
                <!--END UPDATE BUTTON-->
                <!--HAPUS BUTTON-->
                <form action="/category/destroy/{{ $category->id }}" method="post" class="d-inline">
                  @method('delete')
                  @csrf
                  <button class="badge text-bg-danger border-0" type="submit" onclick="return confirm('Anda Yakin?')"><i class="bi bi-x-square"></i></button>
                </form>
                <!--END HAPUS BUTTON-->
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div>
        {{ $categories->links() }}
      </div>
</div>

@endsection
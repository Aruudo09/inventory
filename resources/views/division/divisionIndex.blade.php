@extends('division.division')

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
                    <th>No</th>
                    <th>Nama</th>
                    <th>Head</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($divisions as $division)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $division->divisionName }}</td>
                        <td>{{ $division->divisionHead }}</td>
                        <td>
                            <!--UPDATE BUTTON-->
                            <!-- Button trigger modal -->
                            <button type="button" class="badge text-bg-primary border-0 divisionBtn" id="divisionBtn" data-id="{{ $division->id }}" data-url="{{ route('setText.register', $division->id) }}" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            {{-- <a href="{{route('register.edit', $user->id )}}" class="badge text-bg-primary"><i class="bi bi-pencil-square"></i></a> --}}
                            <!--END UPDATE BUTTON-->
                            <!--HAPUS BUTTON-->
                            <form action="/division/destroy/{{ $division->id }}" method="post" class="d-inline">
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


          {{-- UPDATE MODAL --}}
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
         <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Edit Division</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="" id="divisionForm">
                @method('put')
                <div class="mb-3">
                    <label for="divisionName" class="form-label">Name</label>
                    <input type="text" name="divisionName" class="form-control @error('divisionName') is-invalid @enderror" id="divisionName" value="{{ old('divisionName') }}">
                      @error('divisionName')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                  </div>
                <div class="mb-3">
                  <label for="initials" class="form-label">Initial</label>
                  <input type="text" name="initials" class="form-control @error('initials') is-invalid @enderror" id="initials" value="{{ old('initials') }}">
                    @error('initials')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                  <label for="divisionHead" class="form-label">Division Head</label>
                  <input type="text" name="divisionHead" class="form-control @error('divisionHead') is-invalid @enderror" id="divisionHead" value="{{ old('divisionHead') }}">
                    @error('divisionHead')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
             </form>
            </div>
          </div>
        </div>
      </div>

    </div>
@endsection
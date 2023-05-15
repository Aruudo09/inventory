@extends('register.register')

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
            <form action="/register">
              <div class="input-group mb-3">
                  <input type="text" class="form-control" name="search" placeholder="Cari user....." aria-label="Recipient's username" aria-describedby="button-addon2">
                  <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
              </div>
            </form>
          </div>

          <table class="table table-bordered text-center">
            <thead class="table-primary">
                <tr>
                    <td>No</td>
                    <td>Username</td>
                    <td>Division</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->division->divisionName }}</td>
                        <td>
                            <!--UPDATE BUTTON-->
                            <!-- Button trigger modal -->
                            <button type="button" class="badge text-bg-primary border-0 registerBtn" id="registerBtn" data-link="{{ $user->id }}" data-url="{{ route('setText.register', $user->id) }}" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            {{-- <a href="{{route('register.edit', $user->id )}}" class="badge text-bg-primary"><i class="bi bi-pencil-square"></i></a> --}}
                            <!--END UPDATE BUTTON-->
                            <!--HAPUS BUTTON-->
                            <form action="/register/destroy/{{ $user->id }}" method="post" class="d-inline">
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
    </div>

    {{-- UPDATE MODAL --}}
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="" id="registerForm" method="POST">
                @method('put')
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Name</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username">
                    @error('username')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
                <div class="mb-3">
                  <label for="division_id" class="form-label">Divisi</label>
                  <select class="form-select @error('division_id') is-invalid @enderror" name="division_id" aria-label="Default select example" id="divisionSelect">
                    <option selected disabled value="">Pilih Divisi</option>
                      @foreach ($divisions as $division)
                        <option value="{{ $division->id }}">{{ $division->divisionName }}</option>
                      @endforeach
                  </select>
                    @error('division_id')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-eye-slash" id="togglePassword"></i></span>
                      @error('password')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                  </div>
                </div>
                <div class="mb-3">
                  <label for="userlevel" class="form-label">Level User</label>
                  <select name="userLevel" class="form-select @error('userLevel') is-invalid @enderror" id="userLevel">
                    <option value="" selected disabled>Pilih level user</option>
                    <option value="1">Admin</option>
                    <option value="0">User</option>
                  </select>
                    @error('userLevel')
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

@endsection
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
    {{ session('danger') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  <!-- END FLASH MESSAGE-->

  <form action="/register/store" method="post">
    @csrf
    <div class="mb-3 col-lg-5">
        <label for="username" class="form-label">Name</label>
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username">
        @error('username')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
    </div>
    <div class="mb-3 col-lg-5">
      <label for="division_id" class="form-label">Divisi</label>
      <select class="form-select @error('division_id') is-invalid @enderror" name="division_id" aria-label="Default select example">
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
    <div class="mb-3 col-lg-5">
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
    <div class="mb-3 col-lg-5">
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
    <button type="submit" class="btn btn-primary">Submit</button>
 </form>

    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
  
        togglePassword.addEventListener("click", function() {
          const type = password.getAttribute("type") === "password" ? "text":"password";
          password.setAttribute("type", type);
  
          this.classList.toggle('bi-eye');
        });
      </script>

@endsection
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid">
      <div class="col-lg-6 mx-auto mt-5">
        <!--FLASH MESSAGE-->
        @if(session()->has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        <!--END FLASH MESSAGE-->

        
        <a href="/login" class="btn btn-success"><i class="bi bi-arrow-left-square"></i> Kembali</a>
        <h1 class="text-center">REGISTER</h1>
        <form class="border border-dark p-4" action="/register" method="post">
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
          <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      </div>
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

</body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</html>
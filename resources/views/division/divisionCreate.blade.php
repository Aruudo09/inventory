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
  
  <form class="border border-dark col-lg-8 p-4" action="/division/store" method="post">
    @csrf
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
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  
</div>
@endsection
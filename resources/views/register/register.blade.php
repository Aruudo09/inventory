@extends('layouts.main')

@section('container')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Register</h1>
  </div>

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link {{ (request()->is('register')) ? 'active' : '' }}" href="/register">Index</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ (request()->is('register/create')) ? 'active' : '' }}" href="/register/create">Create</a>
    </li>
  </ul>

  @yield('view')
    
@endsection
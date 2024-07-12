@extends('layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Stock Request</h1>
  </div>

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link {{ (request()->is('stockRequest', 'stockRequest/edit/*')) ? 'active' : '' }}" href="/stockRequest">Index</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ (request()->is('stockRequest/create')) ? 'active' : '' }}" href="/stockRequest/create">Create</a>
    </li>
  </ul>

  @yield('view')
@endsection
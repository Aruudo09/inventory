@extends('layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Berita Acara</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group me-2">
        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
      </div>
      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
        <span data-feather="calendar" class="align-text-bottom"></span>
        This week
      </button>
    </div>
  </div>

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link {{ (request()->is('beritaAcara', 'beritaAcara/edit/*')) ? 'active' : '' }}" href="/beritaAcara">Index</a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ (request()->is('beritaAcara/create')) ? 'active' : '' }}" href="/beritaAcara/create">Create</a>
    </li>
  </ul>

  @yield('view')
@endsection
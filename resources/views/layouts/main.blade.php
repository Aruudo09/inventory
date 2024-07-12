<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Template · Bootstrap v5.2</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    

<link href="/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Trix style --}}
    <link rel="stylesheet" type="text/css" href="/css/trix.css">
    <script type="text/javascript" src="/js/trix.js"></script>
    
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
  </head>
  <body>
    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">STOCK CONTROL</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3 sidebar-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link btn text-start {{ (request()->is('dashboard')) ? 'active' : '' }}" href="/dashboard">
              <span data-feather="shopping-cart" class="align-text-bottom"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn text-start {{ (request()->is('category*')) || request()->is('item*') || request()->is('supplier*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#itemCollapse" role="button" aria-expanded="false" aria-controls="itemCollapse">
              <span data-feather="file" class="align-text-bottom"></span>
              Data Master
            </a>
            <div class="collapse" id="itemCollapse">
              <div class="card card-body">
                <a href="/category" class="text-decoration-none text-dark">Kategori</a>
                <a href="/item" class="text-decoration-none text-dark">Barang</a>
                <a href="/supplier" class="text-decoration-none text-dark">Supplier</a>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link btn text-start {{ (request()->is('stockRequest*')) || request()->is('purchaseRequest*') || request()->is('purchaseOrder*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#purchaseCollapse" role="button" aria-expanded="false" aria-controls="purchaseCollapse">
              <span data-feather="shopping-cart" class="align-text-bottom"></span>
              Pengadaan Barang
            </a>
            <div class="collapse" id="purchaseCollapse">
              <div class="card card-body">
                <a href="/stockRequest" class="text-decoration-none text-dark">Stock Request</a>
                <a href="/purchaseRequest" class="text-decoration-none text-dark">Purchase Request</a>
                <a href="/purchaseOrder" class="text-decoration-none text-dark">Purchase order</a>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link btn text-start {{ (request()->is('beritaAcara*')) ? 'active' : '' }}" data-bs-toggle="collapse" href="#penerimaanCollapse" role="button" aria-expanded="false" aria-controls="penerimaanCollapse">
              <span data-feather="layers" class="align-text-bottom"></span>
              Penerimaan Barang
            </a>
            <div class="collapse" id="penerimaanCollapse">
              <div class="card card-body">
                <a href="/beritaAcara" class="text-decoration-none text-dark">Berita Acara</a>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link btn text-start {{ (request()->is('pemakaian*')) ? 'active' : '' }}" data-bs-toggle="collapse" href="#pemakaianCollapse" role="button" aria-expanded="false" aria-controls="pemakaianCollapse">
              <span data-feather="layers" class="align-text-bottom"></span>
              Pemakaian Barang
            </a>
            <div class="collapse" id="pemakaianCollapse">
              <div class="card card-body">
                <a href="/pemakaian" class="text-decoration-none text-dark">Pemakaian</a>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link btn text-start {{ (request()->is('report*')) ? 'active' : '' }}" data-bs-toggle="collapse" href="#reportCollapse" role="button" aria-expanded="false" aria-controls="reportCollapse">
              <span data-feather="layers" class="align-text-bottom"></span>
              Reports
            </a>
            <div class="collapse" id="reportCollapse">
              <div class="card card-body">
                <a href="/report" class="text-decoration-none text-dark">Rekapitulasi</a>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link btn text-start {{ (request()->is('register*')) || request()->is('division*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#registerCollapse" role="button" aria-expanded="false" aria-controls="registerCollapse">
              <span data-feather="layers" class="align-text-bottom"></span>
              Register
            </a>
            <div class="collapse" id="registerCollapse">
              <div class="card card-body">
                <a href="/register" class="text-decoration-none text-dark">User</a>
                <a href="/division" class="text-decoration-none text-dark">Division</a>
              </div>
            </div>
          </li>
          <hr>
          <li class="nax-item">
            <a class="nav-link text-start" data-bs-toggle="collapse" href="#userCollapse" role="button" aria-expanded="false" aria-controls="userCollapse">
              <span data-feather="layers" class="align-text-bottom"></span>
              <i class="bi bi-person-circle"></i>
              {{ Auth::user()->username }}
            </a>
            <div class="collapse" id="userCollapse">
              <div class="card card-body">
                  <a href="{{ route('logout') }}" class="text-decoration-none text-dark" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Sign Out
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
              </div>
            </div>
          </li>
        </ul>

        {{-- <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
          <span>Saved reports</span>
          <a class="link-secondary" href="#" aria-label="Add a new report">
            <span data-feather="plus-circle" class="align-text-bottom"></span>
          </a>
        </h6>
        <ul class="nav flex-column mb-2">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text" class="align-text-bottom"></span>
              Current month
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text" class="align-text-bottom"></span>
              Last quarter
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text" class="align-text-bottom"></span>
              Social engagement
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span data-feather="file-text" class="align-text-bottom"></span>
              Year-end sale
            </a>
          </li>
        </ul> --}}
      </div>
    </nav>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      @yield('container')
    </main>

  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="{{ asset('js/dashboard.js') }}"></script>
      <script src="{{ asset('js/script.js') }}"></script>
      <script type="text/javascript">
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
      </script>
  </body>
</html>

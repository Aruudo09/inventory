@extends('layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Report</h1>
  </div>

<div>
    {{-- REKAPITULASI BARANG --}}
    <div class="col-3 mb-3">
        <h6>Rekapitulasi Barang :</h6>
        <form action="{{ route('item.eksport') }}" method="post">
          @csrf
          <div class="input-group">
            <select class="form-select" id="inputGroupSelect04" name="month" aria-label="Example select with button addon">
              <option selected disabled>Rekapitulasi Barang...</option>
              <option value="1">Januari</option>
              <option value="2">Febuari</option>
              <option value="3">Maret</option>
              <option value="3">April</option>
              <option value="3">Mei</option>
              <option value="3">Juni</option>
              <option value="3">Juli</option>
              <option value="3">Agustus</option>
              <option value="3">September</option>
              <option value="3">Oktober</option>
              <option value="3">November</option>
              <option value="3">Desember</option>
            </select>
            <button class="btn btn-success" type="submit">Export</button>
          </div>
        </form>
      </div>

      {{-- REKAPITULASI STOCK REQUEST --}}
      <div class="col-3 mb-3">
        <h6>Rekapitulasi SR :</h6>
        <form action="{{ route('stockRequest.eksport') }}" method="post">
          @csrf
          <div class="input-group">
            <select class="form-select" id="inputGroupSelect04" name="month" aria-label="Example select with button addon">
              <option selected disabled>Rekapitulasi SR...</option>
              <option value="1">Januari</option>
              <option value="2">Febuari</option>
              <option value="3">Maret</option>
              <option value="3">April</option>
              <option value="3">Mei</option>
              <option value="3">Juni</option>
              <option value="3">Juli</option>
              <option value="3">Agustus</option>
              <option value="3">September</option>
              <option value="3">Oktober</option>
              <option value="3">November</option>
              <option value="3">Desember</option>
            </select>
            <button class="btn btn-success" type="submit">Export</button>
          </div>
        </form>
      </div>

      {{-- REKAPITULASI PURCHASE REQUEST --}}
      <div class="col-3 mb-3">
        <h6>Rekapitulasi PR :</h6>
        <form action="{{ route('purchaseRequest.eksport') }}" method="post">
          @csrf
          <div class="input-group">
            <select class="form-select" id="inputGroupSelect04" name="month" aria-label="Example select with button addon">
              <option selected disabled>Rekapitulasi PR...</option>
              <option value="1">Januari</option>
              <option value="2">Febuari</option>
              <option value="3">Maret</option>
              <option value="3">April</option>
              <option value="3">Mei</option>
              <option value="3">Juni</option>
              <option value="3">Juli</option>
              <option value="3">Agustus</option>
              <option value="3">September</option>
              <option value="3">Oktober</option>
              <option value="3">November</option>
              <option value="3">Desember</option>
            </select>
            <button class="btn btn-success" type="submit">Export</button>
          </div>
        </form>
      </div>

      {{-- REKAPITULASI PURCHASE ORDER --}}
      <div class="col-3 mb-3">
        <h6>Rekapitulasi PO :</h6>
        <form action="{{ route('purchaseOrder.eksport') }}" method="post">
          @csrf
          <div class="input-group">
            <select class="form-select" id="inputGroupSelect04" name="month" aria-label="Example select with button addon">
              <option selected disabled>Rekapitulasi PO...</option>
              <option value="1">Januari</option>
              <option value="2">Febuari</option>
              <option value="3">Maret</option>
              <option value="3">April</option>
              <option value="3">Mei</option>
              <option value="3">Juni</option>
              <option value="3">Juli</option>
              <option value="3">Agustus</option>
              <option value="3">September</option>
              <option value="3">Oktober</option>
              <option value="3">November</option>
              <option value="3">Desember</option>
            </select>
            <button class="btn btn-success" type="submit">Export</button>
          </div>
        </form>
      </div>

      {{-- REKAPITULASI BERITA ACARA --}}
      <div class="col-3 mb-3">
        <h6>Rekapitulasi BA :</h6>
        <form action="{{ route('beritaAcara.eksport') }}" method="post">
          @csrf
          <div class="input-group">
            <select class="form-select" id="inputGroupSelect04" name="month" aria-label="Example select with button addon">
              <option selected disabled>Rekapitulasi BA...</option>
              <option value="1">Januari</option>
              <option value="2">Febuari</option>
              <option value="3">Maret</option>
              <option value="3">April</option>
              <option value="3">Mei</option>
              <option value="3">Juni</option>
              <option value="3">Juli</option>
              <option value="3">Agustus</option>
              <option value="3">September</option>
              <option value="3">Oktober</option>
              <option value="3">November</option>
              <option value="3">Desember</option>
            </select>
            <button class="btn btn-success" type="submit">Export</button>
          </div>
        </form>
      </div>

      {{-- REKAPITULASI PEMAKAIAN --}}
      <div class="col-3 mb-3">
        <h6>Rekapitulasi Pemakaian :</h6>
        <form action="{{ route('pemakaian.eksport') }}" method="post">
          @csrf
          <div class="input-group">
            <select class="form-select" id="inputGroupSelect04" name="month" aria-label="Example select with button addon">
              <option selected disabled>Rekapitulasi Pemakaian...</option>
              <option value="1">Januari</option>
              <option value="2">Febuari</option>
              <option value="3">Maret</option>
              <option value="3">April</option>
              <option value="3">Mei</option>
              <option value="3">Juni</option>
              <option value="3">Juli</option>
              <option value="3">Agustus</option>
              <option value="3">September</option>
              <option value="3">Oktober</option>
              <option value="3">November</option>
              <option value="3">Desember</option>
            </select>
            <button class="btn btn-success" type="submit">Export</button>
          </div>
        </form>
      </div>

</div>

@endsection
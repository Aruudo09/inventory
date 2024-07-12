<table>
    <thead>
        <tr>
            <th colspan="3">REKAPITULASI PEMAKAIAN BARANG</th>
        </tr>
        <tr>
            <th colspan="3">Bulan : {{ $bulan }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Kuantitas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->Pemakaian[0]->useCode }}</td>
                <td>{{ $item->pemakaian->sum('pivot.qtyUse') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
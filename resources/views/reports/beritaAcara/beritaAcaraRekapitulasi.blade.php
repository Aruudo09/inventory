<table>
    <thead>
        <tr>
            <th colspan="3">REKAPITULASI BERITA ACARA</th>
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
                <td>{{ $item->berita_acara[0]->baCode }}</td>
                <td>{{ $item->berita_acara->sum('pivot.qtyBa') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
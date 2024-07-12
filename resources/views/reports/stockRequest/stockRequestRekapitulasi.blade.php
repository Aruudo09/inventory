<table>
    <thead>
        <tr>
            <th colspan="3">REKAPITULASI STOCK REQUEST</th>
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
                <td>{{ $item->stockRequest[0]->srCode }}</td>
                <td>{{ $item->stockRequest->sum('pivot.qtySr') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
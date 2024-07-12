<table>
    <thead>
        <tr>
            <th colspan="3">REKAPITULASI PURCHASE REQUEST</th>
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
                <td>{{ $item->purchaseRequest[0]->prCode }}</td>
                <td>{{ $item->purchaseRequest->sum('pivot.qtyPr') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
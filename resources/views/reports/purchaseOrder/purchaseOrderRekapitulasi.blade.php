<table>
    <thead>
        <tr>
            <th colspan="3">REKAPITULASI PURCHASE ORDER</th>
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
                <td>{{ $item->purchaseOrder[0]->poCode }}</td>
                <td>{{ $item->purchaseOrder->sum('pivot.qtyPo') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
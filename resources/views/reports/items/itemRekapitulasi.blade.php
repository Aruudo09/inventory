<table>
    <thead>
        <tr>
            <th colspan="10">REKAPITULASI BARANG</th>
        </tr>
        <tr>
            <th colspan="10">Bulan : {{ $bulan }}</th>
        </tr>
        <tr>
            <th>No</th>
            <th>Part Number</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Satuan</th>
            <th>First Stock</th>
            <th>Stock Masuk</th>
            <th>Stock Keluar</th>
            <th>Last Stock</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->partNumber }}</td>
                <td>{{ $item->itemName }}</td>
                <td>{{ $item->category->categoryName }}</td>
                <td>{{ $item->satuan }}</td>
                <td>{{ $lastStock->where('item_id', $item->id)->first()->stockTr - $item->berita_acara->sum('pivot.qtyBa') + $item->pemakaian->sum('pivot.qtyUse') }}</td>
                <td>{{ $item->berita_acara->sum('pivot.qtyBa') }}</td>
                <td>{{ $item->pemakaian->sum('pivot.qtyUse') }}</td>
                <td>{{ $lastStock->where('item_id', $item->id)->first()->stockTr }}</td>
                <td>{{ $item->harga }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
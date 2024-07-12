<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div style="display: flex; text-align: center; margin-bottom: 50px">
        <img src="/Users/audrica/project_TA/inventory/public/assets/images/logoBMC2.jpeg" width="500px" alt="">
        <h2>STOCK REQUEST</h2>
    </div>

    <table style="table-layout: auto; margin: auto; margin-bottom: 25px;" width="667">
        <tbody>
            <tr>
                <td width="297">
                    <p>No SR : {{ $stock_requests->srCode }}</p>
                </td>
            </tr>
            <tr>
                <td width="297">
                    <p>SR Date : {{ $stock_requests->created_at }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="table-layout: auto; margin: auto; margin-bottom: 40px;" border="1px solid" width="665">
        <thead style="text-align: center">
            <tr>
                <th width="43">
                    <p><strong>NO</strong></p>
                </th>
                <th width="260">
                    <p><strong>DESCRIPTION</strong></p>
                </th>
                <th width="65">
                    <p><strong>QTY</strong></p>
                </th>
                <th width="65">
                    <p><strong>SATUAN</strong></p>
                </th>
                <th width="100">
                    <p><strong>KATEGORI</strong></p>
                </th>
            </tr>
        </thead>
        <tbody style="text-align: center">
            @foreach ($items as $item)
                <tr>
                    <td width="43">
                        <p>{{ $loop->iteration }}</p>
                    </td>
                    <td width="260">
                        <p>{{ $item->itemName }}</p>
                    </td>
                    <td width="65">
                        <p>{{ $item->stockRequest[0]->pivot->qtySr }}</p>
                    </td>
                    <td width="72">
                        <p>{{ $item->satuan }}</p>
                    </td>
                    <td>
                        <p>{{ $item->category->categoryName }}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tbody>
            <tr>
                <td style="text-align: center" colspan="2">
                    <strong>Status Pemakaian</strong>
                </td>
                <td style="text-align: center">
                    <p>{{ $stock_requests->srUsed }}</p>
                </td>
            </tr>
            <tr>
                <td style="text-align: center" colspan="2">
                    <strong>Status Permintaan</strong>
                </td>
                <td style="text-align: center">
                    @if ($stock_requests->status == 1)
                        <p>Sudah</p>
                    @else
                        <p>Belum</p>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <table style="table-layout: auto; margin: auto; text-align: center;" width="672">
        <tbody>
            <tr>
                <td width="200">
                    <p><strong>PEMBUAT</strong></p>
                </td>
                <td colspan="2" width="200">
                    <p><strong>DIPERIKSA</strong></p>
                </td>
                <td width="200">
                    <p><strong>DIKETAHUI</strong></p>
                </td>
            </tr>
            <tr>
                <td width="133">
                    <p><br><br></p>
                </td>
                <td width="133">
                    <p><br><br></p>
                </td>
                <td width="133">
                    <p><br><br></p>
                </td>
            </tr>
            <tr>
                <td width="133">
                    <p></p>
                </td>
                <td width="133">
                    <p>Group Head</p>
                </td>
                <td width="133">
                    <p>Section Head</p>
                </td>
                <td width="133">
                    <p>Dept Head</p>
                </td>
            </tr>
        </tbody>
    </table>

    <style>
        @page {
        size: A4 portrait;
        }
    </style>

</body>
</html>
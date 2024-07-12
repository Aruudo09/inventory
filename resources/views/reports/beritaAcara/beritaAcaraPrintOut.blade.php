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
        <h2>BERITA ACARA <br>PENERIMAAN BARANG</h2>
    </div>

    <table style="table-layout: auto; margin: auto; margin-bottom: 25px;" width="667">
        <tbody>
            <tr>
                <td width="370">
                    <p>No PO BMC : {{ $purchase_orders->poCode }}</p>
                </td>
                <td width="145">
                    <p>Nomor BA</p>
                </td>
                <td width="152">
                    <p>: {{ $berita_acaras->baCode }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Pada hari ini tanggal : {{ $berita_acaras->created_at }}</p>
                </td>
                <td width="145">
                    <p>No PR</p>
                </td>
                <td width="152">
                    <p>: {{ $purchase_requests->created_at }}</p>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="table-layout: auto; margin: auto; margin-bottom: 25px;" width="663">
        <tbody>
            <tr>
                <td width="93">
                    <p><strong>SUPPLIER:</strong></p>
                </td>
                <td colspan="1" width="277">
                    <p>{{ $suppliers->spName }}</p>
                </td>
            </tr>
            <tr>
                <td width="93">
                    <p>Delivery Order No</p>
                </td>
                <td width="274">
                    <p>:</p>
                </td>
            </tr>
            <tr>
                <td width="93">
                    <p>Jatuh tempo pembayaran</p>
                </td>
                <td width="274">
                    <p>:</p>
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
                <th width="72">
                    <p><strong>SATUAN</strong></p>
                </th>
            </tr>
        </thead>
        <tbody style="text-align: center">
            @foreach ($berita_acaras->item as $berita_acara)
                <tr>
                    <td width="43">
                        <p>{{ $loop->iteration }}</p>
                    </td>
                    <td width="260">
                        <p>{{ $berita_acara->itemName }}</p>
                    </td>
                    <td width="65">
                        <p>{{ $berita_acara->pivot->qtyBa }}</p>
                    </td>
                    <td width="72">
                        <p>{{ $berita_acara->pivot->satuan }}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tbody>
            <tr>
                <td style="text-align: center" colspan="2">
                    <strong>Total</strong>
                </td>
                <td style="text-align: center">
                    {{ $totals }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Catatan</strong>
                </td>
                <td colspan="2">
                    <textarea name="" id="" cols="100" rows="5">{{ trim($berita_acaras->description, '<div></div>') }}</textarea>
                </td>
            </tr>
        </tbody>
    </table>

    <table style="table-layout: auto; margin: auto; text-align: center;" width="672">
        <tbody>
            <tr>
                <td width="200">
                    <p><strong>PENERIMA</strong></p>
                </td>
                <td width="200">
                    <p><strong>DISETUJUI</strong></p>
                </td>
                <td width="200">
                    <p><strong>DISERAHKAN</strong></p>
                </td>
            </tr>
            <tr>
                <td width="133">
                    <p>&nbsp;</p>
                </td>
                <td width="133">
                    <p>&nbsp;</p>
                </td>
                <td width="133">
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td width="133">
                    <p></p>
                </td>
                <td width="133">
                    <p>Store</p>
                </td>
                <td width="133">
                    <p></p>
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
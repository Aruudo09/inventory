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
        <h2>PURCHASE ORDER</h2>
    </div>

    <table style="table-layout: auto; margin: auto; margin-bottom: 25px;" width="667">
        <tbody>
            <tr>
                <td width="370">
                    <p>Jl.Desa Harapan Kita No. 4 Kel. Harapan Jaya <br> Bekasi Utara 17124 Jawa Barat Indonesia</p>
                </td>
                <td width="145">
                    <p>No PO</p>
                </td>
                <td width="152">
                    <p>: {{ $purchase_orders->poCode }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Telp:&nbsp;<a href="tel:+6218871836">(62-21) 8871836</a></p>
                </td>
                <td width="145">
                    <p>Date</p>
                </td>
                <td width="152">
                    <p>: {{ $purchase_orders->created_at }}</p>
                </td>
            </tr>
            <tr>
                <td width="370">
                    <p>Fax: (62-21) 8878949<br /> Email:&nbsp;<a href="http://bmc.co.id/about-us/">marketingteam@bmc.co.id</a></p>
                </td>
                <td width="145">
                    <p>Payment Terms</p>
                </td>
                <td width="152">
                    <p>: {{ trim($purchase_orders->pymntTerms, '<div></div>') }}</p>
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
                <td width="145">
                    <p>No. PR</p>
                </td>
                <td width="152">
                    <p>: {{ $purchase_requests->prCode }}</p>
                </td>
            </tr>
            <tr>
                <td width="93">
                    <p>Alamat</p>
                </td>
                <td width="274">
                    <p>: {{ trim($suppliers->address, '<div>,</div>') }}</p>
                </td>
                <td width="144">
                    <p>Date</p>
                </td>
                <td width="151">
                    <p>: {{ $purchase_requests->created_at }}</p>
                </td>
            </tr>
            <tr>
                <td width="93">
                    <p>No. Telp</p>
                </td>
                <td width="274">
                    <p>: {{ $suppliers->cpNumber }}</p>
                </td>
                <td width="144">
                    <p>&nbsp;</p>
                </td>
                <td width="151">
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td width="93">
                    <p>Nama</p>
                </td>
                <td width="274">
                    <p>: {{ $suppliers->cpName }}</p>
                </td>
                <td width="144">
                    <p>&nbsp;</p>
                </td>
                <td width="151">
                    <p>&nbsp;</p>
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
                    <p><strong>SIZE</strong></p>
                </th>
                <th width="110">
                    <p><strong>UNIT PRICE</strong></p>
                </th>
                <th width="114">
                    <p><strong>AMOUNT</strong></p>
                </th>
            </tr>
        </thead>
        <tbody style="text-align: center">
            @foreach ($purchase_orders->item as $purchase_order)
                <tr>
                    <td width="43">
                        <p>{{ $loop->iteration }}</p>
                    </td>
                    <td width="260">
                        <p>{{ $purchase_order->itemName }}</p>
                    </td>
                    <td width="65">
                        <p>{{ $purchase_order->pivot->qtyPo }}</p>
                    </td>
                    <td width="72">
                        <p>{{ $purchase_order->pivot->satuan }}</p>
                    </td>
                    <td width="110">
                        <p>{{ number_format($purchase_order->pivot->harga, 0, '', '.') }}</p>
                    </td>
                    <td width="114">
                        <p>{{ number_format($purchase_order->pivot->total, 0, '', '.') }}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="table-layout: auto; margin: auto; text-align: center;" width="672">
        <tbody>
            <tr>
                <td width="200">
                    <p><strong>ACCEPTED BY</strong></p>
                </td>
                <td colspan="2" width="222">
                    <p><strong>AUTHORIZED OFFICER</strong></p>
                </td>
                <td width="183">
                    <p><strong>PROCUREMENT <br> OFFICER</strong></p>
                </td>
            </tr>
            <tr>
                <td width="133">
                    <p>&nbsp;</p>
                </td>
                <td width="113">
                    <p>&nbsp;</p>
                </td>
                <td width="110">
                    <p>&nbsp;</p>
                </td>
                <td width="183">
                    <p>&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td width="133">
                    <p>Presiden Direktur</p>
                </td>
                <td width="113">
                    <p>Direktur</p>
                </td>
                <td width="110">
                    <p>Finance Manager</p>
                </td>
                <td width="183">
                    <p>Procurement</p>
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
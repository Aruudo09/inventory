<?php

namespace App\Exports;

use App\Models\Item;
use App\Models\PurchaseOrder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Database\Eloquent\Builder;

class purchaseOrderExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    use Exportable;

    public function forMonth(int $month)
    {
        $this->month = $month;
        
        return $this;
    }
    
    public function view(): View
    {
        switch ($this->month) {
            case 1:
                $bulan = 'Januari';
                break;
            case 2:
                $bulan = 'Febuari';
                break;
            case 3:
                $bulan = 'Maret';
                break;
            case 4:
                $bulan = 'April';
                break;
            case 5:
                $bulan = 'Mei';
                break;
            case 6:
                $bulan = 'Juni';
                break;
            case 7:
                $bulan = 'Juli';
                break;
            case 8:
                $bulan = 'Agustus';
                break;
            case 9:
                $bulan = 'September';
                break;
            case 10:
                $bulan = 'Oktober';
                break;
            case 11:
                $bulan = 'November';
                break;
            case 12:
                $bulan = 'Desember';
                break;
        }

        return view('reports.purchaseOrder.purchaseOrderRekapitulasi', [
            'items' => Item::with('category')
            ->withWhereHas('purchaseOrder', function (Builder $q) {
                $q->whereMonth('purchase_order_item.created_at', $this->month);
            })
            ->get(),
            'bulan' => $bulan
        ]);

    }
}

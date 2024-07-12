<?php

namespace App\Exports;

use App\Models\Item;
use App\Models\Pemakaian;
use App\Models\StockTrack;
use App\Models\BeritaAcara;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

// class ItemExport implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         return Item::all();
//     }
// }

class ItemExport implements FromView, ShouldAutoSize
{
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

        return view('reports.items.itemRekapitulasi', [
            'items' => Item::with('category')->
            withwhereHas('berita_acara', function (Builder $q) {
                $q->whereMonth('berita_acara_item.created_at', $this->month);
            })
            ->withwhereHas('pemakaian', function (Builder $q) {
                $q->whereMonth('pemakaian_item.created_at', $this->month);
            })
            ->get(),
            'lastStock' => StockTrack::whereMonth('created_at', $this->month)->latest()->get(),
            'bulan' => $bulan
        ]);
    }
}

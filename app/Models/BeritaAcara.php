<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters) {

        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('baCode', 'like', '%' . $search . '%')
                         ->orWhereHas('supplier', function($query) use($search) {
                            $query->where('spName', 'like', '%' . $search . '%');
                         });
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function purchase_order() {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function item() {
        return $this->belongsToMany(Item::class, 'berita_acara_item', 'berita_acara_id', 'item_id')->withPivot('qtyBa', 'satuan')->withTimestamps();
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'sp_id');
    }
}

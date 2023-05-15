<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters) {

        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('poCode', 'like', '%' . $search . '%');
        });
    } 

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function purchase_request() {
        return $this->belongsTo(PurchaseRequest::class, 'pr_id');
    }

    public function berita_acara() {
        return $this->hasMany(BeritaAcara::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class, 'sp_id');
    }

    public function item() {
        return $this->belongsToMany(Item::class, 'purchase_order_item', 'purchase_order_id', 'item_id')->withPivot('qtyPo', 'satuan', 'harga', 'total')->withTimestamps();
    }
}

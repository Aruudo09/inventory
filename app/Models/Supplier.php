<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters) {

        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('spCode', 'like', '%' . $search . '%')
                         ->orWhere('spName', 'like', '%' . $search . '%')
                         ->orWhere('cpName', 'like', '%' . $search . '%')
                         ->orWhere('cpNumber', 'like', '%' . $search . '%');
        });
    }

    public function purchaseOrder() {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function beritaAcara() {
        return $this->hasMany(BeritaAcara::class);
    }

    public function item() {
        return $this->belongsToMany(Item::class, 'supplier_item', 'supplier_id', 'item_id')->withPivot('harga')->withTimestamps();
    }
}

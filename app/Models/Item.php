<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters) {
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('itemName', 'like', '%' . $search. '%')
                         ->orWhere('partNumber', 'like', '%' . $search . '%');
        });
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function supplier() {
        return $this->belongsToMany(Supplier::class, 'supplier_item', 'item_id', 'supplier_id')->withTimestamps();
    }

    public function stockRequest() {
        return $this->belongsToMany(StockRequest::class, 'stock_request_item', 'item_id', 'stock_request_id')->withPivot('qtySr')->withTimestamps();
    }

    public function purchaseRequest() {
        return $this->belongsToMany(PurchaseRequest::class, 'purchase_request_item', 'item_id', 'purchase_request_id')->withPivot('qtyPr')->withTimestamps();
    }

    public function purchaseOrder() {
        return $this->belongsToMany(Item::class, 'purchase_order_item', 'item_id', 'purchase_order_id')->withPivot('qtyPo', 'satuan', 'harga', 'total')->withTimestamps();
    }

    public function berita_acara() {
        return $this->belongsToMany(BeritaAcara::class, 'berita_acara_item', 'item_id', 'berita_acara_id')->withPivot('qtyBa', 'satuan')->withTimestamps();
    }

    public function pemakaian() {
        return $this->belongsToMany(Pemakaian::class, 'pemakaian_item', 'item_id', 'use_id')->withPivot('qtyUse')->withTimestamps();
    }
}

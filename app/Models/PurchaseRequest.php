<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters) {
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('prCode', 'like', '%' . $search . '%');
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function purchase_order() {
        return $this->hasMany(PurchaseOrder::class, 'pr_id');
    }

    public function item() {
        return $this->belongsToMany(Item::class, 'purchase_request_item', 'purchase_request_id', 'item_id')->withPivot('qtyPr')->withTimestamps();
    }

    public function stock_request() {
        return $this->belongsTo(StockRequest::class, 'sr_id');
    }
}

<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters) {

        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('srCode', 'like', '%' . $search . '%');
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function item() {
        return $this->belongsToMany(Item::class, 'stock_request_item', 'stock_request_id', 'item_id')->withPivot('qtySr')->withTimestamps();
    }

    public function purchase_request() {
        return $this->HasOne(PurchaseRequest::class);
    }
}

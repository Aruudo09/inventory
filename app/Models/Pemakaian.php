<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeFilter($query, array $filters) {

        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('useCode', 'like', '%' . $search . '%');
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function item() {
        return $this->belongsToMany(Item::class, 'pemakaian_item', 'use_id', 'item_id')->withPivot('qtyUse')->withTimestamps();
    }

}

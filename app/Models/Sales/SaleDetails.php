<?php

namespace App\Models\Sales;

use App\Models\Products\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetails extends Model
{
    use HasFactory,SoftDeletes;

    public function sales(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sale_id', 'id')->withTrashed();
    }
    public function products(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id', 'id')->withTrashed();
    }
}

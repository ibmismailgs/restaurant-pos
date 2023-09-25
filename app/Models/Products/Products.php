<?php

namespace App\Models\Products;

use App\Models\Ingredients\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory, SoftDeletes;
    
    public function units(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id')->withTrashed();
    }
}

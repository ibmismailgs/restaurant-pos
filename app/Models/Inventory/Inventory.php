<?php

namespace App\Models\Inventory;

use App\Models\Ingredients\Purchase;
use App\Models\Ingredients\Ingredients;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    public function ingredients(): BelongsTo
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id', 'id')->withTrashed();
    }
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id')->withTrashed();
    }
}

<?php

namespace App\Models\Ingredients;

use App\Models\Ingredients\Ingredients;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseDetails extends Model
{
    use HasFactory, softDeletes;

    public function ingredients(): BelongsTo
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id', 'id')->withTrashed();
    }
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id')->withTrashed();
    }
}

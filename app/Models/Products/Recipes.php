<?php

namespace App\Models\Products;

use App\Models\Ingredients\Ingredients;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipes extends Model
{
    use HasFactory, SoftDeletes;

    public function ingredients(): BelongsTo
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id', 'id')->withTrashed();
    }
    public function products(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id', 'id')->withTrashed();
    }
}

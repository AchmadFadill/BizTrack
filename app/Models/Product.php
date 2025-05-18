<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'product_category_id',
        'purchase_price',
        'selling_price',
        'unit',
        'image',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function quantities(): HasMany
    {
        return $this->hasMany(ProductQuantity::class);
    }

    // Method untuk mendapatkan nilai stok saat ini
    public function getStockValue()
    {
        return $this->getCurrentStock() * $this->purchase_price;
    }
}

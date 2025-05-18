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
        'barcode',
        'description',
        'product_category_id',
        'purchase_price',
        'selling_price',
        'alert_quantity',
        'unit',
        'image',
        'is_active',
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

    // // Method untuk mendapatkan total stok saat ini
    // public function getCurrentStock($warehouseId = null)
    // {
    //     $query = $this->quantities();
        
    //     if ($warehouseId) {
    //         $query->where('warehouse_id', $warehouseId);
    //     }
        
    //     return $query->sum('quantity');
    // }

    // Method untuk mendapatkan nilai stok saat ini
    public function getStockValue()
    {
        return $this->getCurrentStock() * $this->purchase_price;
    }
}

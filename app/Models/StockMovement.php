<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
     use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'type', 
        'quantity',
        'unit_price',
        'notes',
        'user_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

     protected static function booted()
    {
        static::created(function ($stockMovement) {
            \Log::info('StockMovement created via model hook', [
                'id' => $stockMovement->id,
                'product_id' => $stockMovement->product_id
            ]);
            
            $productQuantity = ProductQuantity::firstOrCreate(
                ['product_id' => $stockMovement->product_id],
                ['quantity' => 0]
            );
            
            $oldQuantity = $productQuantity->quantity;
            
            // Update stok berdasarkan tipe pergerakan
            switch ($stockMovement->type) {
                case 'in':
                    $productQuantity->increment('quantity', $stockMovement->quantity);
                    break;
                case 'out':
                    $productQuantity->decrement('quantity', $stockMovement->quantity);
                    break;
            }
            
            \Log::info("Product quantity updated via model hook", [
                'product_id' => $stockMovement->product_id,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $productQuantity->quantity
            ]);
        });
    }
}

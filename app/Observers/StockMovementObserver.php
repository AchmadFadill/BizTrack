<?php

namespace App\Observers;

use App\Models\StockMovement;
use App\Models\ProductQuantity;

class StockMovementObserver
{
    /**
     * Handle the StockMovement "created" event.
     */
    public function created(StockMovement $stockMovement): void
    {
        $this->updateProductQuantity($stockMovement);
    }

    /**
     * Handle the StockMovement "updated" event.
     */
    public function updated(StockMovement $stockMovement): void
    {
        // Jika quantity atau type berubah, perbarui jumlah stok
        if ($stockMovement->isDirty(['quantity', 'type'])) {
            // Kembalikan perubahan stok lama terlebih dahulu jika ada
            if ($stockMovement->getOriginal('quantity')) {
                $this->revertProductQuantity(
                    $stockMovement->product_id,
                    $stockMovement->getOriginal('type'),
                    $stockMovement->getOriginal('quantity')
                );
            }
            
            // Terapkan perubahan stok baru
            $this->updateProductQuantity($stockMovement);
        }
    }

    /**
     * Handle the StockMovement "deleted" event.
     */
    public function deleted(StockMovement $stockMovement): void
    {
        // Kembalikan perubahan stok ketika stock movement dihapus
        $this->revertProductQuantity(
            $stockMovement->product_id,
            $stockMovement->type,
            $stockMovement->quantity
        );
    }

    /**
     * Memperbarui jumlah stok produk berdasarkan pergerakan stok
     */
    private function updateProductQuantity(StockMovement $stockMovement): void
    {
        // Debugger untuk melihat input pergerakan stok
        \Log::info('Updating product quantity', [
            'product_id' => $stockMovement->product_id,
            'type' => $stockMovement->type,
            'quantity' => $stockMovement->quantity
        ]);
            
        $productQuantity = ProductQuantity::where('product_id', $stockMovement->product_id)
            ->first();
            
        if (!$productQuantity) {
            // Jika belum ada record, buat baru
            $productQuantity = new ProductQuantity([
                'product_id' => $stockMovement->product_id,
                'quantity' => 0,
            ]);
        }

        $oldQuantity = $productQuantity->quantity;
        
        // Lakukan perubahan berdasarkan tipe pergerakan
        switch ($stockMovement->type) {
            case 'in':
                $productQuantity->quantity += $stockMovement->quantity;
                break;
            case 'out':
                $productQuantity->quantity -= $stockMovement->quantity;
                break;

        }

        // Simpan perubahan
        $result = $productQuantity->save();
        
        // Debugger untuk melihat hasil perubahan
        \Log::info('Product quantity updated', [
            'product_id' => $stockMovement->product_id,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $productQuantity->quantity,
            'save_result' => $result
        ]);
    }

    /**
     * Mengembalikan perubahan jumlah stok produk
     */
    private function revertProductQuantity(int $productId, string $type, int $quantity): void
    {
        $productQuantity = ProductQuantity::where('product_id', $productId)->first();
        
        if (!$productQuantity) {
            return;
        }

        switch ($type) {
            case 'in':
                $productQuantity->quantity -= $quantity;
                break;
            case 'out':
                $productQuantity->quantity += $quantity;
                break;

        }

        $productQuantity->save();
    }
}

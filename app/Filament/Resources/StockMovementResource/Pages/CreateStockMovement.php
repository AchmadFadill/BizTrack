<?php

namespace App\Filament\Resources\StockMovementResource\Pages;

use App\Filament\Resources\StockMovementResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStockMovement extends CreateRecord
{
    protected static string $resource = StockMovementResource::class;

        protected function getRedirectUrl(): string
    {

         return $this->getResource()::getUrl('index');

    }

    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika unit_price kosong, set berdasarkan tipe pergerakan stok
        if (empty($data['unit_price'])) {
            $product = Product::find($data['product_id']);
            if ($product) {
                $data['unit_price'] = ($data['type'] === 'in') 
                    ? $product->purchase_price 
                    : $product->selling_price;
            }
        }
        
        return $data;
    }
}

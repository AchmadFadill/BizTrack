<?php

namespace App\Filament\Resources\StockMovementResource\Pages;

use App\Filament\Resources\StockMovementResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStockMovement extends EditRecord
{
    protected static string $resource = StockMovementResource::class;

    
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
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

        protected function getRedirectUrl(): string
    {

         return $this->getResource()::getUrl('index');

    }
}

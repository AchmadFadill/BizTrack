<?php

namespace App\Filament\Resources\ProductQuantityResource\Pages;

use App\Filament\Resources\ProductQuantityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductQuantity extends EditRecord
{
    protected static string $resource = ProductQuantityResource::class;

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

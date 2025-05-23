<?php

namespace App\Filament\Resources\ProductQuantityResource\Pages;

use App\Filament\Resources\ProductQuantityResource;
use App\Filament\Widgets\ProductStockWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductQuantities extends ListRecords
{
    protected static string $resource = ProductQuantityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    //     protected function getHeaderWidgets(): array
    // {
    //     return [
    //         ProductStockWidget::class,
    //     ];
    // }
}

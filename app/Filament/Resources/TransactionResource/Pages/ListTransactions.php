<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('print_report') // Nama action diubah
                ->label('Cetak Laporan')
                ->icon('heroicon-o-printer')
                ->url(route('laporan.keuangan')), // Mengarah ke route yang dibuat
        ];
    }

    public function getTableQuery(): Builder
    {
        return parent::getTableQuery();
    }
}
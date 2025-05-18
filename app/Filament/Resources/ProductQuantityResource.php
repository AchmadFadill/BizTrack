<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductQuantityResource\Pages;
use App\Filament\Resources\ProductQuantityResource\RelationManagers;
use App\Models\ProductQuantity;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\BelongsToColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class ProductQuantityResource extends Resource
{
    protected static ?string $model = ProductQuantity::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Inventory Management';

    protected static ?string $navigationLabel = 'Stok Produk';
    
    protected static ?int $navigationSort = 3;

    // public static function form(Forms\Form $form): Forms\Form
    // {
    //     return $form
    //         ->schema([
    //             Select::make('product_id')
    //                 ->label('Produk')
    //                 ->relationship('product', 'name')
    //                 ->required()
    //                 ->searchable(),
    //             TextInput::make('quantity')
    //                 ->label('Jumlah')
    //                 ->numeric()
    //                 ->required(),
    //         ]);
    // }

    // public static function table(Tables\Table $table): Tables\Table
    // {
    //     return $table
    //         ->columns([
    //             TextColumn::make('product.name')
    //                 ->label('Nama Produk')
    //                 ->searchable()
    //                 ->sortable(),
    //             TextColumn::make('quantity')
    //                 ->label('Jumlah')
    //                 ->sortable(),
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\BulkActionGroup::make([
    //                 Tables\Actions\DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }

    // public static function getRelations(): array
    // {
    //     return [
    //         //
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductQuantities::route('/'),
            // 'create' => Pages\CreateProductQuantity::route('/create'),
            // 'edit' => Pages\EditProductQuantity::route('/{record}/edit'),
        ];
    }
}
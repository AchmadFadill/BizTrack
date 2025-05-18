<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductQuantityResource\Pages;
use App\Filament\Resources\ProductQuantityResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductQuantity;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Illuminate\Database\Eloquent\Builder;
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

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable(),
                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                ->default(0)
                    ->label('Stok Tersedia')
                    ->formatStateUsing(fn ($state) => $state ?? 0)
                    ->sortable()
                    ->color(function ($state) {
                        if ($state <= 0) return 'danger'; // Merah untuk stok kosong
                        if ($state <= 10) return 'warning'; // Kuning untuk stok rendah
                        return 'success'; // Hijau untuk stok normal
                    }),
            Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->date('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
                    
            ])
            ->filters([
            Tables\Filters\SelectFilter::make('stock_status')
                    ->label('Status Stok')
                    ->options([
                        'out_of_stock' => 'Stok Habis',
                        'low_stock' => 'Stok Rendah',
                        'in_stock' => 'Tersedia',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value']) {
                            'out_of_stock' => $query->where('product_quantities.quantity', '<=', 0),
                            'low_stock' => $query->whereBetween('product_quantities.quantity', [1, 10]),
                            'in_stock' => $query->where('product_quantities.quantity', '>', 10),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tambahkan tombol untuk menambah stok
                Tables\Actions\Action::make('add_stock')
                    ->label('Tambah Stok')
                    ->url(fn () => route('filament.admin.resources.stock-movements.create', ))
                    ->icon('heroicon-o-plus')
                    ->color('success'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
                
            ;
            
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductQuantities::route('/'),
            // 'create' => Pages\CreateProductQuantity::route('/create'),
            // 'edit' => Pages\EditProductQuantity::route('/{record}/edit'),
        ];
    }
}
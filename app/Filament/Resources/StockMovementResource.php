<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Filament\Resources\StockMovementResource\RelationManagers;
use App\Models\StockMovement;
use Filament\Forms;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Columns\BelongsToColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Inventory Management';

    
    protected static ?int $navigationSort = 5;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable(),
                BelongsToSelect::make('supplier_id')
                    ->label('Supplier')
                    ->relationship('supplier', 'name')
                    ->searchable(),
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        // purchase', 'sale', 'return', 'adjustment', 'transfer
                        'purchase' => 'Pembelian',
                        'sale' => 'Penjualan', 
                        'return' => 'Pengembalian', 
                        'adjustment' => 'Penyesuaian', 
                        'transfer' => 'Transfer', 
                    ])
                    ->required(),
                TextInput::make('quantity')
                    ->label('Kuantitas')
                    ->numeric()
                    ->required(),
                TextInput::make('unit_price')
                    ->label('Harga Satuan')
                    ->numeric(),
                Textarea::make('notes')
                    ->label('Catatan'),
                BelongsToSelect::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    // ->disabled() // Biasanya diisi otomatis oleh sistem
                    ->default(auth()->id())
                    ->searchable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'purchase' => 'Pembelian',
                        'sale' => 'Penjualan', 
                        'return' => 'Pengembalian', 
                        'adjustment' => 'Penyesuaian', 
                        'transfer' => 'Transfer', 
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Kuantitas')
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Harga Satuan')
                    ->money('IDR') // Sesuaikan dengan mata uang Anda
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->wrap(),
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i:s', timezone: 'Asia/Jakarta')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'purchase' => 'Pembelian',
                        'sale' => 'Penjualan', 
                        'return' => 'Pengembalian', 
                        'adjustment' => 'Penyesuaian', 
                        'transfer' => 'Transfer', 
                    ]),
                Tables\Filters\SelectFilter::make('product')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('supplier')
                    ->label('Supplier')
                    ->relationship('supplier', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('user')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
            'edit' => Pages\EditStockMovement::route('/{record}/edit'),
        ];
    }
}
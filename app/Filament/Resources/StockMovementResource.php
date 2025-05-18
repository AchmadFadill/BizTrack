<?php
namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Inventory Management';

    protected static ?string $navigationLabel = 'Stok Keluar dan Masuk';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->options(Product::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                    
                Forms\Components\Select::make('supplier_id')
                    ->label('Pemasok')
                    ->options(Supplier::pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                    
                Forms\Components\Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'purchase' => 'Pembelian',
                        'sale' => 'Penjualan',
                        'return' => 'Pengembalian',
                        'adjustment' => 'Penyesuaian',
                        'transfer' => 'Transfer',
                    ])
                    ->default('purchase')
                    ->required(),
                    
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('unit_price')
                    ->label('Harga Per Unit')
                    ->required()
                    ->numeric()
                    ->nullable(),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->nullable(),
                Forms\Components\Hidden::make('user_id')
                    ->default(function () {
                        return Auth::id();
                    }),
                ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Pemasok')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'purchase' => 'Pembelian',
                        'sale' => 'Penjualan',
                        'return' => 'Pengembalian',
                        'adjustment' => 'Penyesuaian',
                        'transfer' => 'Transfer',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'purchase',
                        'danger' => 'sale',
                        'info' => 'return',
                        'warning' => 'adjustment',
                        'secondary' => 'transfer',
                    ])
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Harga Per Unit')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Petugas')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->sortable()
                    ->toggleable(),
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
                    
                Tables\Filters\SelectFilter::make('product_id')
                    ->label('Produk')
                    ->options(Product::pluck('name', 'id'))
                    ->searchable(),
                    
                // Tables\Filters\DatePicker::make('created_at')
                //     ->label('Tanggal'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

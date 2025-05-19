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
use Closure;

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
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Produk')
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($state) {
                                    $product = Product::find($state);
                                    $type = $get('type');
                                    
                                    if ($product && $type) {
                                        $price = ($type === 'in') 
                                            ? $product->purchase_price 
                                            : $product->selling_price;
                                        $set('unit_price', $price);
                                        
                                        // Calculate total when product changes
                                        $quantity = $get('quantity');
                                        if ($quantity) {
                                            $set('total_price', $price * $quantity);
                                        }
                                    }
                                }
                            }),

                        Forms\Components\Select::make('supplier_id')
                            ->label('Pemasok')
                            ->options(Supplier::pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'in'=> 'Masuk',
                                'out'=> 'Keluar'
                            ])
                            ->default('in')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $productId = $get('product_id');
                                if ($productId && $state) {
                                    $product = Product::find($productId);
                                    if ($product) {
                                        $price = ($state === 'in') 
                                            ? $product->purchase_price 
                                            : $product->selling_price;
                                        $set('unit_price', $price);
                                        
                                        // Calculate total when type changes
                                        $quantity = $get('quantity');
                                        if ($quantity) {
                                            $set('total_price', $price * $quantity);
                                        }
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $unitPrice = $get('unit_price');
                                if ($state && $unitPrice) {
                                    $set('total_price', $unitPrice * $state);
                                }
                            }),

                        Forms\Components\TextInput::make('unit_price')
                            ->label('Harga Per Unit')
                            ->helperText('Harga Akan Terisi Otomatis')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                $quantity = $get('quantity');
                                if ($state && $quantity) {
                                    $set('total_price', $state * $quantity);
                                }
                            }),
                            
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Harga')
                            ->helperText('Total Harga Akan Terisi Otomatis')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                            
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->nullable(),
                    ])->columns(2),
                
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
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Pemasok')
                    ->searchable()
                    ->toggleable(),
                    
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in'=> 'Masuk',
                        'out'=> 'Keluar',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'in',
                        'info' => 'out',
                    ]),
                    
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Harga Per Unit')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->getStateUsing(function (StockMovement $record): float {
                        return $record->quantity * $record->unit_price;
                    }),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Petugas')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'in'=> 'Masuk',
                        'out'=> 'Keluar'
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
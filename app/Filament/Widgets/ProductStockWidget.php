<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ProductStockWidget extends BaseWidget
{
    // Prioritas widget di dashboard (opsional)
    protected static ?int $sort = 2;
    
    // Widget akan menggunakan lebar penuh
    protected int|string|array $columnSpan = 'full';
    
    // Judul widget (opsional)
    protected static ?string $heading = 'Stok Produk';


    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Buat query khusus untuk mendapatkan produk dan stoknya
                Product::query()
                    ->leftJoin('product_quantities', 'products.id', '=', 'product_quantities.product_id')
                    ->select('products.*', 'product_quantities.quantity as stock_quantity')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),

                    
                Tables\Columns\TextColumn::make('stock_quantity')
                ->default(0)
                    ->label('Stok Tersedia')
                    ->formatStateUsing(fn ($state) => $state ?? 0)
                    ->sortable()
                    ->color(function ($state) {
                        if ($state <= 0) return 'danger'; // Merah untuk stok kosong
                        if ($state <= 10) return 'warning'; // Kuning untuk stok rendah
                        return 'success'; // Hijau untuk stok normal
                    }),
                    
                // Tambahkan tombol untuk melihat detail stok (opsional)

            ])
            ->filters([
                // Tambahkan filter untuk melihat produk dengan stok rendah

                    
                // Filter berdasarkan kategori (sesuaikan dengan struktur tabel Anda)
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([

            ])
            ->bulkActions([
                // Opsional: Tambahkan bulk action jika diperlukan
            ])
            ->defaultSort('stock_quantity', 'asc'); // Urutkan berdasarkan stok terendah
    }
}
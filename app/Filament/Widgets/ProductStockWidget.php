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
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->date('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                // Tambahkan filter untuk melihat produk dengan stok rendah
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
                    
                // Filter berdasarkan kategori (sesuaikan dengan struktur tabel Anda)
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                // Tambahkan tombol untuk melihat detail produk
                Tables\Actions\Action::make('view_stock_history')
                    ->label('Riwayat Stok')
                    ->url(fn (Product $record) => route('filament.admin.resources.stock-movements.index', [
                        'tableFilters[product_id][value]' => $record->id
                    ]))
                    ->icon('heroicon-o-arrow-path')
                    ->color('info'),
                    
                // Tambahkan tombol untuk menambah stok
                Tables\Actions\Action::make('add_stock')
                    ->label('Tambah Stok')
                    ->url(fn (Product $record) => route('filament.admin.resources.stock-movements.create', [
                        'product_id' => $record->id,
                        'type' => 'purchase'
                    ]))
                    ->icon('heroicon-o-plus')
                    ->color('success'),
            ])
            ->bulkActions([
                // Opsional: Tambahkan bulk action jika diperlukan
            ])
            ->defaultSort('stock_quantity', 'asc'); // Urutkan berdasarkan stok terendah
    }
}
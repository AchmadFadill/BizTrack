<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter; // Import the Filter class
use Carbon\Carbon; // Import Carbon for date manipulation

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Finance Management';

        protected static ?string $navigationLabel = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('date_transaction')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('note')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('category.image')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->description(fn (Transaction $record): string => $record->name)
                    ->label('Transaction')
                    ->searchable(),
                Tables\Columns\IconColumn::make('category.is_expense')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-down-circle')
                    ->falseIcon('heroicon-o-arrow-up-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('date_transaction')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
                Tables\Columns\TextColumn::make('saldo') // Kolom Saldo
                    ->label('Saldo')
                    ->getStateUsing(function (Transaction $record, $livewire) {
                        $transactions = \App\Models\Transaction::orderBy('date_transaction')->get();
                        $saldo = 0;
                        foreach ($transactions as $transaction) {
                            if ($transaction->is($record)) {
                                break;
                            }
                            if ($transaction->category->is_expense) {
                                $saldo -= $transaction->amount;
                            } else {
                                $saldo += $transaction->amount;
                            }
                        }
                        if ($record->category->is_expense) {
                            $saldo -= $record->amount;
                        } else {
                            $saldo += $record->amount;
                        }
                        return 'Rp ' . number_format($saldo, 0, ',', '.');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Date Range Filter
                Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('to')
                            ->label('To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['from']) {
                            $query->where('date_transaction', '>=', $data['from']);
                        }
                        if ($data['to']) {
                            $query->where('date_transaction', '<=', $data['to']);
                        }
                        return $query;
                    }),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}

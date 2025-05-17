<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('startDate')
                    ->label('Tanggal Mulai'),
                DatePicker::make('endDate')
                    ->label('Tanggal Akhir'),
            ]);
    }

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? Transaction::min('date_transaction') ?? now()->startOfYear();
        $endDate = $this->filters['endDate'] ?? now();

        // Ensure $startDate and $endDate are Carbon instances
        if (!$startDate instanceof Carbon) {
            $startDate = Carbon::parse($startDate);
        }
        if (!$endDate instanceof Carbon) {
            $endDate = Carbon::parse($endDate);
        }

        $pemasukanSaatIni = Transaction::incomes()
            ->whereBetween('date_transaction', [$startDate, $endDate])
            ->sum('amount');

        $pengeluaranSaatIni = Transaction::expenses()
            ->whereBetween('date_transaction', [$startDate, $endDate])
            ->sum('amount');

        // Hitung periode sebelumnya
        $diffInDays = $startDate->diffInDays($endDate) + 1;
        $startDateSebelumnya = $startDate->copy()->subDays($diffInDays);
        $endDateSebelumnya = $endDate->copy()->subDays($diffInDays);

        $pemasukanSebelumnya = Transaction::incomes()
            ->whereBetween('date_transaction', [$startDateSebelumnya, $endDateSebelumnya])
            ->sum('amount');

        $pengeluaranSebelumnya = Transaction::expenses()
            ->whereBetween('date_transaction', [$startDateSebelumnya, $endDateSebelumnya])
            ->sum('amount');

        // Fungsi untuk menghitung persentase perubahan
        $hitungPersentase = function ($nilaiSaatIni, $nilaiSebelumnya) {
            if ($nilaiSebelumnya == 0) {
                return $nilaiSaatIni > 0 ? 100 : 0;
            }
            return (($nilaiSaatIni - $nilaiSebelumnya) / $nilaiSebelumnya) * 100;
        };

        // Income Stat
        $persentasePemasukan = $hitungPersentase($pemasukanSaatIni, $pemasukanSebelumnya);
        $warnaPemasukan = $persentasePemasukan >= 0 ? 'success' : 'danger';
        $ikonPemasukan = $persentasePemasukan >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $deskripsiPemasukan = round($persentasePemasukan) . '% ' . ($persentasePemasukan >= 0 ? 'increase' : 'decrease') . ' from previous period';

        // Expenses Stat
        $persentasePengeluaran = $hitungPersentase($pengeluaranSaatIni, $pengeluaranSebelumnya);
        $warnaPengeluaran = $persentasePengeluaran <= 0 ? 'success' : 'danger';
        $ikonPengeluaran = $persentasePengeluaran <= 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-arrow-trending-up';
        $deskripsiPengeluaran = round(abs($persentasePengeluaran)) . '% ' . ($persentasePengeluaran <= 0 ? 'decrease' : 'increase') . ' from previous period';

        // Balance Stat
        $saldoSaatIni = $pemasukanSaatIni - $pengeluaranSaatIni;
        $saldoSebelumnya = $pemasukanSebelumnya - $pengeluaranSebelumnya;
        $persentaseSaldo = $hitungPersentase($saldoSaatIni, $saldoSebelumnya);
        $warnaSaldo = $persentaseSaldo >= 0 ? 'success' : 'danger';
        $ikonSaldo = $persentaseSaldo >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $deskripsiSaldo = round($persentaseSaldo) . '% ' . ($persentaseSaldo >= 0 ? 'increase' : 'decrease') . ' from previous period';

        return [
            Stat::make('Income', 'Rp ' . number_format($pemasukanSaatIni, 0, ',', '.'))
                ->description($deskripsiPemasukan)
                ->descriptionIcon($ikonPemasukan)
                ->color($warnaPemasukan),
            Stat::make('Expenses', 'Rp ' . number_format($pengeluaranSaatIni, 0, ',', '.'))
                ->description($deskripsiPengeluaran)
                ->descriptionIcon($ikonPengeluaran)
                ->color($warnaPengeluaran),
            Stat::make('Balance', 'Rp ' . number_format($saldoSaatIni, 0, ',', '.'))
                ->description($deskripsiSaldo)
                ->descriptionIcon($ikonSaldo)
                ->color($warnaSaldo),
        ];
    }
}

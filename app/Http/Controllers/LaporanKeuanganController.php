<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        // Simpan filter ke session
        Session::put('print_from', $request->tanggal_mulai);
        Session::put('print_to', $request->tanggal_akhir);

        // Ambil data transaksi dengan filter
        $transactions = Transaction::with('category')
            ->when($request->tanggal_mulai, function ($query) use ($request) {
                $query->where('date_transaction', '>=', $request->tanggal_mulai);
            })
            ->when($request->tanggal_akhir, function ($query) use ($request) {
                $query->where('date_transaction', '<=', $request->tanggal_akhir);
            })
            ->orderBy('date_transaction')
            ->get();

        // Hitung saldo berjalan
        $saldo = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->category->is_expense) {
                $saldo -= $transaction->amount;
            } else {
                $saldo += $transaction->amount;
            }
            $transaction->saldo = $saldo;
        }

        return view('laporan_keuangan', compact('transactions'));
    }

    public function print()
    {
        $from = Session::get('print_from');
        $to = Session::get('print_to');

        // Pastikan filter tanggal diterapkan dengan benar
        $transactions = Transaction::with('category')
            ->when($from, function (Builder $query) use ($from) {
                $query->where('date_transaction', '>=', $from);
            })
            ->when($to, function (Builder $query) use ($to) {
                $query->where('date_transaction', '<=', $to);
            })
            ->orderBy('date_transaction')
            ->get();

        // Hitung saldo berjalan
        $saldo = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->category->is_expense) {
                $saldo -= $transaction->amount;
            } else {
                $saldo += $transaction->amount;
            }
            $transaction->saldo = $saldo;
        }

        $data['transactions'] = $transactions;
        $data['from'] = $from;
        $data['to'] = $to;

        return view('print.transactions', $data);
    }
}

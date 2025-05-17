<style>
    /* --- GENERAL LAYOUT --- */
    .laporan-container{
        font-family:"Times New Roman",serif;
        font-size:14px;
        color:#000;
        width:80%;
        margin:0 auto;
        padding:56px 48px 64px;        /* ruang tepi seperti kertas surat */
        background:#fff;
    }

    /* --- LETTER-HEAD --- */
    .kop-laporan{
        text-align:center;
        margin-bottom:36px;
    }
    .kop-laporan h1{
        font-size:22px;
        letter-spacing:1px;
        margin:0;
        text-transform:uppercase;
    }
    .kop-laporan p{
        margin:4px 0 0;
        font-size:13px;
    }
    .garis-kop{
        height:2px;
        background:#000;
        margin:16px 0 32px;
    }

    /* --- SECTION TITLE --- */
    h2{
        font-size:17px;
        margin-bottom:8px;
    }

    /* --- TABLE --- */
    .table-laporan{
        width:100%;
        border-collapse:collapse;
        margin-top:12px;
    }
    .table-laporan th,
    .table-laporan td{
        border:1px solid #999;
        padding:6px 8px;
    }
    .table-laporan th{
        background:#f5f5f5;
        text-align:center;
        font-weight:600;
    }
    .table-laporan td:last-child{
        width:22%;
    }

    /* --- NUMBER ALIGNMENT --- */
    .align-right{
        text-align:right;
    }

    /* --- FOOTER (opsional) --- */
    .footer-tanda-tangan{
        width:100%;
        margin-top:48px;
    }
    .footer-tanda-tangan td{
        width:50%;
        vertical-align:top;
        padding-top:32px;
        text-align:center;
    }
</style>

<div class="laporan-container">
    <!-- KOP SURAT / HEADER -->
    <div class="kop-laporan">
        <h1>LAPORAN KEUANGAN</h1>
        <p>PT Contoh Jaya Sentosa<br>Jl. Merdeka No. 123 – Jakarta</p>
    </div>
    <div class="garis-kop"></div>

    <!-- TABEL TRANSAKSI -->
    <h2>Data Transaksi</h2>
    <table class="table-laporan">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Nama Transaksi</th>
                <th>Catatan</th>
                <th>Jumlah</th>
                <th>Saldo</th>
               
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->date_transaction }}</td>
                <td>{{ $transaction->category->name }}</td>
                <td>{{ $transaction->name }}</td>
                <td>{{ $transaction->note }}</td>
                <td class="align-right">{{ number_format($transaction->amount,0,',','.') }}</td>
                <td class="align-right">{{ number_format($transaction->saldo,0,',','.') }}</td>
               
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER / TANDA TANGAN -->
    <table class="footer-tanda-tangan">
        <tr>
            <td>
                Mengetahui,<br>
                Direktur Keuangan<br><br><br><br>
                <u>(__________________)</u>
            </td>
            <td>
                Jakarta, {{ \Carbon\Carbon::now()->format('d F Y') }}<br>
                Pembuat Laporan<br><br><br><br>
                <u>(__________________)</u>
            </td>
        </tr>
    </table>
</div>

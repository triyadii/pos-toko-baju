<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Struk - {{ $trx->nomor_transaksi }}</title>
    <style>
        @page {
            margin: 0;
            size: 58mm auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 5px;
            width: 58mm;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .w-100 { width: 100%; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 2px 0; font-size: 11px; }
        .print-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            background: #009EF7;
            color: white;
            border: none;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 14px;
        }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Print Struk (Cetak)</button>

    <div class="text-center font-bold mb-1">POS TOKO BAJU</div>
    <div class="text-center mb-2" style="font-size: 10px;">Jl. Raya Toko Baju No. 123<br>Telp: 08123456789</div>
    
    <div class="line"></div>
    <table class="w-100 mt-1 mb-1">
        <tr>
            <td class="text-left">No: {{ $trx->nomor_transaksi }}</td>
        </tr>
        <tr>
            <td class="text-left">Tgl: {{ $trx->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="text-left">Ksr: {{ $trx->user->nama ?? '-' }}</td>
        </tr>
    </table>
    <div class="line"></div>

    <table class="w-100 mt-1 mb-1">
        @foreach($trx->details as $item)
        <tr>
            <td colspan="3" class="text-left font-bold" style="padding-bottom: 2px;">
                {{ $item->barangVarian->barang->nama_barang ?? '-' }}
                ({{ $item->barangVarian->warna->nama_warna ?? '-' }}, {{ $item->barangVarian->ukuran->nama_ukuran ?? '-' }})
            </td>
        </tr>
        <tr>
            <td class="text-left">{{ $item->qty }} x</td>
            <td class="text-left">{{ number_format($item->harga, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    <div class="line"></div>

    <table class="w-100 mt-1 mb-1 font-bold">
        <tr>
            <td class="text-left">Subtotal:</td>
            <td class="text-right">Rp {{ number_format($trx->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($trx->diskon > 0)
        <tr>
            <td class="text-left">Diskon:</td>
            <td class="text-right">- Rp {{ number_format($trx->diskon, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="text-left" style="font-size: 13px;">Total:</td>
            <td class="text-right" style="font-size: 13px;">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-left mt-1">Tunai:</td>
            <td class="text-right mt-1">Rp {{ number_format($trx->bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-left">Kembali:</td>
            <td class="text-right">Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>
    <div class="line"></div>
    
    <div class="text-center mt-2 mb-1">
        Terima Kasih<br>Barang yang sudah dibeli<br>tidak dapat dikembalikan.
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

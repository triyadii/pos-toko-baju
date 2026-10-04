<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\JenisBarang;
use App\Models\StatusBarang;
use App\Models\Warna;
use App\Models\Ukuran;
use App\Models\Barang;
use App\Models\BarangVarian;
use App\Models\Stok;
use App\Models\StokLog;
use App\Models\User;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('username', 'admin')->first();
        $adminId = $adminUser ? $adminUser->id : 1;

        // 1. Jenis Barang
        $jenisBarangs = [
            ['nama_jenis_barang' => 'Kaos', 'status' => 1],
            ['nama_jenis_barang' => 'Kemeja', 'status' => 1],
            ['nama_jenis_barang' => 'Celana Jeans', 'status' => 1],
            ['nama_jenis_barang' => 'Jaket', 'status' => 1],
            ['nama_jenis_barang' => 'Topi', 'status' => 1],
        ];

        foreach ($jenisBarangs as $jb) {
            JenisBarang::create(array_merge(['uuid' => Str::uuid()], $jb));
        }

        // 2. Status Barang
        $statusBarangs = [
            ['nama_status_barang' => 'Tersedia'],
            ['nama_status_barang' => 'Habis'],
            ['nama_status_barang' => 'Pre-order'],
            ['nama_status_barang' => 'Discontinue'],
        ];

        foreach ($statusBarangs as $sb) {
            StatusBarang::create(array_merge(['uuid' => Str::uuid()], $sb));
        }

        // 3. Warna
        $warnas = [
            ['nama_warna' => 'Merah', 'kode_warna' => '#FF0000', 'status' => 1],
            ['nama_warna' => 'Biru', 'kode_warna' => '#0000FF', 'status' => 1],
            ['nama_warna' => 'Hitam', 'kode_warna' => '#000000', 'status' => 1],
            ['nama_warna' => 'Putih', 'kode_warna' => '#FFFFFF', 'status' => 1],
            ['nama_warna' => 'Abu-abu', 'kode_warna' => '#808080', 'status' => 1],
            ['nama_warna' => 'Navy', 'kode_warna' => '#000080', 'status' => 1],
        ];

        foreach ($warnas as $w) {
            Warna::create(array_merge(['uuid' => Str::uuid()], $w));
        }

        // 4. Ukuran
        $ukurans = [
            ['nama_ukuran' => 'S', 'urutan' => 1, 'status' => 1],
            ['nama_ukuran' => 'M', 'urutan' => 2, 'status' => 1],
            ['nama_ukuran' => 'L', 'urutan' => 3, 'status' => 1],
            ['nama_ukuran' => 'XL', 'urutan' => 4, 'status' => 1],
            ['nama_ukuran' => 'XXL', 'urutan' => 5, 'status' => 1],
            ['nama_ukuran' => 'All Size', 'urutan' => 0, 'status' => 1],
        ];

        foreach ($ukurans as $u) {
            Ukuran::create(array_merge(['uuid' => Str::uuid()], $u));
        }

        // 5. Barang
        $jenisKaos = JenisBarang::where('nama_jenis_barang', 'Kaos')->first();
        $jenisKemeja = JenisBarang::where('nama_jenis_barang', 'Kemeja')->first();
        $jenisCelana = JenisBarang::where('nama_jenis_barang', 'Celana Jeans')->first();
        $statusTersedia = StatusBarang::where('nama_status_barang', 'Tersedia')->first();

        $barang1 = Barang::create([
            'uuid' => Str::uuid(),
            'jenis_barang_id' => $jenisKaos->id,
            'status_barang_id' => $statusTersedia->id,
            'kode_barang' => 'BRG-001',
            'nama_barang' => 'Kaos Polos Premium Basic',
            'harga_beli' => 30000,
            'harga_jual' => 55000,
        ]);

        $barang2 = Barang::create([
            'uuid' => Str::uuid(),
            'jenis_barang_id' => $jenisKemeja->id,
            'status_barang_id' => $statusTersedia->id,
            'kode_barang' => 'BRG-002',
            'nama_barang' => 'Kemeja Flannel Kotak',
            'harga_beli' => 85000,
            'harga_jual' => 150000,
        ]);

        $barang3 = Barang::create([
            'uuid' => Str::uuid(),
            'jenis_barang_id' => $jenisCelana->id,
            'status_barang_id' => $statusTersedia->id,
            'kode_barang' => 'BRG-003',
            'nama_barang' => 'Celana Jeans Slimfit',
            'harga_beli' => 120000,
            'harga_jual' => 220000,
        ]);

        // 6. Barang Varian & Manajemen Stok Log Flow
        $warnaHitam = Warna::where('nama_warna', 'Hitam')->first();
        $warnaPutih = Warna::where('nama_warna', 'Putih')->first();
        $warnaNavy = Warna::where('nama_warna', 'Navy')->first();
        
        $ukuranM = Ukuran::where('nama_ukuran', 'M')->first();
        $ukuranL = Ukuran::where('nama_ukuran', 'L')->first();
        $ukuranXL = Ukuran::where('nama_ukuran', 'XL')->first();

        // Fungsi Helper untuk membuat Varian & Stok Masuk Awal
        $createVarianAndStock = function ($barang, $warna, $ukuran, $skuSuffix, $hargaJualExtra, $qtyStockMasuk) use ($adminId) {
            $varian = BarangVarian::create([
                'uuid' => Str::uuid(),
                'barang_id' => $barang->id,
                'warna_id' => $warna->id,
                'ukuran_id' => $ukuran->id,
                'sku' => $barang->kode_barang . '-' . $skuSuffix,
                'barcode' => rand(1000000000, 9999999999),
                'harga_beli' => $barang->harga_beli,
                'harga_jual' => $barang->harga_jual + $hargaJualExtra,
                'status' => 1,
            ]);

            // Simulasi Stok Masuk Awal (Initial Stock)
            $stok = Stok::create([
                'uuid' => Str::uuid(),
                'barang_varian_id' => $varian->id,
                'jumlah_stok' => $qtyStockMasuk
            ]);

            StokLog::create([
                'uuid' => Str::uuid(),
                'barang_varian_id' => $varian->id,
                'tipe' => 'masuk',
                'jumlah' => $qtyStockMasuk,
                'stok_sebelum' => 0,
                'stok_sesudah' => $qtyStockMasuk,
                'referensi_id' => 'INIT-STOCK',
                'keterangan' => 'Stok awal dari sistem',
                'user_id' => $adminId,
            ]);

            return $varian;
        };

        // Buat beberapa varian untuk Barang 1 (Kaos)
        $varian1 = $createVarianAndStock($barang1, $warnaHitam, $ukuranM, 'HTM-M', 0, 50);
        $varian2 = $createVarianAndStock($barang1, $warnaHitam, $ukuranL, 'HTM-L', 0, 45);
        $varian3 = $createVarianAndStock($barang1, $warnaPutih, $ukuranL, 'PTH-L', 0, 30);
        $varian4 = $createVarianAndStock($barang1, $warnaPutih, $ukuranXL, 'PTH-XL', 5000, 20); // XL lebih mahal sedikit

        // Buat varian untuk Barang 2 (Kemeja)
        $varian5 = $createVarianAndStock($barang2, $warnaNavy, $ukuranM, 'NVY-M', 0, 15);
        $varian6 = $createVarianAndStock($barang2, $warnaNavy, $ukuranL, 'NVY-L', 0, 20);

        // Buat varian untuk Barang 3 (Celana)
        $varian7 = $createVarianAndStock($barang3, $warnaHitam, $ukuranM, 'HTM-M', 0, 10);
        $varian8 = $createVarianAndStock($barang3, $warnaHitam, $ukuranL, 'HTM-L', 0, 12);

        // 7. Simulasi Transaksi Stok Keluar & Penyesuaian (Membuat Log)
        // Simulasi Penjualan (Stok Keluar) untuk Varian 1
        $stokV1 = Stok::where('barang_varian_id', $varian1->id)->first();
        $qtyKeluar = 3;
        
        StokLog::create([
            'uuid' => Str::uuid(),
            'barang_varian_id' => $varian1->id,
            'tipe' => 'keluar',
            'jumlah' => $qtyKeluar,
            'stok_sebelum' => $stokV1->jumlah_stok,
            'stok_sesudah' => $stokV1->jumlah_stok - $qtyKeluar,
            'referensi_id' => 'INV-20231001-001',
            'keterangan' => 'Penjualan ke pelanggan (Kasir)',
            'user_id' => $adminId,
        ]);
        $stokV1->update(['jumlah_stok' => $stokV1->jumlah_stok - $qtyKeluar]);

        // Simulasi Penyesuaian (Stok Rusak) untuk Varian 5
        $stokV5 = Stok::where('barang_varian_id', $varian5->id)->first();
        $qtyRusak = 1;

        StokLog::create([
            'uuid' => Str::uuid(),
            'barang_varian_id' => $varian5->id,
            'tipe' => 'keluar',
            'jumlah' => $qtyRusak,
            'stok_sebelum' => $stokV5->jumlah_stok,
            'stok_sesudah' => $stokV5->jumlah_stok - $qtyRusak,
            'referensi_id' => 'ADJ-20231002',
            'keterangan' => 'Barang rusak/cacat pabrik saat QC',
            'user_id' => $adminId,
        ]);
        $stokV5->update(['jumlah_stok' => $stokV5->jumlah_stok - $qtyRusak]);
    }
}

@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Dashboard</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">Ringkasan Aktivitas Toko</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            @if(auth()->user()->role->nama_role == 'superadmin')
                <!-- Widgets Row -->
                <div class="row g-5 gx-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-4">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-100 mb-5 mb-xl-10" style="background-color: #F1416C;background-image:url('/assets/media/patterns/vector-1.png')">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</span>
                                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Pendapatan Hari Ini</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0">
                                <div class="d-flex align-items-center flex-column mt-3 w-100">
                                    <a href="{{ route('transaksis.index') }}" class="btn btn-sm btn-light w-100">Lihat Laporan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-100 mb-5 mb-xl-10" style="background-color: #7239EA;background-image:url('/assets/media/patterns/vector-1.png')">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $transaksiHariIni }}</span>
                                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Transaksi Hari Ini</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0">
                                <div class="d-flex align-items-center flex-column mt-3 w-100">
                                    <a href="{{ route('transaksis.index') }}" class="btn btn-sm btn-light w-100">Lihat Laporan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-100 mb-5 mb-xl-10" style="background-color: #009EF7;background-image:url('/assets/media/patterns/vector-1.png')">
                            <div class="card-header pt-5">
                                <div class="card-title d-flex flex-column">
                                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $totalBarang }}</span>
                                    <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Varian Barang</span>
                                </div>
                            </div>
                            <div class="card-body d-flex align-items-end pt-0">
                                <div class="d-flex align-items-center flex-column mt-3 w-100">
                                    <a href="{{ route('stoks.index') }}" class="btn btn-sm btn-light w-100">Cek Gudang</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables Row -->
                <div class="row g-5 gx-xl-10">
                    <!-- Stok Menipis -->
                    <div class="col-xl-6">
                        <div class="card card-flush h-md-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Peringatan Stok Menipis</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Sisa stok 5 ke bawah</span>
                                </h3>
                            </div>
                            <div class="card-body pt-6">
                                @if($stoks->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                            <thead>
                                                <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                                    <th class="p-0 pb-3 min-w-150px text-start">BARANG</th>
                                                    <th class="p-0 pb-3 min-w-100px text-end">SISA STOK</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stoks as $stok)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <a href="{{ route('stoks.index') }}" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                                                    {{ $stok->barangVarian->barang->nama_barang ?? '-' }}
                                                                </a>
                                                                <span class="text-gray-400 fw-semibold d-block fs-7">
                                                                    SKU: {{ $stok->barangVarian->sku ?? '-' }} | {{ $stok->barangVarian->warna->nama_warna ?? '-' }} | {{ $stok->barangVarian->ukuran->nama_ukuran ?? '-' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        @if($stok->jumlah_stok == 0)
                                                            <span class="badge py-3 px-4 fs-7 badge-light-danger">HABIS (0)</span>
                                                        @else
                                                            <span class="badge py-3 px-4 fs-7 badge-light-warning">{{ $stok->jumlah_stok }} Sisa</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-success d-flex align-items-center p-5">
                                        <i class="ki-duotone ki-check-circle fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-success">Gudang Aman</h4>
                                            <span>Tidak ada stok barang yang menipis atau habis.</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Transaksi Terbaru -->
                    <div class="col-xl-6">
                        <div class="card card-flush h-md-100">
                            <div class="card-header pt-7">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">5 Transaksi Terbaru</span>
                                    <span class="text-gray-400 mt-1 fw-semibold fs-6">Riwayat penjualan terakhir</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('transaksis.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                                </div>
                            </div>
                            <div class="card-body pt-6">
                                @if($transaksis->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                            <thead>
                                                <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                                    <th class="p-0 pb-3 min-w-100px text-start">NOMOR / KASIR</th>
                                                    <th class="p-0 pb-3 min-w-100px text-end">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transaksis as $trx)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <a href="{{ route('transaksis.index') }}" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                                                    {{ $trx->nomor_transaksi }}
                                                                </a>
                                                                <span class="text-gray-400 fw-semibold d-block fs-7">
                                                                    Kasir: {{ $trx->user->nama ?? '-' }} | {{ $trx->created_at->format('d/m/Y H:i') }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="text-gray-800 fw-bold fs-6">Rp {{ number_format($trx->total, 0, ',', '.') }}</span>
                                                        <span class="d-block text-success fs-8 mt-1">{{ ucfirst($trx->status) }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-10">Belum ada riwayat transaksi.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body p-lg-17">
                        <div class="text-center mb-13">
                            <h3 class="fs-2hx text-dark mb-5">Selamat Datang, {{ auth()->user()->nama }}</h3>
                            <div class="fs-5 text-muted fw-semibold">Anda login sebagai <span class="fw-bold text-primary">{{ ucfirst(auth()->user()->role->nama_role) }}</span>. Silakan gunakan menu di samping untuk mulai bekerja.</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

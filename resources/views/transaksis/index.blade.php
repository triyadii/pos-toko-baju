@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Riwayat Transaksi</h1>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <div class="row g-5 mb-5">
                <!-- Total Pendapatan Card -->
                <div class="col-md-4">
                    <div class="card card-flush h-md-100 bg-primary" data-bs-theme="light">
                        <div class="card-body py-9">
                            <div class="fs-2hx fw-bold text-white">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                            <div class="fs-4 fw-semibold text-white opacity-75 mb-7">Total Pendapatan</div>
                        </div>
                    </div>
                </div>
                <!-- Filter Card -->
                <div class="col-md-8">
                    <div class="card card-flush h-md-100">
                        <div class="card-body py-9">
                            <h3 class="mb-5">Filter Tanggal Laporan</h3>
                            <form action="{{ route('transaksis.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-5">
                                <div>
                                    <label class="form-label mb-1">Mulai Tanggal</label>
                                    <input type="date" name="start_date" class="form-control form-control-solid w-200px" value="{{ $start_date ?? '' }}" required />
                                </div>
                                <div>
                                    <label class="form-label mb-1">Sampai Tanggal</label>
                                    <input type="date" name="end_date" class="form-control form-control-solid w-200px" value="{{ $end_date ?? '' }}" required />
                                </div>
                                <div class="d-flex align-items-end" style="margin-top: 25px;">
                                    <button type="submit" class="btn btn-primary me-3">Tampilkan</button>
                                    <a href="{{ route('transaksis.index') }}" class="btn btn-light">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-flush">
                <div class="card-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>Tanggal</th>
                                <th>Nomor Transaksi</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($transaksis as $trx)
                            <tr>
                                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $trx->nomor_transaksi }}</td>
                                <td>{{ $trx->user->nama ?? '-' }}</td>
                                <td>Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($trx->status == 'lunas')
                                        <span class="badge badge-light-success">Lunas</span>
                                    @else
                                        <span class="badge badge-light-warning">{{ $trx->status }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-info" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $trx->id }}">Detail</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@foreach($transaksis as $trx)
<div class="modal fade" id="modalDetail{{ $trx->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Transaksi: {{ $trx->nomor_transaksi }}</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr class="fw-bold bg-light">
                            <th>Barang</th>
                            <th>SKU</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trx->details as $detail)
                        <tr>
                            <td>
                                {{ $detail->barangVarian->barang->nama_barang ?? '-' }}
                                <div class="text-muted fs-7">
                                    Warna: {{ $detail->barangVarian->warna->nama_warna ?? '-' }},
                                    Ukuran: {{ $detail->barangVarian->ukuran->nama_ukuran ?? '-' }}
                                </div>
                            </td>
                            <td>{{ $detail->barangVarian->sku ?? '-' }}</td>
                            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end fw-bold">Subtotal:</th>
                            <th class="fw-bold">Rp {{ number_format($trx->subtotal, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end fw-bold">Diskon:</th>
                            <th class="fw-bold text-danger">- Rp {{ number_format($trx->diskon, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end fw-bold">Total:</th>
                            <th class="fw-bold fs-4">Rp {{ number_format($trx->total, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end fw-bold text-muted">Bayar:</th>
                            <th class="text-muted">Rp {{ number_format($trx->bayar, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end fw-bold text-muted">Kembalian:</th>
                            <th class="text-muted">Rp {{ number_format($trx->kembalian, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <a href="{{ route('transaksis.print', $trx->id) }}" target="_blank" class="btn btn-primary">Print Struk</a>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

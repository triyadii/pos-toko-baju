@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Riwayat Stok (Log)</h1>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Tipe</th>
                                <th>Perubahan</th>
                                <th>S.Awal -> S.Akhir</th>
                                <th>Keterangan</th>
                                <th>Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($stok_logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{ $log->barangVarian->barang->nama_barang ?? '-' }}<br>
                                    <span class="text-muted fs-7">SKU: {{ $log->barangVarian->sku ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($log->tipe == 'masuk')
                                        <span class="badge badge-light-success">Masuk</span>
                                    @elseif($log->tipe == 'keluar')
                                        <span class="badge badge-light-danger">Keluar</span>
                                    @else
                                        <span class="badge badge-light-warning">Adjust</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->tipe == 'keluar')
                                        <span class="text-danger fw-bold">-{{ $log->jumlah }}</span>
                                    @else
                                        <span class="text-success fw-bold">+{{ $log->jumlah }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->stok_sebelum }} <i class="ki-duotone ki-arrow-right fs-4 mx-1"><span class="path1"></span><span class="path2"></span></i> {{ $log->stok_sesudah }}</td>
                                <td>{{ $log->keterangan ?? '-' }}</td>
                                <td>{{ $log->user->nama ?? 'Sistem' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

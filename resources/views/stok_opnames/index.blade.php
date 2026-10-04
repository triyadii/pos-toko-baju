@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Stok Opname</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('stok-opnames.create') }}" class="btn btn-sm fw-bold btn-primary">Lakukan Stok Opname</a>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card card-flush">
                <div class="card-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>Tanggal</th>
                                <th>Nomor Opname</th>
                                <th>Petugas</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($opnames as $op)
                            <tr>
                                <td>{{ $op->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $op->nomor_opname }}</td>
                                <td>{{ $op->user->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-light-success">Selesai</span>
                                </td>
                                <td>{{ $op->keterangan ?? '-' }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-info" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $op->id }}">Detail Selisih</button>
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
@foreach($opnames as $op)
<div class="modal fade" id="modalDetail{{ $op->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Opname: {{ $op->nomor_opname }}</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                @if($op->details->count() > 0)
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr class="fw-bold bg-light">
                                <th>SKU / Barang</th>
                                <th class="text-center">Stok Sistem</th>
                                <th class="text-center">Stok Fisik</th>
                                <th class="text-center">Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($op->details as $detail)
                            <tr>
                                <td>
                                    {{ $detail->barangVarian->barang->nama_barang ?? '-' }}
                                    <br>
                                    <span class="text-muted fs-7">SKU: {{ $detail->barangVarian->sku ?? '-' }}</span>
                                </td>
                                <td class="text-center">{{ $detail->stok_sistem }}</td>
                                <td class="text-center">{{ $detail->stok_fisik }}</td>
                                <td class="text-center">
                                    @if($detail->selisih < 0)
                                        <span class="text-danger fw-bold">{{ $detail->selisih }}</span>
                                    @else
                                        <span class="text-success fw-bold">+{{ $detail->selisih }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center text-muted">Tidak ada selisih stok (Sinkron 100%).</div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

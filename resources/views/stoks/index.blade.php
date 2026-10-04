@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Stok</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button type="button" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#modalPenyesuaian">Penyesuaian Stok</button>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row g-5 g-xl-8 mb-10">
                @foreach($stoks as $item)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card card-flush h-100 border border-2 border-dashed border-gray-300 hover-elevate-up">
                        <div class="card-body p-5 text-center flex-column justify-content-between d-flex">
                            <div class="mb-4">
                                <i class="ki-duotone ki-cube-2 fs-3x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </div>
                            <div class="fs-4 fw-bold text-gray-800 mb-1">{{ $item->barangVarian->barang->nama_barang ?? '-' }}</div>
                            <div class="fs-6 text-gray-600 mb-3">{{ $item->barangVarian->warna->nama_warna ?? '-' }} | {{ $item->barangVarian->ukuran->nama_ukuran ?? '-' }}</div>
                            <div class="d-flex flex-wrap justify-content-center align-items-center mb-4">
                                <span class="badge badge-light-primary fw-bold fs-7">SKU: {{ $item->barangVarian->sku ?? '-' }}</span>
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex flex-column align-items-center mb-4">
                                    <span class="text-gray-500 fw-semibold fs-7 mb-2">Jumlah Stok</span>
                                    @if($item->jumlah_stok > 10)
                                        <span class="badge badge-success fs-1 py-3 px-4">{{ $item->jumlah_stok }}</span>
                                    @elseif($item->jumlah_stok > 0)
                                        <span class="badge badge-warning fs-1 py-3 px-4">{{ $item->jumlah_stok }}</span>
                                    @else
                                        <span class="badge badge-danger fs-1 py-3 px-4">{{ $item->jumlah_stok }}</span>
                                    @endif
                                </div>
                                <div class="fs-8 fw-semibold text-muted">Update: {{ $item->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="card card-flush">
                <div class="card-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>Barang</th>
                                <th>Warna</th>
                                <th>Ukuran</th>
                                <th>SKU</th>
                                <th>Jumlah Stok</th>
                                <th>Terakhir Update</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($stoks as $item)
                            <tr>
                                <td>{{ $item->barangVarian->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->barangVarian->warna->nama_warna ?? '-' }}</td>
                                <td>{{ $item->barangVarian->ukuran->nama_ukuran ?? '-' }}</td>
                                <td>{{ $item->barangVarian->sku ?? '-' }}</td>
                                <td>
                                    @if($item->jumlah_stok > 10)
                                        <span class="badge badge-light-success fs-base">{{ $item->jumlah_stok }}</span>
                                    @elseif($item->jumlah_stok > 0)
                                        <span class="badge badge-light-warning fs-base">{{ $item->jumlah_stok }}</span>
                                    @else
                                        <span class="badge badge-light-danger fs-base">{{ $item->jumlah_stok }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Penyesuaian Stok -->
<div class="modal fade" id="modalPenyesuaian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('stoks.adjust') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2>Penyesuaian Stok</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17 text-start">
                    <div class="mb-5">
                        <label class="required form-label">Barang Varian</label>
                        <select name="barang_varian_id" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#modalPenyesuaian" data-placeholder="Pilih Barang" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barang_varians as $bv)
                                <option value="{{ $bv->id }}">
                                    {{ $bv->barang->nama_barang ?? '' }} - 
                                    Warna: {{ $bv->warna->nama_warna ?? '' }} - 
                                    Ukuran: {{ $bv->ukuran->nama_ukuran ?? '' }} 
                                    (SKU: {{ $bv->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Tipe Penyesuaian</label>
                        <select name="tipe" class="form-select form-select-solid" required>
                            <option value="masuk">Stok Masuk (Bertambah)</option>
                            <option value="keluar">Stok Keluar (Berkurang)</option>
                            <option value="penyesuaian">Penyesuaian (Bertambah)</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control form-control-solid" min="1" value="1" required />
                        <div class="text-muted fs-7 mt-2">Masukkan nilai absolut yang akan ditambahkan/dikurangkan.</div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control form-control-solid" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penyesuaian</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

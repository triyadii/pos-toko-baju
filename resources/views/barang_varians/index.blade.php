@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Barang Varian</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button type="button" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Varian</button>
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
            <div class="card card-flush">
                <div class="card-body">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>Barang</th>
                                <th>Warna</th>
                                <th>Ukuran</th>
                                <th>SKU</th>
                                <th>Barcode</th>
                                <th>Harga Jual</th>
                                <th>Status</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($barang_varians as $item)
                            <tr>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->warna->nama_warna ?? '-' }}</td>
                                <td>{{ $item->ukuran->nama_ukuran ?? '-' }}</td>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->barcode }}</td>
                                <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->status)
                                        <span class="badge badge-light-success">Aktif</span>
                                    @else
                                        <span class="badge badge-light-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">Edit</button>
                                    <form action="{{ route('barang-varians.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-danger" onclick="return confirm('Hapus data ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <form action="{{ route('barang-varians.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h2>Edit Barang Varian</h2>
                                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                </div>
                                            </div>
                                            <div class="modal-body py-10 px-lg-17 text-start">
                                                <div class="mb-5">
                                                    <label class="required form-label">Barang</label>
                                                    <select name="barang_id" class="form-select form-select-solid" required>
                                                        @foreach($barangs as $b)
                                                            <option value="{{ $b->id }}" {{ $item->barang_id == $b->id ? 'selected' : '' }}>{{ $b->nama_barang }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-5">
                                                        <label class="required form-label">Warna</label>
                                                        <select name="warna_id" class="form-select form-select-solid" required>
                                                            @foreach($warnas as $w)
                                                                <option value="{{ $w->id }}" {{ $item->warna_id == $w->id ? 'selected' : '' }}>{{ $w->nama_warna }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-5">
                                                        <label class="required form-label">Ukuran</label>
                                                        <select name="ukuran_id" class="form-select form-select-solid" required>
                                                            @foreach($ukurans as $u)
                                                                <option value="{{ $u->id }}" {{ $item->ukuran_id == $u->id ? 'selected' : '' }}>{{ $u->nama_ukuran }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-5">
                                                        <label class="form-label">SKU (Otomatis)</label>
                                                        <input type="text" name="sku" class="form-control form-control-solid" value="{{ $item->sku }}" readonly />
                                                    </div>
                                                    <div class="col-md-6 mb-5">
                                                        <label class="form-label">Barcode (Otomatis)</label>
                                                        <input type="text" name="barcode" class="form-control form-control-solid" value="{{ $item->barcode }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-5">
                                                        <label class="required form-label">Harga Beli</label>
                                                        <input type="number" name="harga_beli" class="form-control form-control-solid" value="{{ $item->harga_beli }}" required />
                                                    </div>
                                                    <div class="col-md-6 mb-5">
                                                        <label class="required form-label">Harga Jual</label>
                                                        <input type="number" name="harga_jual" class="form-control form-control-solid" value="{{ $item->harga_jual }}" required />
                                                    </div>
                                                </div>
                                                <div class="mb-5">
                                                    <label class="required form-label">Status</label>
                                                    <select name="status" class="form-select form-select-solid" required>
                                                        <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Aktif</option>
                                                        <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('barang-varians.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2>Tambah Barang Varian</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17 text-start">
                    <div class="mb-5">
                        <label class="required form-label">Barang</label>
                        <select name="barang_id" class="form-select form-select-solid" required>
                            <option value="">Pilih Barang</option>
                            @foreach($barangs as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="required form-label">Warna</label>
                            <select name="warna_id" class="form-select form-select-solid" required>
                                <option value="">Pilih Warna</option>
                                @foreach($warnas as $w)
                                    <option value="{{ $w->id }}">{{ $w->nama_warna }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="required form-label">Ukuran</label>
                            <select name="ukuran_id" class="form-select form-select-solid" required>
                                <option value="">Pilih Ukuran</option>
                                @foreach($ukurans as $u)
                                    <option value="{{ $u->id }}">{{ $u->nama_ukuran }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label class="required form-label">Harga Beli</label>
                            <input type="number" name="harga_beli" class="form-control form-control-solid" required />
                        </div>
                        <div class="col-md-6 mb-5">
                            <label class="required form-label">Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control form-control-solid" required />
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Status</label>
                        <select name="status" class="form-select form-select-solid" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

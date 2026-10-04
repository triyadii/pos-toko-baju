@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Barang</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button type="button" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Barang</button>
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
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Status</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($barangs as $item)
                            <tr>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->jenisBarang->nama_jenis_barang ?? '-' }}</td>
                                <td>{{ $item->statusBarang->nama_status_barang ?? '-' }}</td>
                                <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">Edit</button>
                                    <form action="{{ route('barangs.destroy', $item->id) }}" method="POST" class="d-inline">
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
                                        <form action="{{ route('barangs.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h2>Edit Barang</h2>
                                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                </div>
                                            </div>
                                            <div class="modal-body py-10 px-lg-17 text-start">
                                                <div class="mb-5">
                                                    <label class="required form-label">Kode Barang (Otomatis)</label>
                                                    <input type="text" name="kode_barang" class="form-control form-control-solid" value="{{ $item->kode_barang }}" readonly />
                                                </div>
                                                <div class="mb-5">
                                                    <label class="required form-label">Nama Barang</label>
                                                    <input type="text" name="nama_barang" class="form-control form-control-solid" value="{{ $item->nama_barang }}" required />
                                                </div>
                                                <div class="mb-5">
                                                    <label class="required form-label">Jenis Barang</label>
                                                    <select name="jenis_barang_id" class="form-select form-select-solid" required>
                                                        @foreach($jenis_barangs as $jb)
                                                            <option value="{{ $jb->id }}" {{ $item->jenis_barang_id == $jb->id ? 'selected' : '' }}>{{ $jb->nama_jenis_barang }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-5">
                                                    <label class="required form-label">Status Barang</label>
                                                    <select name="status_barang_id" class="form-select form-select-solid" required>
                                                        @foreach($status_barangs as $sb)
                                                            <option value="{{ $sb->id }}" {{ $item->status_barang_id == $sb->id ? 'selected' : '' }}>{{ $sb->nama_status_barang }}</option>
                                                        @endforeach
                                                    </select>
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
            <form action="{{ route('barangs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2>Tambah Barang</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17 text-start">
                    <div class="mb-5">
                        <label class="required form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control form-control-solid" required />
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Jenis Barang</label>
                        <select name="jenis_barang_id" class="form-select form-select-solid" required>
                            <option value="">Pilih Jenis</option>
                            @foreach($jenis_barangs as $jb)
                                <option value="{{ $jb->id }}">{{ $jb->nama_jenis_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Status Barang</label>
                        <select name="status_barang_id" class="form-select form-select-solid" required>
                            <option value="">Pilih Status</option>
                            @foreach($status_barangs as $sb)
                                <option value="{{ $sb->id }}">{{ $sb->nama_status_barang }}</option>
                            @endforeach
                        </select>
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

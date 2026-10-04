@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Jenis Barang</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button type="button" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Jenis Barang</button>
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
                                <th>Nama Jenis Barang</th>
                                <th>Status</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($jenis_barangs as $item)
                            <tr>
                                <td>{{ $item->nama_jenis_barang }}</td>
                                <td>
                                    @if($item->status)
                                        <span class="badge badge-light-success">Aktif</span>
                                    @else
                                        <span class="badge badge-light-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id }}">Edit</button>
                                    <form action="{{ route('jenis-barangs.destroy', $item->id) }}" method="POST" class="d-inline">
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
                                        <form action="{{ route('jenis-barangs.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h2>Edit Jenis Barang</h2>
                                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                </div>
                                            </div>
                                            <div class="modal-body py-10 px-lg-17 text-start">
                                                <div class="mb-5">
                                                    <label class="required form-label">Nama Jenis Barang</label>
                                                    <input type="text" name="nama_jenis_barang" class="form-control form-control-solid" value="{{ $item->nama_jenis_barang }}" required />
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
            <form action="{{ route('jenis-barangs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2>Tambah Jenis Barang</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17 text-start">
                    <div class="mb-5">
                        <label class="required form-label">Nama Jenis Barang</label>
                        <input type="text" name="nama_jenis_barang" class="form-control form-control-solid" required />
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

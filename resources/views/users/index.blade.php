@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Manajemen User</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button type="button" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">Tambah User</button>
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
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->role->nama_role ?? '-' }}</td>
                                <td>
                                    @if($user->status)
                                        <span class="badge badge-light-success">Aktif</span>
                                    @else
                                        <span class="badge badge-light-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}">Edit</button>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-danger" onclick="return confirm('Hapus user ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <div class="modal-content">
                                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h2>Edit User</h2>
                                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                </div>
                                            </div>
                                            <div class="modal-body py-10 px-lg-17 text-start">
                                                <div class="mb-5">
                                                    <label class="required form-label">Nama</label>
                                                    <input type="text" name="nama" class="form-control form-control-solid" value="{{ $user->nama }}" required />
                                                </div>
                                                <div class="mb-5">
                                                    <label class="required form-label">Username</label>
                                                    <input type="text" name="username" class="form-control form-control-solid" value="{{ $user->username }}" required />
                                                </div>
                                                <div class="mb-5">
                                                    <label class="form-label">Password (Kosongkan jika tidak ingin diubah)</label>
                                                    <input type="password" name="password" class="form-control form-control-solid" />
                                                </div>
                                                <div class="mb-5">
                                                    <label class="required form-label">Role</label>
                                                    <select name="role_id" class="form-select form-select-solid" required>
                                                        @foreach(\App\Models\Role::all() as $role)
                                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-5">
                                                    <label class="required form-label">Status</label>
                                                    <select name="status" class="form-select form-select-solid" required>
                                                        <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Aktif</option>
                                                        <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
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
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h2>Tambah User</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17 text-start">
                    <div class="mb-5">
                        <label class="required form-label">Nama</label>
                        <input type="text" name="nama" class="form-control form-control-solid" required />
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Username</label>
                        <input type="text" name="username" class="form-control form-control-solid" required />
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-solid" required />
                    </div>
                    <div class="mb-5">
                        <label class="required form-label">Role</label>
                        <select name="role_id" class="form-select form-select-solid" required>
                            <option value="">Pilih Role</option>
                            @foreach(\App\Models\Role::all() as $role)
                                <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                            @endforeach
                        </select>
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

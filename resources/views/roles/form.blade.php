@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">{{ isset($role) ? 'Edit Role' : 'Tambah Role' }}</h1>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-body">
                    <form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST">
                        @csrf
                        @if(isset($role))
                            @method('PUT')
                        @endif

                        <div class="mb-10">
                            <label class="required form-label">Nama Role</label>
                            <input type="text" name="nama_role" class="form-control form-control-solid" value="{{ old('nama_role', $role->nama_role ?? '') }}" required />
                            @error('nama_role') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-10">
                            <label class="required form-label">Status</label>
                            <select name="status" class="form-select form-select-solid" required>
                                <option value="1" {{ old('status', $role->status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status', $role->status ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

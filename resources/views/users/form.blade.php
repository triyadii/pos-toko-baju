@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">{{ isset($user) ? 'Edit User' : 'Tambah User' }}</h1>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <div class="card-body">
                    <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <div class="mb-10">
                            <label class="required form-label">Nama</label>
                            <input type="text" name="nama" class="form-control form-control-solid" value="{{ old('nama', $user->nama ?? '') }}" required />
                            @error('nama') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-10">
                            <label class="required form-label">Username</label>
                            <input type="text" name="username" class="form-control form-control-solid" value="{{ old('username', $user->username ?? '') }}" required />
                            @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-10">
                            <label class="{{ isset($user) ? '' : 'required' }} form-label">Password {{ isset($user) ? '(Kosongkan jika tidak ingin diubah)' : '' }}</label>
                            <input type="password" name="password" class="form-control form-control-solid" {{ isset($user) ? '' : 'required' }} />
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-10">
                            <label class="required form-label">Role</label>
                            <select name="role_id" class="form-select form-select-solid" required>
                                <option value="">Pilih Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                                @endforeach
                            </select>
                            @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-10">
                            <label class="required form-label">Status</label>
                            <select name="status" class="form-select form-select-solid" required>
                                <option value="1" {{ old('status', $user->status ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status', $user->status ?? 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Profil Toko</h1>
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
                <div class="card-header pt-7">
                    <h3 class="card-title fw-bold text-gray-800">Pengaturan Informasi Toko</h3>
                </div>
                <div class="card-body pt-5">
                    <form action="{{ route('profil-tokos.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label class="required form-label">Nama Toko</label>
                                <input type="text" name="nama_toko" class="form-control form-control-solid" value="{{ $profil->nama_toko }}" required />
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">Slogan Singkat</label>
                                <input type="text" name="slogan" class="form-control form-control-solid" value="{{ $profil->slogan }}" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label class="form-label">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="telepon" class="form-control form-control-solid" value="{{ $profil->telepon }}" />
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label">Alamat Email</label>
                                <input type="email" name="email" class="form-control form-control-solid" value="{{ $profil->email }}" />
                            </div>
                        </div>

                        <div class="mb-10">
                            <label class="form-label">Alamat Lengkap Toko</label>
                            <textarea name="alamat" class="form-control form-control-solid" rows="4">{{ $profil->alamat }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-4 fs-4 fw-bold">Simpan Perubahan Profil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

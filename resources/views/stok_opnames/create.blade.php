@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Lakukan Stok Opname</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('stok-opnames.index') }}" class="btn btn-sm btn-light">Batal / Kembali</a>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
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
                    <form action="{{ route('stok-opnames.store') }}" method="POST">
                        @csrf
                        <div class="mb-10">
                            <label class="form-label">Keterangan / Catatan Opname</label>
                            <textarea name="keterangan" class="form-control form-control-solid" rows="3" placeholder="Contoh: Opname Akhir Bulan Oktober"></textarea>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="opnameTable">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-200px">Barang Varian</th>
                                        <th>SKU</th>
                                        <th class="text-center">Stok Sistem Saat Ini</th>
                                        <th class="text-center w-150px">Stok Fisik (Actual)</th>
                                        <th class="text-center">Selisih</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach($varians as $index => $varian)
                                    @php
                                        $stokSistem = $varian->stok->jumlah_stok ?? 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $varian->id }}">
                                            <span class="fw-bold">{{ $varian->barang->nama_barang ?? '-' }}</span><br>
                                            <span class="text-muted fs-7">Warna: {{ $varian->warna->nama_warna ?? '-' }}, Ukuran: {{ $varian->ukuran->nama_ukuran ?? '-' }}</span>
                                        </td>
                                        <td>{{ $varian->sku }}</td>
                                        <td class="text-center sys-stok" data-stok="{{ $stokSistem }}">{{ $stokSistem }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-solid form-control-sm text-center fisik-input" name="items[{{ $index }}][fisik]" value="{{ $stokSistem }}" min="0" required>
                                        </td>
                                        <td class="text-center selisih-display fw-bold">0</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-10 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin data stok fisik sudah benar? Sistem akan menyesuaikan stok berdasarkan data fisik ini.')">Simpan Stok Opname</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.fisik-input');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const tr = this.closest('tr');
                const sysStok = parseInt(tr.querySelector('.sys-stok').getAttribute('data-stok'));
                const fisikStok = parseInt(this.value) || 0;
                const selisih = fisikStok - sysStok;
                const selisihEl = tr.querySelector('.selisih-display');
                
                if (selisih < 0) {
                    selisihEl.innerHTML = `<span class="text-danger">${selisih}</span>`;
                } else if (selisih > 0) {
                    selisihEl.innerHTML = `<span class="text-success">+${selisih}</span>`;
                } else {
                    selisihEl.innerHTML = `0`;
                }
            });
        });
    });
</script>
@endsection

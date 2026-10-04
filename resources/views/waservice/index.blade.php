@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Manajemen WA Service</h1>
            </div>
        </div>
    </div>
    
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                    <i class="ki-duotone ki-check-circle fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-success">Berhasil</h4>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="ki-duotone ki-cross-circle fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Gagal</h4>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            
            <div class="row g-5 g-xl-8">
                <!-- Status Card -->
                <div class="col-xl-6">
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header align-items-center border-0 mt-4">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="fw-bold mb-2 text-gray-900">Status WA Service</span>
                                <span class="text-muted fw-semibold fs-7">Cek status dan jalankan WA service</span>
                            </h3>
                            <div class="card-toolbar">
                                <form action="{{ route('waservice.start') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary" id="btn-start-service">
                                        <i class="ki-duotone ki-rocket fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        Start Service
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <div class="d-flex align-items-center mb-7">
                                <div class="d-flex flex-column w-100" id="wa-status-container">
                                    <span class="text-gray-900 fs-5 fw-bold mb-3">Response dari API Status:</span>
                                    <div id="wa-status-content">
                                        @if(isset($status['status']))
                                            <div class="mb-3">
                                                <span class="badge badge-light-primary fs-6 py-2 px-3">Status: {{ $status['status'] }}</span>
                                                @if(isset($status['sessionId']))
                                                    <span class="badge badge-light-info fs-6 py-2 px-3 ms-2">Session: {{ $status['sessionId'] }}</span>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if(isset($status['qr']))
                                            <div class="mb-5 text-center bg-light p-4 rounded">
                                                <div class="text-dark fw-bold mb-3">Scan QR Code untuk Login WhatsApp:</div>
                                                <img src="{{ $status['qr'] }}" alt="QR Code" class="img-fluid border border-2 border-secondary rounded" style="max-width: 250px;">
                                            </div>
                                        @endif

                                        <pre class="bg-light p-4 rounded text-dark" style="max-height: 250px; overflow-y: auto;">@php 
                                            $status_clean = $status;
                                            if(isset($status_clean['qr'])) {
                                                $status_clean['qr'] = '(base64 string omitted for readability)';
                                            }
                                            echo json_encode($status_clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); 
                                        @endphp</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Send Message Card -->
                <div class="col-xl-6">
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <div class="card-header align-items-center border-0 mt-4">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="fw-bold mb-2 text-gray-900">Test Kirim Pesan WA</span>
                                <span class="text-muted fw-semibold fs-7">Uji coba pengiriman pesan via API</span>
                            </h3>
                        </div>
                        <div class="card-body pt-5">
                            <form action="{{ route('waservice.send') }}" method="POST">
                                @csrf
                                <div class="mb-5">
                                    <label class="required form-label">Nomor WhatsApp</label>
                                    <input type="text" name="number" class="form-control" placeholder="Contoh: 6281234567890" required />
                                    <div class="text-muted fs-7 mt-2">Gunakan kode negara, misalnya 62 untuk Indonesia</div>
                                </div>
                                <div class="mb-5">
                                    <label class="required form-label">Pesan</label>
                                    <textarea name="message" class="form-control" rows="4" placeholder="Tuliskan pesan..." required></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ki-duotone ki-send fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnStart = document.getElementById('btn-start-service');
        
        function fetchStatus() {
            fetch("{{ route('waservice.index') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.data) {
                    renderStatus(data.data);
                }
            })
            .catch(err => console.error('Failed to fetch WA status', err));
        }
        
        function renderStatus(statusData) {
            let html = '';
            
            if (statusData.status) {
                html += `
                <div class="mb-3">
                    <span class="badge badge-light-primary fs-6 py-2 px-3">Status: ${statusData.status}</span>
                    ${statusData.sessionId ? `<span class="badge badge-light-info fs-6 py-2 px-3 ms-2">Session: ${statusData.sessionId}</span>` : ''}
                </div>`;
            }
            
            if (statusData.qr) {
                html += `
                <div class="mb-5 text-center bg-light p-4 rounded">
                    <div class="text-dark fw-bold mb-3">Scan QR Code untuk Login WhatsApp:</div>
                    <img src="${statusData.qr}" alt="QR Code" class="img-fluid border border-2 border-secondary rounded" style="max-width: 250px;">
                </div>`;
            }
            
            const cleanStatus = { ...statusData };
            if (cleanStatus.qr) {
                cleanStatus.qr = '(base64 string omitted for readability)';
            }
            
            html += `
            <pre class="bg-light p-4 rounded text-dark" style="max-height: 250px; overflow-y: auto;">${JSON.stringify(cleanStatus, null, 4)}</pre>
            `;
            
            const container = document.getElementById('wa-status-content');
            if (container && container.innerHTML !== html) {
                container.innerHTML = html;
            }
        }
        
        // Poll every 3 seconds
        setInterval(fetchStatus, 3000);
    });
</script>
@endsection

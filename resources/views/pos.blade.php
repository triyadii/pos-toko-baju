@extends('layouts.app')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Point of Sales (Kasir)</h1>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <div class="form-check form-switch form-check-custom form-check-solid form-check-danger me-2" title="Paksa Mode Offline (Berguna saat koneksi lambat)">
                    <input class="form-check-input" type="checkbox" id="forceOfflineToggle" onchange="checkNetworkStatus()" />
                    <label class="form-check-label fw-bold text-gray-700 fs-7" for="forceOfflineToggle">
                        Paksa Offline
                    </label>
                </div>
                <span id="networkStatus" class="badge badge-success fs-6 px-4 py-2">Online</span>
                <button type="button" class="btn btn-sm fw-bold btn-info d-none" id="btnViewOffline" onclick="showOfflineData()">
                    <i class="ki-duotone ki-eye fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Lihat Data Offline
                </button>
                <button type="button" class="btn btn-sm fw-bold btn-warning d-none" id="btnSyncOffline" onclick="syncOfflineData()">
                    <i class="ki-duotone ki-arrows-loop fs-4"><span class="path1"></span><span class="path2"></span></i> Sync Data
                </button>
                <a href="{{ route('transaksis.index') }}" class="btn btn-sm fw-bold btn-light-primary">Riwayat Transaksi</a>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            
            <div class="row g-5 g-xl-10">
                <!-- Product Grid (Left Side) -->
                <div class="col-xl-8">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-7">
                            <div class="card-title">
                                <h2>Katalog Barang</h2>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                                    <input type="text" id="searchInput" class="form-control form-control-solid w-250px ps-12" placeholder="Cari nama barang / SKU..." />
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <div class="row g-5" id="productContainer">
                                @foreach($varians as $v)
                                <div class="col-md-4 col-lg-3 product-item" data-name="{{ strtolower($v->barang->nama_barang . ' ' . $v->sku) }}">
                                    <div class="card border border-2 border-dashed border-gray-300 hover-elevate-up cursor-pointer h-100" onclick="addToCart({{ $v->id }}, '{{ $v->barang->nama_barang }}', '{{ $v->warna->nama_warna }}', '{{ $v->ukuran->nama_ukuran }}', {{ $v->harga_jual }}, {{ $v->stok->jumlah_stok ?? 0 }})">
                                        <div class="card-body p-5 text-center">
                                            <div class="mb-3">
                                                <!-- Placeholder icon since we dont have image uploads yet -->
                                                <i class="ki-duotone ki-basket fs-3x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                            </div>
                                            <div class="fs-5 fw-bold text-gray-800 mb-1">{{ $v->barang->nama_barang }}</div>
                                            <div class="fs-7 text-gray-500 mb-3">{{ $v->warna->nama_warna }} | {{ $v->ukuran->nama_ukuran }}</div>
                                            <div class="d-flex flex-wrap justify-content-center align-items-center mb-2">
                                                <span class="badge badge-light-success fs-base fw-bold">Rp {{ number_format($v->harga_jual, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="fs-8 fw-semibold text-muted">Stok: {{ $v->stok->jumlah_stok ?? 0 }}</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart (Right Side) -->
                <div class="col-xl-4">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-7">
                            <h3 class="card-title fw-bold text-gray-800">Keranjang</h3>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-light-danger" onclick="clearCart()">Clear</button>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <div id="cartItems" class="mb-5" style="max-height: 350px; overflow-y: auto;">
                                <!-- Cart items will be injected here via JS -->
                                <div class="text-center text-muted py-10" id="emptyCartMsg">Keranjang masih kosong</div>
                            </div>

                            <div class="separator border-gray-300 my-5"></div>
                            
                            <div class="d-flex flex-stack mb-3">
                                <div class="fw-semibold text-gray-600 fs-5">Subtotal</div>
                                <div class="fw-bold text-gray-800 fs-5" id="cartSubtotal">Rp 0</div>
                            </div>
                            <div class="d-flex flex-stack mb-3">
                                <div class="fw-semibold text-gray-600 fs-5">Diskon (Rp)</div>
                                <div class="fw-bold text-gray-800 fs-5">
                                    <input type="text" id="inputDiskon" class="form-control form-control-sm form-control-solid w-100px text-end" value="0" oninput="this.value = formatRupiahInput(this.value); calculateTotal()">
                                </div>
                            </div>
                            <div class="separator border-gray-300 my-5"></div>
                            <div class="d-flex flex-stack mb-5">
                                <div class="fw-bold text-gray-800 fs-3">Total</div>
                                <div class="fw-bold text-primary fs-2" id="cartTotal">Rp 0</div>
                            </div>

                            <div class="mb-5">
                                <label class="fw-semibold text-gray-600 fs-6 mb-2">Uang Bayar (Tunai)</label>
                                <div class="input-group input-group-solid">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" id="inputBayar" class="form-control" oninput="this.value = formatRupiahInput(this.value); calculateTotal()">
                                </div>
                            </div>

                            <div class="d-flex flex-stack mb-10">
                                <div class="fw-semibold text-gray-600 fs-5">Kembalian</div>
                                <div class="fw-bold text-success fs-3" id="cartKembalian">Rp 0</div>
                            </div>

                            <button type="button" class="btn btn-primary w-100 py-4 fs-4 fw-bold" id="btnCheckout" onclick="showConfirmModal()" disabled>
                                Proses Transaksi (Bayar)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" tabindex="-1" id="confirmCheckoutModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Konfirmasi Transaksi</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body">
                <p class="fs-5">Apakah Anda yakin ingin memproses transaksi ini? Struk akan langsung dicetak setelah diproses.</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnProsesModal" onclick="processCheckout()">Ya, Proses & Cetak</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sukses -->
<div class="modal fade" tabindex="-1" id="successCheckoutModal" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-body py-10">
                <i class="ki-duotone ki-check-circle fs-5x text-success mb-5"><span class="path1"></span><span class="path2"></span></i>
                <h3 class="modal-title mb-3">Transaksi Berhasil!</h3>
                <p class="fs-5 text-muted mb-7">Struk telah dicetak. Apa yang ingin Anda lakukan selanjutnya?</p>
                
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-info" id="btnReprint" onclick="reprintStruk()">
                        <i class="ki-duotone ki-printer fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> Cetak Ulang
                    </button>
                    <button type="button" class="btn btn-primary" onclick="tutupTransaksi()">
                        Selesai / Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Offline Data -->
<div class="modal fade" tabindex="-1" id="offlineDataModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Data Transaksi Offline Belum Disinkronisasi</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>

            <div class="modal-body scroll-y" style="max-height: 400px;">
                <div class="table-responsive">
                    <table class="table align-middle gs-0 gy-4">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 rounded-start">Waktu (Lokal)</th>
                                <th>Item (Qty)</th>
                                <th>Total Belanja</th>
                                <th>Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody id="offlineDataTableBody">
                            <!-- Injected via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" onclick="syncOfflineData()">Sync Sekarang</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let cart = [];
    
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        checkNetworkStatus();
        window.addEventListener('online', checkNetworkStatus);
        window.addEventListener('offline', checkNetworkStatus);
        
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            let term = e.target.value.toLowerCase();
            let items = document.querySelectorAll('.product-item');
            items.forEach(item => {
                if (item.getAttribute('data-name').includes(term)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    function checkNetworkStatus() {
        let statusBadge = document.getElementById('networkStatus');
        let btnSync = document.getElementById('btnSyncOffline');
        let btnView = document.getElementById('btnViewOffline');
        let forceOfflineToggle = document.getElementById('forceOfflineToggle');
        
        // Otomatis nyalakan toggle Paksa Offline jika koneksi benar-benar terputus
        if (!navigator.onLine) {
            forceOfflineToggle.checked = true;
        }
        
        let forceOffline = forceOfflineToggle.checked;
        
        let offlineData = JSON.parse(localStorage.getItem('offline_transactions')) || [];
        
        let isOnline = navigator.onLine && !forceOffline;

        if (isOnline) {
            statusBadge.className = 'badge badge-success fs-6 px-4 py-2';
            statusBadge.innerText = 'Online';
            
            // Check if there are offline transactions
            if (offlineData.length > 0) {
                btnSync.classList.remove('d-none');
                btnView.classList.remove('d-none');
                btnSync.innerHTML = `<i class="ki-duotone ki-arrows-loop fs-4"><span class="path1"></span><span class="path2"></span></i> Sync Data (${offlineData.length})`;
            } else {
                btnSync.classList.add('d-none');
                btnView.classList.add('d-none');
            }
        } else {
            statusBadge.className = 'badge badge-danger fs-6 px-4 py-2';
            statusBadge.innerText = 'Offline Mode';
            btnSync.classList.add('d-none');
            if (offlineData.length > 0) {
                btnView.classList.remove('d-none');
            } else {
                btnView.classList.add('d-none');
            }
        }
    }
    
    function showOfflineData() {
        let offlineData = JSON.parse(localStorage.getItem('offline_transactions')) || [];
        let tbody = document.getElementById('offlineDataTableBody');
        tbody.innerHTML = '';
        
        if (offlineData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Tidak ada data offline</td></tr>';
        } else {
            offlineData.forEach(tx => {
                let itemsList = tx.items.map(item => `${item.name} (${item.qty}x)`).join('<br>');
                let time = new Date(tx.timestamp).toLocaleString();
                
                tbody.innerHTML += `
                    <tr>
                        <td class="ps-4">${time}</td>
                        <td>${itemsList}</td>
                        <td class="fw-bold">Rp ${formatRupiah(tx.total)}</td>
                        <td>Rp ${formatRupiah(tx.bayar)}</td>
                    </tr>
                `;
            });
        }
        
        let modal = new bootstrap.Modal(document.getElementById('offlineDataModal'));
        modal.show();
    }

    function addToCart(id, name, warna, ukuran, harga, maxStok) {
        if (maxStok <= 0) {
            Swal.fire({ text: 'Stok barang habis!', icon: 'warning', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-primary' } });
            return;
        }

        let existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.qty < maxStok) {
                existing.qty += 1;
            } else {
                Swal.fire({ text: 'Maksimal stok tercapai!', icon: 'warning', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-primary' } });
            }
        } else {
            cart.push({
                id: id,
                name: name + ' (' + warna + '/' + ukuran + ')',
                harga: harga,
                qty: 1,
                maxStok: maxStok
            });
        }
        renderCart();
    }

    function changeQty(id, delta) {
        let item = cart.find(i => i.id === id);
        if (item) {
            item.qty += delta;
            if (item.qty <= 0) {
                cart = cart.filter(i => i.id !== id);
            } else if (item.qty > item.maxStok) {
                item.qty = item.maxStok;
                alert('Maksimal stok tercapai!');
            }
        }
        renderCart();
    }

    function clearCart() {
        if (cart.length === 0) return;
        Swal.fire({
            title: 'Bersihkan Keranjang?',
            text: "Semua barang di keranjang akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bersihkan!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-light'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                renderCart();
            }
        });
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function formatRupiahInput(value) {
        if (!value) return '';
        let number_string = value.toString().replace(/\D/g, '');
        let res = '';
        let count = 0;
        for(let i = number_string.length - 1; i >= 0; i--) {
            res = number_string[i] + res;
            count++;
            if (count % 3 === 0 && i !== 0) {
                res = '.' + res;
            }
        }
        return res;
    }

    function renderCart() {
        let html = '';
        let subtotal = 0;
        
        if (cart.length === 0) {
            html = '<div class="text-center text-muted py-10" id="emptyCartMsg">Keranjang masih kosong</div>';
        } else {
            cart.forEach(item => {
                let itemTotal = item.harga * item.qty;
                subtotal += itemTotal;
                
                html += `
                <div class="d-flex align-items-center justify-content-between mb-5">
                    <div class="d-flex flex-column w-50">
                        <span class="fw-bold text-gray-800 fs-6 text-truncate" title="${item.name}">${item.name}</span>
                        <span class="text-gray-500 fw-semibold fs-7">Rp ${formatRupiah(item.harga)}</span>
                    </div>
                    <div class="d-flex align-items-center w-25 justify-content-center">
                        <button type="button" class="btn btn-icon btn-sm btn-light btn-active-color-primary w-25px h-25px" onclick="changeQty(${item.id}, -1)">
                            <i class="ki-duotone ki-minus fs-5"></i>
                        </button>
                        <span class="fw-bold mx-2">${item.qty}</span>
                        <button type="button" class="btn btn-icon btn-sm btn-light btn-active-color-primary w-25px h-25px" onclick="changeQty(${item.id}, 1)">
                            <i class="ki-duotone ki-plus fs-5"></i>
                        </button>
                    </div>
                    <div class="w-25 text-end">
                        <span class="fw-bold text-gray-800 fs-6">Rp ${formatRupiah(itemTotal)}</span>
                    </div>
                </div>
                `;
            });
        }
        
        document.getElementById('cartItems').innerHTML = html;
        document.getElementById('cartSubtotal').innerText = 'Rp ' + formatRupiah(subtotal);
        document.getElementById('cartSubtotal').setAttribute('data-val', subtotal);
        
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = parseInt(document.getElementById('cartSubtotal').getAttribute('data-val')) || 0;
        let diskon = parseInt(document.getElementById('inputDiskon').value.replace(/\D/g, '')) || 0;
        let bayar = parseInt(document.getElementById('inputBayar').value.replace(/\D/g, '')) || 0;
        
        let total = subtotal - diskon;
        if (total < 0) total = 0;
        
        let kembalian = bayar - total;
        
        document.getElementById('cartTotal').innerText = 'Rp ' + formatRupiah(total);
        
        if (kembalian >= 0 && bayar > 0 && cart.length > 0) {
            document.getElementById('cartKembalian').innerText = 'Rp ' + formatRupiah(kembalian);
            document.getElementById('btnCheckout').disabled = false;
        } else {
            if(kembalian < 0 && bayar > 0) {
                document.getElementById('cartKembalian').innerHTML = '<span class="text-danger">Uang Kurang!</span>';
            } else {
                document.getElementById('cartKembalian').innerText = 'Rp 0';
            }
            document.getElementById('btnCheckout').disabled = true;
        }
    }

    function showConfirmModal() {
        let myModal = new bootstrap.Modal(document.getElementById('confirmCheckoutModal'), {
            keyboard: false
        });
        myModal.show();
    }

    function processCheckout() {
        let subtotal = parseInt(document.getElementById('cartSubtotal').getAttribute('data-val')) || 0;
        let diskon = parseInt(document.getElementById('inputDiskon').value.replace(/\D/g, '')) || 0;
        let bayar = parseInt(document.getElementById('inputBayar').value.replace(/\D/g, '')) || 0;
        let total = subtotal - diskon;
        let kembalian = bayar - total;

        if (cart.length === 0) return Swal.fire({ text: 'Keranjang kosong!', icon: 'error', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-danger' } });
        if (kembalian < 0) return Swal.fire({ text: 'Uang bayar tidak cukup!', icon: 'error', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-danger' } });

        let btnModal = document.getElementById('btnProsesModal');
        btnModal.disabled = true;
        btnModal.innerHTML = '<span class="spinner-border spinner-border-sm align-middle ms-2"></span> Memproses...';

        let transactionData = {
            items: cart,
            subtotal: subtotal,
            diskon: diskon,
            total: total,
            bayar: bayar,
            kembalian: kembalian,
            timestamp: new Date().toISOString()
        };

        let isOnline = navigator.onLine && !document.getElementById('forceOfflineToggle').checked;
        if (!isOnline) {
            // Generate a local ID for offline printing
            let localTxId = 'OFF-' + Date.now();
            transactionData.transaction_id = localTxId;

            // Save to localStorage
            let offlineData = JSON.parse(localStorage.getItem('offline_transactions')) || [];
            offlineData.push(transactionData);
            localStorage.setItem('offline_transactions', JSON.stringify(offlineData));
            
            // Show Success UI for offline
            let modalEl = document.getElementById('confirmCheckoutModal');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if(modalInstance) modalInstance.hide();
            
            // Enable offline reprint
            document.getElementById('btnReprint').style.display = 'inline-block';
            document.getElementById('btnReprint').setAttribute('data-id', localTxId);
            document.getElementById('btnReprint').setAttribute('data-offline', 'true');
            
            document.querySelector('#successCheckoutModal .modal-title').innerText = 'Transaksi Tersimpan Offline!';
            document.querySelector('#successCheckoutModal p').innerText = 'Data akan disinkronkan saat koneksi kembali. Anda tetap bisa mencetak struk sekarang.';
            
            let successModal = new bootstrap.Modal(document.getElementById('successCheckoutModal'));
            successModal.show();
            
            btnModal.disabled = false;
            btnModal.innerHTML = 'Ya, Proses & Cetak';
            
            // Trigger offline print automatically
            reprintStruk();
            
            checkNetworkStatus();
            return;
        }

        fetch("{{ route('pos.process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(transactionData)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Tutup modal konfirmasi
                let modalEl = document.getElementById('confirmCheckoutModal');
                let modalInstance = bootstrap.Modal.getInstance(modalEl);
                if(modalInstance) modalInstance.hide();
                
                // Set transaction id untuk cetak ulang
                document.getElementById('btnReprint').style.display = 'inline-block';
                document.getElementById('btnReprint').setAttribute('data-id', data.transaction_id);
                document.querySelector('#successCheckoutModal .modal-title').innerText = 'Transaksi Berhasil!';
                document.querySelector('#successCheckoutModal p').innerText = 'Struk telah dicetak. Apa yang ingin Anda lakukan selanjutnya?';

                // Buka halaman print di popup otomatis (cetak pertama)
                window.open("{{ url('transaksis') }}/" + data.transaction_id + "/print", "_blank", "width=800,height=600");
                
                // Tampilkan modal sukses
                let successModal = new bootstrap.Modal(document.getElementById('successCheckoutModal'));
                successModal.show();
                
                // Kembalikan tombol di modal konfirmasi seperti semula
                btnModal.disabled = false;
                btnModal.innerHTML = 'Ya, Proses & Cetak';
            } else {
                Swal.fire({ text: 'Gagal: ' + data.message, icon: 'error', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-danger' } });
                btnModal.disabled = false;
                btnModal.innerHTML = 'Ya, Proses & Cetak';
            }
        })
        .catch(err => {
            // Fallback to offline if server is unreachable
            let localTxId = 'OFF-' + Date.now();
            transactionData.transaction_id = localTxId;
            let offlineData = JSON.parse(localStorage.getItem('offline_transactions')) || [];
            offlineData.push(transactionData);
            localStorage.setItem('offline_transactions', JSON.stringify(offlineData));
            
            let modalEl = document.getElementById('confirmCheckoutModal');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);
            if(modalInstance) modalInstance.hide();
            
            document.getElementById('btnReprint').style.display = 'inline-block';
            document.getElementById('btnReprint').setAttribute('data-id', localTxId);
            document.getElementById('btnReprint').setAttribute('data-offline', 'true');
            
            document.querySelector('#successCheckoutModal .modal-title').innerText = 'Tersimpan Offline (Gangguan Jaringan)';
            document.querySelector('#successCheckoutModal p').innerText = 'Gagal menghubungi server. Data disimpan offline. Anda tetap bisa mencetak struk.';
            
            let successModal = new bootstrap.Modal(document.getElementById('successCheckoutModal'));
            successModal.show();
            
            btnModal.disabled = false;
            btnModal.innerHTML = 'Ya, Proses & Cetak';
            
            reprintStruk();
            
            checkNetworkStatus();
        });
    }

    function syncOfflineData() {
        if (!navigator.onLine) {
            Swal.fire({ text: 'Anda masih offline!', icon: 'warning', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-warning' } });
            return;
        }
        
        let offlineData = JSON.parse(localStorage.getItem('offline_transactions')) || [];
        if (offlineData.length === 0) return;
        
        let btnSync = document.getElementById('btnSyncOffline');
        btnSync.disabled = true;
        btnSync.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Syncing...';
        
        let syncPromises = offlineData.map(tx => {
            return fetch("{{ route('pos.process') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(tx)
            }).then(res => res.json());
        });
        
        Promise.all(syncPromises)
            .then(results => {
                // Hapus data lokal setelah sukses
                localStorage.removeItem('offline_transactions');
                checkNetworkStatus();
                Swal.fire({ text: 'Berhasil sinkronisasi ' + results.length + ' transaksi!', icon: 'success', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-success' } }).then(() => {
                    window.location.reload();
                });
            })
            .catch(err => {
                Swal.fire({ text: 'Gagal sinkronisasi data. Silakan coba lagi nanti.', icon: 'error', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-danger' } });
                btnSync.disabled = false;
                checkNetworkStatus();
            });
    }

    function reprintStruk() {
        let id = document.getElementById('btnReprint').getAttribute('data-id');
        let isOffline = document.getElementById('btnReprint').getAttribute('data-offline') === 'true';
        
        if (isOffline) {
            // Render local offline print
            let offlineData = JSON.parse(localStorage.getItem('offline_transactions')) || [];
            let tx = offlineData.find(t => t.transaction_id === id);
            if (!tx) {
                Swal.fire({ text: 'Data transaksi offline tidak ditemukan!', icon: 'error', buttonsStyling: false, confirmButtonText: 'Ok', customClass: { confirmButton: 'btn btn-danger' } });
                return;
            }
            
            let printWindow = window.open('', '_blank', 'width=800,height=600');
            let itemsHtml = '';
            tx.items.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td colspan="3" class="text-left font-bold" style="padding-bottom: 2px;">${item.name}</td>
                    </tr>
                    <tr>
                        <td class="text-left">${item.qty} x</td>
                        <td class="text-left">${formatRupiah(item.harga)}</td>
                        <td class="text-right">${formatRupiah(item.harga * item.qty)}</td>
                    </tr>
                `;
            });
            
            let diskonHtml = tx.diskon > 0 ? `
                <tr>
                    <td class="text-left">Diskon:</td>
                    <td class="text-right">- Rp ${formatRupiah(tx.diskon)}</td>
                </tr>` : '';
            
            let html = `
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <title>Print Struk - ${id}</title>
                    <style>
                        @page { margin: 0; size: 58mm auto; }
                        body { font-family: 'Courier New', Courier, monospace; font-size: 12px; margin: 0; padding: 5px; width: 58mm; color: #000; }
                        .text-center { text-align: center; } .text-right { text-align: right; } .text-left { text-align: left; }
                        .font-bold { font-weight: bold; } .mb-1 { margin-bottom: 5px; } .mb-2 { margin-bottom: 10px; }
                        .mt-1 { margin-top: 5px; } .mt-2 { margin-top: 10px; } .w-100 { width: 100%; }
                        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
                        table { width: 100%; border-collapse: collapse; } th, td { padding: 2px 0; font-size: 11px; }
                        .print-btn { display: block; width: 100%; padding: 10px; margin-bottom: 15px; background: #009EF7; color: white; border: none; cursor: pointer; font-family: sans-serif; font-size: 14px; }
                        @media print { .print-btn { display: none; } }
                    </style>
                </head>
                <body>
                    <button class="print-btn" onclick="window.print()">Print Struk (Cetak)</button>
                    <div class="text-center font-bold mb-1">POS-DII (OFFLINE)</div>
                    <div class="text-center mb-2" style="font-size: 10px;">Data Belum Disinkronisasi<br>ID: ${id}</div>
                    <div class="line"></div>
                    <table class="w-100 mt-1 mb-1">
                        <tr><td class="text-left">No: ${id}</td></tr>
                        <tr><td class="text-left">Tgl: ${new Date(tx.timestamp).toLocaleString()}</td></tr>
                    </table>
                    <div class="line"></div>
                    <table class="w-100 mt-1 mb-1">
                        ${itemsHtml}
                    </table>
                    <div class="line"></div>
                    <table class="w-100 mt-1 mb-1 font-bold">
                        <tr><td class="text-left">Subtotal:</td><td class="text-right">Rp ${formatRupiah(tx.subtotal)}</td></tr>
                        ${diskonHtml}
                        <tr><td class="text-left" style="font-size: 13px;">Total:</td><td class="text-right" style="font-size: 13px;">Rp ${formatRupiah(tx.total)}</td></tr>
                    </table>
                    <div class="line"></div>
                    <table class="w-100 mt-1 mb-1">
                        <tr><td class="text-left">Tunai:</td><td class="text-right">Rp ${formatRupiah(tx.bayar)}</td></tr>
                        <tr><td class="text-left">Kembali:</td><td class="text-right">Rp ${formatRupiah(tx.kembalian)}</td></tr>
                    </table>
                    <div class="line"></div>
                    <div class="text-center mt-2 mb-2">Terima Kasih!</div>
                    <script>
                        // Auto print
                        window.onload = function() { window.print(); }
                    <\/script>
                </body>
                </html>
            `;
            printWindow.document.open();
            printWindow.document.write(html);
            printWindow.document.close();
            
        } else if(id) {
            window.open("{{ url('transaksis') }}/" + id + "/print", "_blank", "width=800,height=600");
        }
    }

    function tutupTransaksi() {
        window.location.reload();
    }
</script>
@endsection

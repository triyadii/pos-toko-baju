<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\StatusBarangController;
use App\Http\Controllers\WarnaController;
use App\Http\Controllers\UkuranController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangVarianController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\StokLogController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\StokOpnameController;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role->nama_role === 'kasir') {
            return redirect('/pos');
        }
        return redirect('/dashboard');
    }
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/webhook/wa', [\App\Http\Controllers\WaWebhookController::class, 'handle'])->name('webhook.wa');

Route::middleware('auth')->group(function () {
    Route::post('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    
    Route::get('/dashboard', function () {
        $transaksis = \App\Models\Transaksi::with(['user', 'details.barangVarian.barang'])->latest()->take(5)->get();
        $stoks = \App\Models\Stok::with(['barangVarian.barang', 'barangVarian.warna', 'barangVarian.ukuran'])
                    ->where('jumlah_stok', '<=', 5)->get();
        $pendapatanHariIni = \App\Models\Transaksi::whereDate('created_at', today())->sum('total');
        $transaksiHariIni = \App\Models\Transaksi::whereDate('created_at', today())->count();
        $totalBarang = \App\Models\BarangVarian::count();

        return view('dashboard', compact('transaksis', 'stoks', 'pendapatanHariIni', 'transaksiHariIni', 'totalBarang'));
    })->name('dashboard');

    // Superadmin Only
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':superadmin')->group(function () {
        Route::get('waservice', [\App\Http\Controllers\WaServiceController::class, 'index'])->name('waservice.index');
        Route::get('waservice/chats', [\App\Http\Controllers\WaServiceController::class, 'chats'])->name('waservice.chats');
        Route::post('waservice/start', [\App\Http\Controllers\WaServiceController::class, 'start'])->name('waservice.start');
        Route::post('waservice/send', [\App\Http\Controllers\WaServiceController::class, 'send'])->name('waservice.send');
        Route::get('waservice/chat-history/{senderId}', [\App\Http\Controllers\WaServiceController::class, 'getChatHistory'])->name('waservice.chat-history');
    });

    // Admin & Superadmin (Master Data & Inventory)
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('jenis-barangs', JenisBarangController::class);
        Route::resource('status-barangs', StatusBarangController::class);
        Route::resource('warnas', WarnaController::class);
        Route::resource('ukurans', UkuranController::class);
        Route::resource('barangs', BarangController::class);
        Route::resource('barang-varians', BarangVarianController::class);
        Route::get('stoks', [StokController::class, 'index'])->name('stoks.index');
        Route::post('stoks/adjust', [StokController::class, 'adjust'])->name('stoks.adjust');
        Route::get('stok-logs', [StokLogController::class, 'index'])->name('stok-logs.index');
        Route::resource('stok-opnames', StokOpnameController::class);
    });

    // Kasir & Superadmin (POS)
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':kasir')->group(function () {
        Route::get('/pos', function () {
            return view('pos');
        })->name('pos');
        Route::get('pos', [PosController::class, 'index'])->name('pos');
        Route::post('pos/process', [PosController::class, 'process'])->name('pos.process');
        Route::get('transaksis', [TransaksiController::class, 'index'])->name('transaksis.index');
        Route::get('transaksis/{id}/print', [TransaksiController::class, 'print'])->name('transaksis.print');
    });
});

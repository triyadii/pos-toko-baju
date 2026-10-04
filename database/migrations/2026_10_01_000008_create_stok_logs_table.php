<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('barang_varian_id')->constrained('barang_varians')->onDelete('cascade');
            $table->string('tipe'); // 'masuk', 'keluar', 'penyesuaian'
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->string('referensi_id')->nullable(); // misal ID transaksi
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_logs');
    }
};

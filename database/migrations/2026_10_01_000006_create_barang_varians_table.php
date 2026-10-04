<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_varians', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->foreignId('warna_id')->constrained('warnas')->onDelete('restrict');
            $table->foreignId('ukuran_id')->constrained('ukurans')->onDelete('restrict');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_varians');
    }
};

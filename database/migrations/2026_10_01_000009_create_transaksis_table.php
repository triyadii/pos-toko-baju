<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nomor_transaksi')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->dateTime('tanggal_transaksi');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->decimal('bayar', 15, 2);
            $table->decimal('kembalian', 15, 2);
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

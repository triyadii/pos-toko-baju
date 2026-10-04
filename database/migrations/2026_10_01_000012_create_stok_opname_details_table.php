<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_opname_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('stok_opname_id')->constrained('stok_opnames')->onDelete('cascade');
            $table->foreignId('barang_varian_id')->constrained('barang_varians')->onDelete('restrict');
            $table->integer('stok_sistem');
            $table->integer('stok_fisik');
            $table->integer('selisih');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opname_details');
    }
};

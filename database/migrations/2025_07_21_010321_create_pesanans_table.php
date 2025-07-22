<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id("pesanan_id");
            $table->unsignedBigInteger('meja_id');
            $table->bigInteger('jumlah_pesanan')->default(0);
            $table->decimal('total_harga', 10, 2)->default(0);
            $table->enum('metode_bayar', ['cash', 'cashless']);
            $table->enum('status', ['pending', 'dibayar', 'selesai'])->default('pending');
            $table->timestamps();

            // Foreignkeys dengan key eksplisit
            $table->foreign('meja_id')->references('meja_id')->on('mejas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};

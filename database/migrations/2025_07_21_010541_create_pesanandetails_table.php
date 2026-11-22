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
        Schema::create('pesanandetails', function (Blueprint $table) {
            $table->id("pesanandetail_id");
            $table->unsignedBigInteger('pesanan_id');
            $table->unsignedBigInteger('menu_id');
            $table->integer('jumlah_pesanan');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->timestamps();

            $table->foreign('pesanan_id')->references('pesanan_id')->on('pesanans')->onDelete('cascade');
            $table->foreign('menu_id')->references('menu_id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanandetails');
    }
};

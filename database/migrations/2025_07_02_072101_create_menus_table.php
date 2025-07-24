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
        Schema::create('menus', function (Blueprint $table) {
            $table->id("menu_id");
            $table->string('nama_menu');
            $table->enum('tipe', ['coffee', 'non_coffee', 'snack'])->default('coffee');
            $table->text('deskripsi')->nullable();
            $table->string('harga', 10);
            $table->string('gambar')->nullable(); // path gambar menu  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

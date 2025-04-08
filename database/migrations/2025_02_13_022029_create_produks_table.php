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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('stock', 10, 2); // Bisa menyimpan angka desimal, misalnya 1.11
            $table->decimal('harga_beli', 10, 2); // Bisa menyimpan angka desimal, misalnya 11.11
            $table->string('gambar'); 
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};

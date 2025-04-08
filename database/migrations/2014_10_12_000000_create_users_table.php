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
     
            Schema::create('users', function (Blueprint $table) {
                $table->id(); // Auto-increment primary key
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['kasir', 'admin'])->default('kasir'); // Role hanya 'kasir' atau 'admin'
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

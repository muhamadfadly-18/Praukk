<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id(); 
            $table->jsonb('id_produk');
            $table->jsonb('harga');
            $table->jsonb('qty');
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('detail_pembelian');
    }
};

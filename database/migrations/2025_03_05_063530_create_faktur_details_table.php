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
        Schema::create('faktur_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignid('faktur_id')->constrained('fakturs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('diskon');
            $table->string('nama_barang');
            $table->bigInteger('harga');
            $table->bigInteger('subtotal');
            $table->integer('qty');
            $table->integer('hasil_qty');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktur_details');
    }
};

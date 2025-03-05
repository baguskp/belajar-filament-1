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
        Schema::create('fakturs', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur');
            $table->date('tanggal');
            $table->foreignId('customer_id')->constrained('customers','id');
            $table->text('keterangan');
            $table->integer('total');
            $table->integer('charge');
            $table->integer('nominal_charge');
            $table->integer('grand_total');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fakturs');
    }
};

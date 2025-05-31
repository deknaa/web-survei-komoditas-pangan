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
        Schema::create('komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komoditas');
            $table->string('harga_komoditas');
            $table->integer('jumlah_komoditas');
            $table->integer('kebutuhan_rumah_tangga');
            $table->enum('tempat_survey', ['pasar_kediri', 'pasar_baturiti', 'pasar_pesiapan', 'pasar_tabanan']);
            $table->date('tgl_pelaksanaan');
            $table->integer('minggu_dilakukan_survey');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komoditas');
    }
};

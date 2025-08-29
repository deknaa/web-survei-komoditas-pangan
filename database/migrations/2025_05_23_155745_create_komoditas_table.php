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
            $table->float('jumlah_komoditas');
            $table->float('kebutuhan_rumah_tangga');
            $table->enum('tempat_survey', ['pasar_kediri', 'pasar_baturiti', 'pasar_pesiapan', 'pasar_tabanan']);
            $table->date('tgl_pelaksanaan');
            $table->integer('minggu_dilakukan_survey');
            $table->enum('status_verifikasi', ['belum_diverifikasi', 'sudah_diverifikasi'])->default('belum_diverifikasi');
            $table->float('neraca_pangan')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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

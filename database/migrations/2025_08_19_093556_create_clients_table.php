<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('id_client');
            $table->string('kode')->nullable();
            $table->string('nomor')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama')->nullable();
            $table->text('kordinat')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedBigInteger('id_optical_distribution')->nullable();
            $table->string('user_pppoe')->nullable();
            $table->date('tanggal_regis')->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->unsignedBigInteger('id_paket')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

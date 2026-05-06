<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('optical_distribution', function (Blueprint $table) {
            $table->id('id_optical_distribution');
            $table->string('kode')->nullable();
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->string('inputan')->nullable();
            $table->text('kordinat')->nullable();
            $table->string('estimasi_redaman_input')->nullable();
            $table->string('estimasi_redaman_output')->nullable();
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('optical_distribution');
    }
};

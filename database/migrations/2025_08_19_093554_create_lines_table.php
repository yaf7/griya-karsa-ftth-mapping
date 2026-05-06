<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('line', function (Blueprint $table) {
            $table->id('id_line');
            $table->unsignedBigInteger('id_optical_distribution')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('kordinat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('line');
    }
};

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
        Schema::create('pupuk', function (Blueprint $table) {
            $table->id('pupuk_id');
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('jenis');
            $table->integer('berat');
            $table->decimal('harga', 15, 2);
            $table->integer('stok');
            $table->text('deskripsi');
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
            $table->boolean('unggulan')
                ->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pupuk');
    }
};

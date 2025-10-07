<?php

use App\Models\Pesanan;
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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('pengiriman_id');
            $table->foreignIdFor(Pesanan::class, 'pesanan_id')->constrained()->onDelete('cascade');
            $table->string('nama_penerima');
            $table->string('telepon');
            $table->string('alamat');
            $table->decimal('ongkir');
            $table->dateTime('tgl_kirim');
            $table->dateTime('tgl_terima');
            $table->enum('status', ['pending', 'shipped', 'delivered']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};

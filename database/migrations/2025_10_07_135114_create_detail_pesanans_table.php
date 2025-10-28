<?php

use App\Models\Pesanan;
use App\Models\Pupuk;
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
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('detailPesanan_id');
            $table->foreignIdFor(Pesanan::class, 'pesanan_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Pupuk::class, 'pupuk_id')->constrained()->onDelete('cascade');
            $table->integer('qty_karung');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};

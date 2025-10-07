<?php

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
        Schema::create('produksi', function (Blueprint $table) {
            $table->id('produksi_id');
            $table->foreignIdFor(Pupuk::class, 'barang_id')->constrained()->onDelete('cascade');
            $table->dateTime('tanggal_produksi');
            $table->integer('jumlah_karung');
            $table->enum('status_produksi', ['proses', 'selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};

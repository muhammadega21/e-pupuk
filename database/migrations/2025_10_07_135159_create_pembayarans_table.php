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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('pembayaran_id');
            $table->foreignIdFor(Pesanan::class, 'pesanan_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('metode', ['cash', 'transfer']);
            $table->decimal('total_bayar', 15, 2);
            $table->string('bukti_url')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};

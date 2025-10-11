<?php

use App\Models\User;
use App\Models\UserData;
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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('pesanan_id');
            $table->foreignIdFor(UserData::class, 'data_user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'created_by')->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class, 'handled_by')->nullable()->constrained()->nullOnDelete();
            $table->integer('order_no')->unique();
            $table->enum('channel', ['online', 'store']);
            $table->enum('order_type', ['delivery', 'pickup']);
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'failed', 'redunded']);
            $table->enum('fulfillment_status', ['new', 'processing', 'shipped', 'delivered', 'canceled']);
            $table->integer('total_karung');
            $table->decimal('total_bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};

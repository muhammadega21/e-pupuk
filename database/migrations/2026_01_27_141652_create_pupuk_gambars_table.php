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
        Schema::create('pupuk_gambar', function (Blueprint $table) {
            $table->id('pupuk_gambar_id');
            $table->foreignIdFor(Pupuk::class, 'pupuk_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('gambar_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pupuk_gambar');
    }
};

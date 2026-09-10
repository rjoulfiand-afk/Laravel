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
    Schema::create('wallets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable();
        $table->string('provider');       // BCA, DANA, GoPay, OVO, dll
        $table->string('account_number'); // Nomor rekening / No HP
        $table->string('account_name');   // Atas nama siapa
        $table->string('qr_image_path')->nullable(); // Jalur foto QRIS di server
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};

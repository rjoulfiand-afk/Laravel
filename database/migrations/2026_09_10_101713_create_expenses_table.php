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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable(); // Pemilik pengeluaran
        $table->integer('amount');                // Laci buat nominal uang keluar
        $table->string('description')->nullable(); // Laci buat keterangan (makan, bensin, dll)
        $table->timestamps();                     // Laci waktu (buat terminal log)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

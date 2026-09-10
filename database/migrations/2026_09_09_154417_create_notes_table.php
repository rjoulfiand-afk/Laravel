<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('notes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable(); // Sambungan ke user
        $table->string('title')->nullable();      // Laci buat Judul
        $table->text('content');                  // Laci buat Isi Catatan (INI YANG BIKIN ERROR TADI!)
        $table->string('color')->nullable();      // Laci buat warna gelembung
        $table->string('icon')->nullable();       // Laci buat icon gelembung
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
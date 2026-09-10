<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(); // Laci akun
            $table->string('title');                  // Laci judul tugas
            $table->string('priority')->nullable();   // Laci tingkat prioritas (santai/normal/mendesak)
            $table->string('due_date')->nullable();   // Laci tenggat waktu
            $table->text('detail')->nullable();       // Laci rincian tugas
            $table->boolean('is_completed')->default(false); // Laci status kelar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
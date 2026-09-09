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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // Nama tugasnya
            $table->text('description')->nullable(); // Detail tugas (nullable = boleh dikosongin)
            $table->date('due_date')->nullable(); // Tenggat waktu / deadline
            $table->boolean('is_done')->default(false); // Status selesai atau belum (default: belum/false)
            $table->timestamp('completed_at')->nullable(); // Kapan diselesaikannya (buat pemicu logo piagam)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
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
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Buat nyambungin ke user yang login
            $table->decimal('amount', 12, 2); // Buat nyatet jumlah uang
            $table->string('purpose'); // Buat nyatet tabungan ini untuk apa
            $table->date('saved_at'); // Buat nyatet tanggal nabungnya


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};
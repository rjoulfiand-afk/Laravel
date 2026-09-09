<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // 👇 TAMBAHIN BARIS INI BIAR DIA TAU NAMA TABEL BARUNYA 👇
    protected $table = 'daily_tasks'; 
    
    // (Biar sekalian aman, kita tambahin fillable-nya juga)
    protected $fillable = ['user_id', 'title', 'description', 'due_date', 'is_done', 'completed_at'];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    // Ini buat ngasih tau Laravel nama tabel aslinya biar ngga sok inggris wkwk
    protected $table = 'peminjamans';
    
    protected $guarded = [];
}
<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class Tabungan extends Component
{
    public function simpan($uangStr = '', $keterangan = '')
    {
        $amount = (int) preg_replace('/[^0-9]/', '', $uangStr);

        if ($amount <= 0) {
            return; 
        }

        // Tiket VVIP otomatis
        $user = User::firstOrCreate(
            ['email' => 'boss@harian.com'],
            ['name' => 'Boss Jul', 'password' => bcrypt('rahasia123')]
        );

        // Masukin ke Database
        DB::table('savings')->insert([
            'user_id' => $user->id,
            'amount' => $amount,
            'purpose' => $keterangan == '' ? 'Tabungan Rutin' : $keterangan,
            'saved_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function render()
    {
        // 🚀 GPS GANDA BIAR LARAVEL NGGA BISA ALASAN "NOT FOUND" LAGI!
        if (view()->exists('livewire.tabungan')) {
            return view('livewire.tabungan');
        }
        return view('components.tabungan');
    }
}
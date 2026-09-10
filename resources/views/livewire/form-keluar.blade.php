<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

new class extends Component
{
    public function simpan($nominalStr, $keterangan)
    {
        // Bersihin titik dan tulisan Rp biar jadi angka murni
        $nominal = (int) preg_replace('/[^0-9]/', '', (string) $nominalStr);

        // Kalau nol atau kosong, batalin
        if ($nominal <= 0) {
            return;
        }

        $user = User::where('email', 'boss@harian.com')->first();

        // Simpan ke tabel expenses (pengeluaran)
        DB::table('expenses')->insert([
            'user_id' => $user->id,
            'amount' => $nominal,
            'description' => empty(trim($keterangan)) ? 'Lain-lain' : $keterangan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
?>

<div>
    <div x-cloak x-show="activeForm === 'keluar'" class="fixed inset-0 z-[60] flex items-end justify-center pointer-events-none">
        <div x-show="activeForm === 'keluar'" x-transition.opacity @click="activeForm = ''" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm pointer-events-auto"></div>
        <div x-show="activeForm === 'keluar'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="relative w-full h-[95vh] bg-white rounded-t-[2.5rem] shadow-2xl pointer-events-auto flex flex-col">
            
            <div class="flex items-center gap-4 p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white rounded-t-[2.5rem] relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-200 opacity-30 rounded-full blur-xl"></div>
                <button type="button" @click="backToMenu()" class="relative z-10 w-10 h-10 bg-white shadow-sm border border-red-100 rounded-full text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fas fa-chevron-left text-lg"></i></button>
                <div class="flex-1 relative z-10"><h3 class="text-xl font-extrabold text-gray-900">Pengeluaran</h3><p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Waduh, Jajan Apa Nih?</p></div>
                <div class="relative z-10 w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-500 transform -rotate-12"><i class="fas fa-receipt text-2xl"></i></div>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1">
                <div class="bg-gray-900 rounded-3xl p-6 mb-6 relative overflow-hidden shadow-[0_15px_30px_rgba(220,38,38,0.15)]">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-red-500 opacity-20 rounded-full blur-3xl -mr-10 -mt-10"></div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 text-center">Nominal Keluar</label>
                    <div class="flex items-center justify-center gap-2 border-b-2 border-gray-700 pb-2 focus-within:border-red-400 transition-colors">
                        <span class="text-red-400 font-extrabold text-3xl">Rp</span>
                        <input type="text" inputmode="numeric" x-model="uangKeluarStr" @input="formatRupiahKeluar($event)" placeholder="0" class="bg-transparent w-full max-w-[220px] text-center text-5xl font-black text-white focus:outline-none placeholder-gray-600">
                    </div>
                </div>
                
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fas fa-bolt text-red-500 mr-1"></i> Nominal Cepat</label>
                <div class="grid grid-cols-3 gap-3 mb-8">
                    <button type="button" @click="tambahUangKeluar(10000)" class="py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-sm transition-colors">+ 10rb</button>
                    <button type="button" @click="tambahUangKeluar(20000)" class="py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-sm transition-colors">+ 20rb</button>
                    <button type="button" @click="tambahUangKeluar(50000)" class="py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-sm transition-colors">+ 50rb</button>
                    <button type="button" @click="tambahUangKeluar(100000)" class="py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-bold text-sm transition-colors">+ 100rb</button>
                    <button type="button" @click="tambahUangKeluar(0, true)" class="py-3 col-span-2 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-xl font-bold text-sm transition-colors"><i class="fas fa-eraser mr-1"></i> Reset Uang</button>
                </div>
                
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fas fa-tag text-red-500 mr-1"></i> Buat Apa?</label>
                <div class="flex gap-2 mb-3 overflow-x-auto pb-2">
                    <button type="button" @click="keteranganKeluar = 'Makan / Minum'" :class="keteranganKeluar === 'Makan / Minum' ? 'bg-gray-800 text-white' : 'bg-white border-2 border-gray-100 text-gray-500'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all">Makan / Minum</button>
                    <button type="button" @click="keteranganKeluar = 'Bensin / Parkir'" :class="keteranganKeluar === 'Bensin / Parkir' ? 'bg-gray-800 text-white' : 'bg-white border-2 border-gray-100 text-gray-500'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all">Bensin / Parkir</button>
                    <button type="button" @click="keteranganKeluar = 'Kuota Internet'" :class="keteranganKeluar === 'Kuota Internet' ? 'bg-gray-800 text-white' : 'bg-white border-2 border-gray-100 text-gray-500'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all">Kuota Internet</button>
                </div>
                <input type="text" x-model="keteranganKeluar" placeholder="Ketik rincian pengeluaranmu..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-800 focus:outline-none focus:border-red-500">
            </div>
            
            <div class="p-6 border-t border-gray-50 bg-white">
                <button type="button" @click="$wire.simpan(uangKeluarStr, keteranganKeluar).then(() => { backToMenu(); uangKeluarStr = ''; keteranganKeluar = ''; })" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-lg py-4 rounded-2xl flex justify-center items-center gap-2 shadow-[0_10px_20px_rgba(220,38,38,0.2)] transition-all active:scale-95">
                    <span wire:loading.remove wire:target="simpan"><i class="fas fa-minus-circle"></i> Catat Pengeluaran</span>
                    <span wire:loading wire:target="simpan"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                </button>
            </div>
            
        </div>
    </div>
</div>
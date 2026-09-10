<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

new class extends Component
{
    public function simpan($judul, $isi)
    {
        // Kalau judul dan isi kosong dua-duanya, ngapain disimpen yak wkwk
        if (empty(trim($judul)) && empty(trim($isi))) {
            return;
        }

        $user = User::firstOrCreate(
            ['email' => 'boss@harian.com'],
            ['name' => 'Boss Jul', 'password' => bcrypt('rahasia123')]
        );

        // Simpan ide ke tabel 'notes'
        DB::table('notes')->insert([
            'user_id' => $user->id,
            'title' => $judul ?: 'Ide Dadakan', // Judul default kalau lupa ngisi
            'content' => $isi,
            'color' => 'from-blue-400 to-cyan-300', // Warna gelembung default
            'icon' => 'fa-lightbulb', // Icon default
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
?>

<div x-data="{ noteTitle: '', noteContent: '' }">
    <div x-cloak x-show="activeForm === 'catatan'" class="fixed inset-0 z-[60] flex items-end justify-center pointer-events-none">
        <div x-show="activeForm === 'catatan'" x-transition.opacity @click="activeForm = ''" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm pointer-events-auto"></div>
        <div x-show="activeForm === 'catatan'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="relative w-full h-[90vh] bg-white rounded-t-[2.5rem] shadow-2xl pointer-events-auto flex flex-col">
            
            <div class="flex items-center gap-4 p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white rounded-t-[2.5rem]">
                <button type="button" @click="backToMenu()" class="w-10 h-10 bg-white shadow-sm border border-blue-100 rounded-full text-blue-600 flex items-center justify-center hover:bg-blue-50 transition-colors"><i class="fas fa-chevron-left text-lg"></i></button>
                <div class="flex-1"><h3 class="text-xl font-extrabold text-gray-900">Catatan Baru</h3><p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Simpan Ide & Rencanamu</p></div>
                <div class="w-10 h-10 bg-blue-100 text-blue-500 rounded-xl flex items-center justify-center"><i class="fas fa-book-open text-xl"></i></div>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1 flex flex-col">
                <input type="text" x-model="noteTitle" placeholder="Judul Catatan..." class="w-full bg-transparent text-3xl font-extrabold text-gray-900 placeholder-gray-300 focus:outline-none mb-4 pb-2 border-b-2 border-transparent focus:border-blue-400 transition-colors">
                <textarea x-model="noteContent" placeholder="Mulai mengetik ide cemerlangmu di sini..." class="w-full flex-1 bg-transparent text-gray-700 font-medium text-base resize-none focus:outline-none paper-style p-2"></textarea>
            </div>
            
            <div class="p-6 border-t border-gray-50 bg-white">
                <button type="button" @click="$wire.simpan(noteTitle, noteContent).then(() => { backToMenu(); noteTitle = ''; noteContent = ''; })" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg py-4 rounded-2xl flex justify-center items-center gap-2 shadow-[0_10px_20px_rgba(37,99,235,0.2)] transition-all active:scale-95">
                    <span wire:loading.remove wire:target="simpan"><i class="fas fa-save"></i> Simpan Catatan</span>
                    <span wire:loading wire:target="simpan"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>
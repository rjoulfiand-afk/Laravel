<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

new class extends Component
{
    public function simpan($judul, $prioritas, $tanggal, $detail)
    {
        // Kalau judul kosong, batalin aja ngga usah disimpen
        if (empty(trim($judul))) {
            return;
        }

        // Tiket VVIP biar SQLite ngga nolak
        $user = User::firstOrCreate(
            ['email' => 'boss@harian.com'],
            ['name' => 'Boss Jul', 'password' => bcrypt('rahasia123')]
        );

        // Simpan misi ke tabel 'tasks'
        DB::table('tasks')->insert([
            'user_id' => $user->id,
            'title' => $judul,
            'priority' => $prioritas,
            'due_date' => $tanggal,
            'detail' => $detail,
            'is_completed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
?>

<div x-data="{ taskTitle: '', taskDetail: '', customDateVal: '' }">
    <div x-cloak x-show="activeForm === 'tugas'" class="fixed inset-0 z-[60] flex items-end justify-center pointer-events-none">
        <div x-show="activeForm === 'tugas'" x-transition.opacity @click="activeForm = ''" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm pointer-events-auto"></div>
        <div x-show="activeForm === 'tugas'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="relative w-full h-[90vh] bg-white rounded-t-[2.5rem] shadow-2xl pointer-events-auto flex flex-col">
            
            <div class="flex items-center gap-4 p-6 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-white rounded-t-[2.5rem]">
                <button type="button" @click="backToMenu()" class="w-10 h-10 bg-white shadow-sm border border-orange-100 rounded-full text-orange-600 flex items-center justify-center hover:bg-orange-50 transition-colors"><i class="fas fa-chevron-left text-lg"></i></button>
                <div class="flex-1"><h3 class="text-xl font-extrabold text-gray-900">Misi Baru</h3><p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Selesaikan & Dapatkan Piagam</p></div>
                <i class="fas fa-award text-3xl text-orange-300 opacity-50"></i>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1">
                <input type="text" x-model="taskTitle" placeholder="Tulis tugasmu disini..." class="w-full bg-transparent text-2xl font-extrabold text-gray-900 placeholder-gray-300 focus:outline-none mb-8 border-b-2 border-dashed border-gray-200 pb-3 focus:border-orange-400">
                
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fas fa-fire text-orange-500 mr-1"></i> Seberapa Penting?</label>
                <div class="grid grid-cols-3 gap-3 mb-8">
                    <button type="button" @click="taskPriority = 'santai'" :class="taskPriority === 'santai' ? 'bg-green-500 text-white shadow-lg border-green-500' : 'bg-white text-gray-400 border-gray-200'" class="py-3 rounded-xl border-2 font-bold text-sm flex flex-col items-center gap-1 transition-colors"><i class="fas fa-coffee"></i> Santai</button>
                    <button type="button" @click="taskPriority = 'normal'" :class="taskPriority === 'normal' ? 'bg-blue-500 text-white shadow-lg border-blue-500' : 'bg-white text-gray-400 border-gray-200'" class="py-3 rounded-xl border-2 font-bold text-sm flex flex-col items-center gap-1 transition-colors"><i class="fas fa-book"></i> Normal</button>
                    <button type="button" @click="taskPriority = 'mendesak'" :class="taskPriority === 'mendesak' ? 'bg-red-500 text-white shadow-lg border-red-500' : 'bg-white text-gray-400 border-gray-200'" class="py-3 rounded-xl border-2 font-bold text-sm flex flex-col items-center gap-1 transition-colors"><i class="fas fa-bolt"></i> Mendesak</button>
                </div>
                
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fas fa-clock text-orange-500 mr-1"></i> Kapan Dikumpulkan?</label>
                <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
                    <button type="button" @click="taskDate = 'hari_ini'" :class="taskDate === 'hari_ini' ? 'bg-gray-800 text-white shadow-md' : 'bg-gray-100 text-gray-600'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-colors">Hari Ini</button>
                    <button type="button" @click="taskDate = 'besok'" :class="taskDate === 'besok' ? 'bg-gray-800 text-white shadow-md' : 'bg-gray-100 text-gray-600'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-colors">Besok</button>
                    <button type="button" @click="taskDate = 'custom'" :class="taskDate === 'custom' ? 'bg-gray-800 text-white shadow-md' : 'bg-gray-100 text-gray-600'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap flex items-center gap-2 transition-colors"><i class="fas fa-calendar-alt"></i> Pilih Tanggal</button>
                </div>
                <div x-show="taskDate === 'custom'" x-collapse>
                    <input type="date" x-model="customDateVal" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-800 mb-6 focus:outline-none focus:border-orange-400">
                </div>
                
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mt-4 mb-2"><i class="fas fa-pen text-orange-500 mr-1"></i> Detail (Opsional)</label>
                <textarea x-model="taskDetail" rows="4" placeholder="Misal: Tugas dari Pak Guru..." class="w-full bg-yellow-50/50 border border-yellow-100 rounded-xl py-2 px-4 font-medium text-gray-700 resize-none paper-style focus:outline-none focus:border-orange-300"></textarea>
            </div>
            
            <div class="p-6 border-t border-gray-50 bg-white">
                <button type="button" @click="$wire.simpan(taskTitle, taskPriority, taskDate === 'custom' ? customDateVal : taskDate, taskDetail).then(() => { backToMenu(); taskTitle = ''; taskDetail = ''; customDateVal = ''; })" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold text-lg py-4 rounded-2xl flex justify-center items-center gap-2 transition-all active:scale-95 shadow-[0_10px_20px_rgba(249,115,22,0.2)]">
                    <span wire:loading.remove wire:target="simpan"><i class="fas fa-paper-plane"></i> Tambahkan Misi</span>
                    <span wire:loading wire:target="simpan"><i class="fas fa-spinner fa-spin"></i> Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>
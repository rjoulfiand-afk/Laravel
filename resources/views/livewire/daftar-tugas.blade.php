<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

new class extends Component
{
    public $filter = 'aktif'; // Pilihannya: 'aktif' atau 'selesai'
    public $tasks = [];

    public function boot()
    {
        $this->muatTugas();
    }

    public function muatTugas()
    {
        $user = User::where('email', 'boss@harian.com')->first();
        if ($user) {
            $isCompleted = $this->filter === 'selesai';
            
            $this->tasks = DB::table('tasks')
                ->where('user_id', $user->id)
                ->where('is_completed', $isCompleted)
                // Urutin dari prioritas tertinggi ke terendah
                ->orderByRaw("CASE WHEN priority = 'mendesak' THEN 1 WHEN priority = 'normal' THEN 2 ELSE 3 END")
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function setFilter($status)
    {
        $this->filter = $status;
        $this->muatTugas();
    }

    public function ubahStatus($id, $statusSelesai)
    {
        DB::table('tasks')->where('id', $id)->update(['is_completed' => $statusSelesai, 'updated_at' => now()]);
        $this->muatTugas();
    }

    public function hapusTugas($id)
    {
        DB::table('tasks')->where('id', $id)->delete();
        $this->muatTugas();
    }
};
?>

<div>
    <!-- Overlay Transparan -->
    <div x-cloak x-show="activeForm === 'daftar-tugas'" class="fixed inset-0 z-[80] flex justify-end pointer-events-none">
        <div x-show="activeForm === 'daftar-tugas'" x-transition.opacity @click="activeForm = ''" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm pointer-events-auto"></div>
        
        <!-- PANEL GESER DARI KANAN (IPHONE STYLE) -->
        <div x-show="activeForm === 'daftar-tugas'" 
             x-transition:enter="transition ease-out duration-300 transform" 
             x-transition:enter-start="translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in duration-200 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="translate-x-full" 
             class="relative w-full h-screen bg-gray-50 shadow-2xl pointer-events-auto flex flex-col overflow-hidden">
            
            <!-- HEADER (Tombol Back Panah Kiri) -->
            <div class="flex items-center gap-4 p-6 border-b border-gray-200 bg-white relative z-10 pt-10"> <!-- Ditambah pt-10 biar ga nabrak notch HP -->
                <button type="button" @click="backToMenu()" class="w-10 h-10 bg-gray-50 border border-gray-200 rounded-full text-gray-600 flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
                <div class="flex-1">
                    <h3 class="text-xl font-extrabold text-gray-900">Markas Misi</h3>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Daftar Pekerjaan & Tugas</p>
                </div>
                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-clipboard-list text-lg"></i>
                </div>
            </div>

            <!-- FILTER TABS -->
            <div class="px-6 pt-5 pb-2 bg-white border-b border-gray-100">
                <div class="flex bg-gray-100 rounded-xl p-1 shadow-inner">
                    <button wire:click="setFilter('aktif')" class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all {{ $filter === 'aktif' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-fire text-orange-500 mr-1"></i> Sedang Aktif
                    </button>
                    <button wire:click="setFilter('selesai')" class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all {{ $filter === 'selesai' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <i class="fas fa-check-circle text-emerald-500 mr-1"></i> Sudah Kelar
                    </button>
                </div>
            </div>
            
            <!-- AREA DAFTAR TUGAS -->
            <!-- wire:poll.2s biar kalau lu nambah tugas dari tombol (+), layarnya otomatis update! -->
            <div class="p-6 overflow-y-auto flex-1 space-y-4" wire:poll.2s="muatTugas">
                
                @if(count($tasks) == 0)
                    <div class="p-8 bg-white rounded-3xl border-2 border-dashed border-gray-200 text-center flex flex-col items-center justify-center mt-4">
                        <i class="fas {{ $filter === 'aktif' ? 'fa-mug-hot text-gray-300' : 'fa-box-open text-gray-300' }} text-4xl mb-3"></i>
                        <p class="text-gray-500 font-bold text-sm">{{ $filter === 'aktif' ? 'Misi bersih boss! Waktunya santai.' : 'Belum ada misi yang selesai.' }}</p>
                        @if($filter === 'aktif')
                            <p class="text-gray-400 text-[10px] mt-1">Klik tombol (+) di bawah untuk nambah tugas baru.</p>
                        @endif
                    </div>
                @else
                    @foreach($tasks as $task)
                        @php
                            // Pewarnaan Badge Dinamis
                            $badgeBg = 'bg-blue-50'; $badgeText = 'text-blue-600'; $badgeBorder = 'border-blue-100'; $strip = 'bg-blue-500';
                            if($task->priority == 'mendesak') { $badgeBg = 'bg-red-50'; $badgeText = 'text-red-600'; $badgeBorder = 'border-red-100'; $strip = 'bg-red-500'; }
                            elseif($task->priority == 'santai') { $badgeBg = 'bg-green-50'; $badgeText = 'text-green-600'; $badgeBorder = 'border-green-100'; $strip = 'bg-green-500'; }
                            $modalColor = $badgeBg . ' ' . $badgeText;
                        @endphp

                        <!-- KARTU TUGAS BISA DIKLIK -->
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 relative overflow-hidden group transition-all hover:border-gray-300 hover:shadow-md cursor-pointer"
                             @click="$dispatch('buka-gelembung', {
                                 id: {{ $task->id }},
                                 title: {{ json_encode($task->title) }},
                                 text: {{ json_encode($task->detail ?? 'Tidak ada detail khusus untuk misi ini.') }},
                                 date: 'Tenggat: {{ str_replace('_', ' ', $task->due_date) ?: 'Kapan aja' }}',
                                 color: '{{ $modalColor }}',
                                 icon: 'fa-clipboard-list'
                             })">
                            
                            <!-- Strip Warna Prioritas di Kiri -->
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $filter === 'selesai' ? 'bg-gray-300' : $strip }}"></div>
                            
                            <div class="flex justify-between items-start gap-3 pl-2">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if($filter === 'aktif')
                                            <span class="text-[8px] font-black uppercase tracking-widest {{ $badgeText }} {{ $badgeBg }} px-1.5 py-0.5 rounded border {{ $badgeBorder }}">
                                                {{ $task->priority ?: 'Normal' }}
                                            </span>
                                        @else
                                            <span class="text-[8px] font-black uppercase tracking-widest text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200">
                                                SELESAI
                                            </span>
                                        @endif
                                        <span class="text-[9px] font-bold text-gray-400 truncate"><i class="far fa-clock mr-0.5"></i> {{ str_replace('_', ' ', $task->due_date) ?: 'Kapan aja' }}</span>
                                    </div>
                                    <h4 class="font-bold text-sm truncate {{ $filter === 'selesai' ? 'text-gray-400 line-through' : 'text-gray-900' }}">{{ $task->title }}</h4>
                                    @if($task->detail)
                                        <p class="text-[10px] font-medium mt-1 truncate {{ $filter === 'selesai' ? 'text-gray-300' : 'text-gray-500' }}">{{ $task->detail }}</p>
                                    @endif
                                </div>
                                
                                <!-- TOMBOL AKSI (WAJIB PAKAI @click.stop BIAR GA BENTROK SAMA KLIK KARTU) -->
                                <div class="flex flex-col gap-2 shrink-0">
                                    @if($filter === 'aktif')
                                        <button wire:click.stop="ubahStatus({{ $task->id }}, true)" @click.stop class="w-8 h-8 rounded-full border-2 border-gray-100 flex items-center justify-center text-gray-300 hover:border-emerald-500 hover:text-emerald-500 hover:bg-emerald-50 transition-colors shadow-sm bg-gray-50" title="Selesaikan">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </button>
                                    @else
                                        <button wire:click.stop="ubahStatus({{ $task->id }}, false)" @click.stop class="w-8 h-8 rounded-full border-2 border-gray-100 flex items-center justify-center text-gray-400 hover:border-orange-500 hover:text-orange-500 hover:bg-orange-50 transition-colors shadow-sm bg-gray-50" title="Batal Selesai">
                                            <i class="fas fa-undo-alt text-[10px]"></i>
                                        </button>
                                    @endif
                                    
                                    <button wire:click.stop="hapusTugas({{ $task->id }})" @click.stop class="w-8 h-8 rounded-full border-2 border-gray-100 flex items-center justify-center text-gray-300 hover:border-red-500 hover:text-red-500 hover:bg-red-50 transition-colors shadow-sm bg-gray-50" title="Hapus Tugas">
                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                
            </div>
        </div>
    </div>
</div>
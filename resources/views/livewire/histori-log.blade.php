<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $activityLogs = [];

    // Fungsi dijalankan otomatis pas komponen dipanggil
    public function boot() {
        $this->muatHistori();
    }

    // Mesin Penarik Data Histori
    public function muatHistori() {
        $user = DB::table('users')->where('email', 'boss@harian.com')->first();
        if ($user) {
            try {
                $logsRaw = DB::table('activity_logs')
                            ->where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                
                $this->activityLogs = [];
                foreach($logsRaw as $l) {
                    // Logika Pengelompokan Tanggal Otomatis
                    if (\Carbon\Carbon::parse($l->created_at)->isToday()) {
                        $dateKey = 'HARI INI';
                    } elseif (\Carbon\Carbon::parse($l->created_at)->isYesterday()) {
                        $dateKey = 'KEMARIN';
                    } else {
                        $dateKey = \Carbon\Carbon::parse($l->created_at)->format('d M Y');
                    }
                    
                    $this->activityLogs[$dateKey][] = $l;
                }
            } catch (\Exception $e) { 
                $this->activityLogs = []; 
            }
        }
    }
};
?>

<!-- SIHIR WIRE:POLL = Otomatis update data tanpa refresh halaman! -->
<div wire:poll.2s="muatHistori">
    <!-- Panel Histori (Slide dari Kanan / Bawah) -->
    <div x-cloak x-show="activeForm === 'histori'" class="fixed inset-0 z-[95] flex justify-end pointer-events-none">
        
        <!-- Background Blur (Lebih Terang & Elegan) -->
        <div x-show="activeForm === 'histori'" x-transition.opacity.duration.400ms @click="activeForm = ''; setTimeout(() => $dispatch('buka-lobi'), 300)" class="absolute inset-0 bg-gray-900/30 backdrop-blur-sm pointer-events-auto"></div>
        
        <!-- Wadah Panel (Clean, Minimalist, Bright) -->
        <div x-show="activeForm === 'histori'" 
             x-transition:enter="transition ease-out duration-400 transform" x-transition:enter-start="translate-x-full md:translate-y-full md:translate-x-0" x-transition:enter-end="translate-x-0 md:translate-y-0" 
             x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0 md:translate-y-0" x-transition:leave-end="translate-x-full md:translate-y-full md:translate-x-0" 
             class="relative w-full max-w-md bg-[#f8fafc] shadow-[0_0_40px_rgba(0,0,0,0.1)] pointer-events-auto flex flex-col h-full overflow-hidden md:border-l border-gray-200 md:rounded-l-3xl rounded-t-[2.5rem] md:rounded-t-none md:mt-0 mt-10">
            
            <!-- HEADER PREMIUM -->
            <div class="px-7 py-6 bg-white/90 backdrop-blur-xl border-b border-gray-100 flex items-center justify-between z-20 shadow-sm relative">
                <div class="flex items-center gap-4">
                    <!-- LOGIKA BACK: KEMBALI KE LOBI -->
                    <button @click="activeForm = ''; setTimeout(() => $dispatch('buka-lobi'), 300)" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-800 transition-colors shadow-sm">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Riwayat Sistem</h2>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Sinkronisasi Realtime</p>
                        </div>
                    </div>
                </div>
                <!-- Indikator Animasi Halus -->
                <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-500 relative">
                    <div class="absolute inset-0 rounded-full border border-blue-400 animate-ping opacity-20"></div>
                    <i class="fas fa-list-ul text-sm"></i>
                </div>
            </div>

            <!-- AREA SCROLL TIMELINE -->
            <div class="flex-1 overflow-y-auto p-6 relative z-10 premium-scroll">
                
                @forelse($activityLogs as $tanggal => $logs)
                    <div class="mb-10 relative">
                        <!-- Garis Waktu (Pill Tanggal Elegan) -->
                        <div class="flex items-center gap-4 mb-8">
                            <span class="text-[10px] font-black text-gray-500 bg-white px-4 py-1.5 rounded-full uppercase tracking-widest border border-gray-200 shadow-sm z-10">
                                {{ $tanggal }}
                            </span>
                            <div class="flex-1 h-px bg-gray-200"></div>
                        </div>

                        <!-- Wadah Item Timeline dengan Garis Lurus -->
                        <div class="space-y-6 relative before:absolute before:inset-0 before:ml-6 before:-translate-x-px before:h-full before:w-0.5 before:bg-gray-200">

                            @foreach($logs as $log)
                                @php
                                    // Dinamis Warna Minimalis Modern
                                    $iconStyle = match($log->color) {
                                        'red' => 'bg-red-50 text-red-500 border-red-100 shadow-[0_2px_10px_rgba(239,68,68,0.1)]',
                                        'emerald' => 'bg-emerald-50 text-emerald-500 border-emerald-100 shadow-[0_2px_10px_rgba(16,185,129,0.1)]',
                                        'blue' => 'bg-blue-50 text-blue-500 border-blue-100 shadow-[0_2px_10px_rgba(59,130,246,0.1)]',
                                        default => 'bg-gray-50 text-gray-500 border-gray-100 shadow-[0_2px_10px_rgba(107,114,128,0.1)]'
                                    };
                                    $borderCardHover = match($log->color) {
                                        'red' => 'group-hover:border-red-200',
                                        'emerald' => 'group-hover:border-emerald-200',
                                        'blue' => 'group-hover:border-blue-200',
                                        default => 'group-hover:border-gray-300'
                                    };
                                @endphp

                                <div class="relative flex items-start gap-4 group cursor-default">
                                    <!-- Ikon Timeline -->
                                    <div class="relative z-10 w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 border {{ $iconStyle }} transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">
                                        <i class="fas {{ $log->icon }} text-base"></i>
                                    </div>
                                    
                                    <!-- Kartu Konten Bersih & Elegan -->
                                    <div class="flex-1 bg-white border border-gray-100 {{ $borderCardHover }} rounded-2xl p-4 transition-all duration-300 shadow-sm group-hover:shadow-md relative overflow-hidden">
                                        
                                        <!-- Header Kartu -->
                                        <div class="flex justify-between items-start mb-1.5">
                                            <h4 class="font-extrabold text-gray-800 text-sm">{{ $log->title }}</h4>
                                            <span class="text-[9px] font-bold text-gray-400 flex items-center gap-1">
                                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}
                                            </span>
                                        </div>
                                        
                                        <!-- Deskripsi -->
                                        <p class="text-[10px] text-gray-500 font-medium leading-relaxed">{{ $log->description }}</p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @empty
                    <!-- State Kalau Kosong (Clean Aesthetic) -->
                    <div class="h-full flex flex-col items-center justify-center text-center pb-20 pt-10">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 border border-gray-100 shadow-sm relative">
                            <i class="fas fa-wind text-4xl text-gray-300 absolute z-10"></i>
                            <div class="absolute inset-[-10px] border border-gray-100 rounded-full animate-pulse"></div>
                        </div>
                        <h4 class="text-gray-800 font-extrabold text-lg tracking-wide mb-1">Riwayat Bersih</h4>
                        <p class="text-xs text-gray-400 font-medium max-w-[200px] mx-auto">Belum ada aktivitas, penghapusan, atau tugas yang terekam.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</div>
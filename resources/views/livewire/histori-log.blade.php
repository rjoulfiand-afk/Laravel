<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $activityLogs = [];

    protected $listeners = ['refreshHistori' => 'muatHistori'];
    
    public function restoreData($logId) {
        $log = DB::table('activity_logs')->where('id', $logId)->first();
        if ($log && $log->table_name && $log->payload) {
            $dataAsli = json_decode($log->payload, true);
            unset($dataAsli['id']); 
            
            DB::table($log->table_name)->insert($dataAsli); 
            DB::table('activity_logs')->where('id', $logId)->delete(); 
            
            $this->muatHistori();
            $this->dispatch('refreshData'); 
        }
    }

    public function hapusPermanen($logId) {
        DB::table('activity_logs')->where('id', $logId)->delete(); 
        $this->muatHistori();
    }
    
    public function boot() {
        $this->muatHistori();
    }

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

<!-- SIHIR WIRE:POLL -->
<div wire:poll.2s="muatHistori">
    <div x-cloak x-show="activeForm === 'histori'" class="fixed inset-0 z-[95] flex justify-end pointer-events-none">
        
        <!-- Background Blur Soft -->
        <div x-show="activeForm === 'histori'" x-transition.opacity.duration.400ms @click="activeForm = ''; setTimeout(() => $dispatch('buka-lobi'), 300)" class="absolute inset-0 bg-slate-900/20 backdrop-blur-md pointer-events-auto"></div>
        
        <!-- Wadah Panel Elegan -->
        <div x-show="activeForm === 'histori'" 
             x-transition:enter="transition ease-out duration-400 transform" x-transition:enter-start="translate-x-full md:translate-y-full md:translate-x-0" x-transition:enter-end="translate-x-0 md:translate-y-0" 
             x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0 md:translate-y-0" x-transition:leave-end="translate-x-full md:translate-y-full md:translate-x-0" 
             class="relative w-full max-w-md bg-slate-50/95 shadow-[-10px_0_40px_rgba(0,0,0,0.05)] pointer-events-auto flex flex-col h-full overflow-hidden border-l border-white md:rounded-l-3xl rounded-t-[2.5rem] md:rounded-t-none md:mt-0 mt-12">
            
            <!-- HEADER AESTHETIC -->
            <div class="px-8 py-6 bg-white/80 backdrop-blur-xl border-b border-slate-100 flex items-center justify-between z-20 sticky top-0 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="activeForm = ''; setTimeout(() => $dispatch('buka-lobi'), 300)" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:bg-slate-50 hover:scale-105 transition-all shadow-sm">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">Riwayat Sistem</h2>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-[9px] text-slate-400 font-extrabold uppercase tracking-widest">Sinkronisasi Realtime</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AREA TIMELINE -->
            <div class="flex-1 overflow-y-auto p-6 relative z-10 premium-scroll">
                
                @forelse($activityLogs as $tanggal => $logs)
                    <div class="mb-10 relative">
                        <!-- Tanggal -->
                        <div class="flex items-center gap-3 mb-6 sticky top-0 z-20 py-2">
                            <span class="text-[9px] font-black text-slate-500 bg-white/90 backdrop-blur-sm px-4 py-1.5 rounded-full uppercase tracking-widest border border-slate-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                                {{ $tanggal }}
                            </span>
                            <div class="flex-1 h-px bg-gradient-to-r from-slate-200 to-transparent"></div>
                        </div>

                        <!-- Garis Timeline Lurus -->
                        <div class="space-y-5 relative before:absolute before:inset-0 before:ml-[23px] before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-slate-200 before:via-slate-200 before:to-transparent">

                            @foreach($logs as $log)
                                @php
                                    // Pewarnaan Estetik
                                    $iconColor = match($log->color) {
                                        'red' => 'bg-rose-50 text-rose-500',
                                        'emerald' => 'bg-emerald-50 text-emerald-500',
                                        'blue' => 'bg-blue-50 text-blue-500',
                                        default => 'bg-slate-50 text-slate-500'
                                    };
                                @endphp

                                <!-- INI KUNCI ANTI SILUMAN: wire:key -->
                                <div wire:key="log-{{ $log->id }}" class="relative flex items-start gap-4 group">
                                    
                                    <!-- Ikon Bulat -->
                                    <div class="relative z-10 w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 bg-white border border-slate-100 shadow-sm transition-transform duration-300 group-hover:scale-105">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center {{ $iconColor }}">
                                            <i class="fas {{ $log->icon }} text-sm"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Kartu Putih Elegan -->
                                    <div class="flex-1 bg-white border border-slate-100 rounded-2xl p-4 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.08)] transition-all duration-300 relative overflow-hidden">
                                        
                                        <!-- Header Kartu -->
                                        <div class="flex justify-between items-start gap-3 mb-1.5">
                                            <h4 class="font-extrabold text-slate-800 text-sm leading-tight">{{ $log->title }}</h4>
                                            
                                            <!-- INI SOLUSI JAM KEGENCET: shrink-0 -->
                                            <span class="shrink-0 text-[9px] font-bold text-slate-400 flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }}
                                            </span>
                                        </div>
                                        
                                        <!-- Deskripsi -->
                                        <p class="text-[10px] text-slate-500 font-medium leading-relaxed">{{ $log->description }}</p>

                                        <!-- TOMBOL MESIN WAKTU (CUMA 1X & RAPI) -->
                                        @if($log->type === 'delete')
                                            <div class="mt-4 pt-3 border-t border-slate-50 flex items-center gap-2">
                                                <button wire:click="restoreData({{ $log->id }})" class="flex-1 py-2 bg-slate-50 hover:bg-emerald-500 text-slate-600 hover:text-white rounded-xl text-[9px] font-extrabold uppercase tracking-widest transition-all flex items-center justify-center gap-1.5 border border-slate-100 hover:border-emerald-500 shadow-sm group/btn">
                                                    <i class="fas fa-undo-alt group-hover/btn:-rotate-45 transition-transform"></i> Restore
                                                </button>
                                                <button wire:click="hapusPermanen({{ $log->id }})" class="flex-1 py-2 bg-slate-50 hover:bg-rose-500 text-slate-600 hover:text-white rounded-xl text-[9px] font-extrabold uppercase tracking-widest transition-all flex items-center justify-center gap-1.5 border border-slate-100 hover:border-rose-500 shadow-sm group/btn">
                                                    <i class="fas fa-trash-alt group-hover/btn:scale-110 transition-transform"></i> Musnahkan
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @empty
                    <!-- Tampilan Kosong Super Clean -->
                    <div class="h-full flex flex-col items-center justify-center text-center pb-20 pt-10">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 border border-slate-100 shadow-[0_10px_30px_rgba(0,0,0,0.03)] relative">
                            <i class="fas fa-wind text-4xl text-slate-300 absolute z-10"></i>
                            <div class="absolute inset-[-10px] border border-slate-200 rounded-full animate-pulse opacity-50"></div>
                        </div>
                        <h4 class="text-slate-800 font-black text-lg tracking-tight mb-1">Riwayat Bersih</h4>
                        <p class="text-xs text-slate-400 font-medium max-w-[200px] mx-auto">Belum ada aktivitas, penghapusan, atau tugas yang terekam.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</div>
<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

new class extends Component
{
    public $notes = [];
    public $topTask = null;
    public $saldo = 0;
    
    // Variabel buat Ide Gila Lu
    public $logs = [];
    public $level = 1;
    public $exp = 0;
    public $targetTabungan = 2500000; // Misal target Upgrade RAM/SSD 2.5 Jt
    public $quote = "";
    public $greeting = "";

    public function boot()
    {
        $this->setWaktuDanQuote();
        $this->muatData();
    }

    public function setWaktuDanQuote()
    {
        // Ide #7: Sapaan Pintar
        $jam = (int) now()->format('H');
        if ($jam >= 5 && $jam < 11) $this->greeting = "Pagi boss! Udah ngopi belum? ☕";
        elseif ($jam >= 11 && $jam < 15) $this->greeting = "Siang Rixsan! Tetep fokus koding ya. 💻";
        elseif ($jam >= 15 && $jam < 18) $this->greeting = "Sore Rixsan! Sby lagi panas, minum es dulu 🧊";
        else $this->greeting = "Malam boss! Waktunya nge-push commit nih 🚀";

        // Ide #9: Daily Quote API (Simulasi)
        $quotes = [
            "Bukan bug, itu fitur yang belum terpecahkan. 👾",
            "Satu baris kode hari ini, satu langkah menuju Pro! 🏆",
            "Jangan takut error, takutlah kalau langsung jalan. 💀",
            "Kopi habis, error meringis. Tetap semangat! ☕"
        ];
        $this->quote = $quotes[array_rand($quotes)];
    }

    public function muatData()
    {
        $user = User::where('email', 'boss@harian.com')->first();
        if ($user) {
            // Ide #1: Radar Prioritas (Cuma ambil 1 tugas paling mendesak)
            $this->topTask = DB::table('tasks')
                ->where('user_id', $user->id)
                ->where('is_completed', false)
                ->orderByRaw("CASE WHEN priority = 'mendesak' THEN 1 WHEN priority = 'normal' THEN 2 ELSE 3 END")
                ->orderBy('created_at', 'asc')
                ->first();

            // Ambil 4 Catatan Terbaru
            $this->notes = DB::table('notes')->where('user_id', $user->id)->orderBy('id', 'desc')->limit(4)->get();

            // Hitung Total Uang
            $this->saldo = DB::table('savings')->where('user_id', $user->id)->sum('amount');

            // Ide #5: Gamifikasi Level & EXP (Dihitung dari rutinitas)
            $totalMisiKelar = DB::table('tasks')->where('user_id', $user->id)->where('is_completed', true)->count();
            $totalNabung = DB::table('savings')->where('user_id', $user->id)->count();
            
            $totalExp = ($totalMisiKelar * 50) + ($totalNabung * 20);
            $this->level = floor($totalExp / 100) + 1;
            $this->exp = $totalExp % 100; // Sisa Persentase ke level berikutnya

            // Ide #3: Mini Terminal Log (Ambil 3 histori nabung/misi terakhir)
            $histori = DB::table('savings')->where('user_id', $user->id)->orderBy('id', 'desc')->limit(3)->get();
            $this->logs = [];
            foreach($histori as $h) {
                $time = \Carbon\Carbon::parse($h->created_at)->format('H:i');
                $this->logs[] = "[$time] > sys.nabung($h->amount) -> OK";
            }
        }
    }

    public function selesaiTugas($id)
    {
        DB::table('tasks')->where('id', $id)->update(['is_completed' => true]);
        $this->muatData();
    }
    
    public function hapusCatatan($id)
    {
        DB::table('notes')->where('id', $id)->delete();
        $this->muatData();
    }
};
?>

<!-- Auto refresh per 3 detik -->
<main class="flex-1 overflow-y-auto pb-28 p-6 relative bg-white" wire:poll.3s="muatData">
    
    <!-- HEADER: Sapaan, Quote & Level Gamifikasi -->
    <div class="flex justify-between items-start mb-6 mt-2">
        <div class="flex-1 pr-4">
            <h1 class="text-xl font-extrabold text-gray-900 leading-tight">{{ $greeting }}</h1>
            <p class="text-[10px] text-gray-500 font-bold mt-1.5 line-clamp-2">"{{ $quote }}"</p>
        </div>
        
        <!-- Gamifikasi Level Badge -->
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center font-black border-4 border-red-100 shadow-md z-10 relative">
                <span class="text-[9px] absolute top-1">LV</span>
                <span class="text-lg mt-2">{{ $level }}</span>
            </div>
            <!-- Bar EXP -->
            <div class="w-16 h-1.5 bg-gray-100 rounded-full mt-2 overflow-hidden shadow-inner">
                <div class="h-full bg-red-500 rounded-full transition-all duration-500" style="width: {{ $exp }}%"></div>
            </div>
        </div>
    </div>

    <!-- KARTU SALDO + GRAFIK DENYUT + PROGRESS BAR TARGET -->
    <div class="bg-gray-900 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden border border-gray-800">
        <!-- Ide #6: Grafik Denyut (Sparkline Background) -->
        <svg class="absolute bottom-4 right-0 w-full h-24 text-red-500/10" preserveAspectRatio="none" viewBox="0 0 100 30" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M0 30 L10 25 L20 28 L30 15 L40 18 L50 5 L60 12 L70 2 L80 10 L90 0 L100 15" />
        </svg>
        
        <!-- Efek Glow Merah -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-red-600 opacity-20 rounded-full blur-3xl -mr-10 -mt-10"></div>
        
        <div class="relative z-10">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1.5 flex items-center gap-2">
                Total Kas <i class="fas fa-shield-check text-red-500"></i>
            </p>
            <h2 class="text-3xl font-black text-white mb-4 tracking-tight">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
            
            <!-- Ide #2: Target Tabungan -->
            @php $persenTabungan = min(100, round(($saldo / $targetTabungan) * 100)); @endphp
            <div class="mt-4">
                <div class="flex justify-between text-[10px] font-bold mb-1.5">
                    <span class="text-gray-400">Target: Rakit PC (<span class="text-white">Rp {{ number_format($targetTabungan/1000000, 1, ',', '.') }} Jt</span>)</span>
                    <span class="text-red-400">{{ $persenTabungan }}%</span>
                </div>
                <div class="w-full h-2.5 bg-gray-800 rounded-full overflow-hidden shadow-inner border border-gray-700">
                    <div class="h-full bg-red-600 rounded-full relative overflow-hidden" style="width: {{ $persenTabungan }}%">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ide #3: MINI TERMINAL LOG -->
    <div class="bg-gray-900 border border-gray-800 p-3 rounded-xl mt-4 flex flex-col gap-1 font-mono text-[9px] text-green-400 shadow-inner relative overflow-hidden">
        <div class="absolute inset-0 bg-green-500/5 mix-blend-overlay pointer-events-none"></div>
        <div class="text-gray-500 mb-1 flex justify-between"><span>root@rixsan:~# tail -n 3 sys.log</span> <span><i class="fas fa-terminal"></i></span></div>
        @forelse($logs as $log)
            <div>{{ $log }}</div>
        @empty
            <div class="text-gray-600">Waiting for system input...</div>
        @endforelse
        <div class="animate-pulse font-bold">_</div>
    </div>

    <!-- Ide #1: RADAR PRIORITAS (Cuma 1 Tugas!) -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-4 bg-red-600 rounded-full animate-pulse"></div>
                <h3 class="text-sm font-extrabold text-gray-900">Radar Prioritas</h3>
            </div>
            <!-- Link ke menu tugas lengkap -->
            <span class="text-[10px] font-bold text-gray-400">Lihat Semua <i class="fas fa-arrow-right"></i></span>
        </div>
        
        @if(!$topTask)
            <div class="p-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-center text-xs font-bold text-gray-400 flex flex-col items-center gap-2">
                <i class="fas fa-satellite-dish text-2xl text-gray-300"></i>
                Radar bersih. Ngga ada misi mendesak boss!
            </div>
        @else
            <div class="bg-white rounded-2xl p-4 shadow-[0_5px_15px_rgba(220,38,38,0.08)] border border-red-100 flex items-center justify-between gap-3 relative overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                
                <div class="flex items-center gap-3 flex-1 overflow-hidden">
                    <button wire:click="selesaiTugas({{ $topTask->id }})" class="shrink-0 w-7 h-7 rounded border-2 border-gray-200 flex items-center justify-center text-transparent hover:border-red-600 hover:text-red-600 hover:bg-red-50 transition-all cursor-pointer">
                        <i class="fas fa-check text-[10px]"></i>
                    </button>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 text-sm truncate">{{ $topTask->title }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[8px] font-black uppercase tracking-widest text-red-600 bg-red-50 px-1.5 py-0.5 rounded border border-red-100"><i class="fas fa-fire mr-0.5"></i> {{ $topTask->priority ?: 'Normal' }}</span>
                            <span class="text-[9px] font-bold text-gray-500 truncate"><i class="far fa-clock mr-0.5"></i> {{ str_replace('_', ' ', $topTask->due_date) ?: 'Kapan aja' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Ide #4: GELEMBUNG DINAMIS PASTEL -->
    <div class="mt-8">
        <h3 class="text-sm font-extrabold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-red-600 text-lg"></i> Quick Notes
        </h3>
        
        @if(count($notes) == 0)
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center text-xs font-semibold text-gray-400">Belum ada ide tersimpan.</div>
        @else
            <div class="flex gap-3 overflow-x-auto pb-4 pt-1 snap-x -mx-2 px-2">
                @php 
                    // Mapping warna dinamis elegan tapi tetap ada nuansa putih/pastel
                    $themes = [
                        ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-100'],
                        ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200'],
                        ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-100'],
                        ['bg' => 'bg-gray-50', 'text' => 'text-gray-900', 'border' => 'border-gray-200'],
                    ];
                @endphp
                
                @foreach($notes as $index => $note)
                    @php $theme = $themes[$index % 4]; @endphp
                    <div class="snap-start shrink-0 w-36 {{ $theme['bg'] }} rounded-2xl p-4 border {{ $theme['border'] }} relative group transition-all">
                        <button wire:click="hapusCatatan({{ $note->id }})" class="absolute top-2 right-2 w-5 h-5 flex items-center justify-center text-gray-400 hover:text-red-600 rounded-full transition-colors">
                            <i class="fas fa-times text-[10px]"></i>
                        </button>
                        <i class="fas {{ $note->icon ?: 'fa-lightbulb' }} {{ $theme['text'] }} text-lg mb-2"></i>
                        <h4 class="font-bold text-gray-900 text-[11px] truncate mb-1">{{ $note->title }}</h4>
                        <p class="text-[9px] font-medium text-gray-500 truncate w-full">{{ $note->content }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</main>
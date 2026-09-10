<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

new class extends Component
{
    public $notes = [];
    public $topTask = null;
    public $saldo = 0;
    
    // Variabel Terminal & Gamifikasi
    public $logs = [];
    public $level = 1;
    public $exp = 0;
    public $quote = "";
    public $greeting = "";

    // Variabel Target Dinamis
    public $targetName = 'Rakit PC';
    public $targetAmount = 2500000;

    public function boot()
    {
        $this->setWaktuDanQuote();
        $this->muatData();
    }

    public function setWaktuDanQuote()
    {
        $jam = (int) now()->format('H');
        if ($jam >= 5 && $jam < 11) $this->greeting = "Pagi boss! Udah ngopi belum? ☕";
        elseif ($jam >= 11 && $jam < 15) $this->greeting = "Siang Rixsan! Tetep fokus koding ya. 💻";
        elseif ($jam >= 15 && $jam < 18) $this->greeting = "Sore Rixsan! Sby lagi panas, minum es dulu 🧊";
        else $this->greeting = "Malam boss! Waktunya nge-push commit nih 🚀";

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
            // Ambil Data Target dari Database
            $target = DB::table('targets')->where('user_id', $user->id)->first();
            if($target) {
                $this->targetName = $target->name;
                $this->targetAmount = $target->amount;
            }

            // Radar Prioritas
            $this->topTask = DB::table('tasks')
                ->where('user_id', $user->id)
                ->where('is_completed', false)
                ->orderByRaw("CASE WHEN priority = 'mendesak' THEN 1 WHEN priority = 'normal' THEN 2 ELSE 3 END")
                ->orderBy('created_at', 'asc')
                ->first();

            // Notes
            $this->notes = DB::table('notes')->where('user_id', $user->id)->orderBy('id', 'desc')->limit(4)->get();

            // Hitung Saldo
            $uangMasuk = DB::table('savings')->where('user_id', $user->id)->sum('amount');
            $uangKeluar = DB::table('expenses')->where('user_id', $user->id)->sum('amount');
            $this->saldo = $uangMasuk - $uangKeluar;

            // Gamifikasi
            $totalMisiKelar = DB::table('tasks')->where('user_id', $user->id)->where('is_completed', true)->count();
            $totalNabung = DB::table('savings')->where('user_id', $user->id)->count();
            $totalExp = ($totalMisiKelar * 50) + ($totalNabung * 20);
            $this->level = floor($totalExp / 100) + 1;
            $this->exp = $totalExp % 100;

            // Log
            $logMasuk = DB::table('savings')->where('user_id', $user->id)->select('amount', 'created_at', DB::raw("'nabung' as tipe"))->orderBy('id', 'desc')->limit(3)->get();
            $logKeluar = DB::table('expenses')->where('user_id', $user->id)->select('amount', 'created_at', DB::raw("'keluar' as tipe"))->orderBy('id', 'desc')->limit(3)->get();
            $semuaLog = $logMasuk->merge($logKeluar)->sortByDesc('created_at')->take(3);
            
            $this->logs = [];
            foreach($semuaLog as $h) {
                $time = \Carbon\Carbon::parse($h->created_at)->format('H:i');
                if($h->tipe == 'nabung') {
                    $this->logs[] = "[$time] > sys.nabung($h->amount) -> OK";
                } else {
                    $this->logs[] = "[$time] > sys.pay($h->amount) -> OK";
                }
            }
        }
    }

    // Fungsi Update Target Real-time
    public function simpanTarget($nama, $nominalStr)
    {
        $nominal = (int) preg_replace('/[^0-9]/', '', (string) $nominalStr);
        if ($nominal <= 0 || empty(trim($nama))) return;

        $user = User::where('email', 'boss@harian.com')->first();
        $target = DB::table('targets')->where('user_id', $user->id)->first();

        if ($target) {
            DB::table('targets')->where('id', $target->id)->update(['name' => $nama, 'amount' => $nominal, 'updated_at' => now()]);
        } else {
            DB::table('targets')->insert(['user_id' => $user->id, 'name' => $nama, 'amount' => $nominal, 'created_at' => now(), 'updated_at' => now()]);
        }

        $this->muatData(); // Refresh UI langsung!
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

<main class="flex-1 overflow-y-auto pb-28 p-6 relative bg-white" wire:poll.3s="muatData">
    
    <!-- HEADER -->
    <div class="flex justify-between items-start mb-6 mt-2">
        <div class="flex-1 pr-4">
            <h1 class="text-xl font-extrabold text-gray-900 leading-tight">{{ $greeting }}</h1>
            <p class="text-[10px] text-gray-500 font-bold mt-1.5 line-clamp-2">"{{ $quote }}"</p>
        </div>
        
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center font-black border-4 border-red-50 shadow-sm z-10 relative">
                <span class="text-[9px] absolute top-1">LV</span>
                <span class="text-lg mt-2">{{ $level }}</span>
            </div>
            <div class="w-16 h-1.5 bg-gray-100 rounded-full mt-2 overflow-hidden shadow-inner">
                <div class="h-full bg-red-500 rounded-full transition-all duration-500" style="width: {{ $exp }}%"></div>
            </div>
        </div>
    </div>

    <!-- KARTU SALDO MODERN PREMIUM & MODAL EDIT -->
    <div x-data="{ 
            editMode: false, 
            inputNama: '{{ addslashes($targetName) }}', 
            inputNominal: '{{ number_format($targetAmount, 0, ',', '.') }}',
            formatUang(e) {
                let angka = e.target.value.replace(/[^0-9]/g, '');
                this.inputNominal = angka ? new Intl.NumberFormat('id-ID').format(angka) : '';
            }
         }">
         
        <!-- Kartu Utama (Desain Clean Elegant) -->
        <div class="bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 rounded-[2rem] p-7 shadow-[0_15px_40px_rgba(0,0,0,0.2)] relative overflow-hidden border border-gray-700/50">
            <!-- Smooth Ambient Glow (Bukan garis tajam) -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-red-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                        Total Kas <i class="fas fa-shield-check text-emerald-400"></i>
                    </p>
                    <i class="fab fa-cc-visa text-gray-600 text-2xl opacity-30"></i>
                </div>
                
                <h2 class="text-4xl font-black text-white mb-6 tracking-tight drop-shadow-md">
                    Rp {{ number_format($saldo, 0, ',', '.') }}
                </h2>
                
                <!-- Target Dinamis -->
                @php 
                    $persenTabungan = $targetAmount > 0 ? min(100, round(($saldo / $targetAmount) * 100)) : 0; 
                @endphp
                
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 border border-white/10 relative group">
                    <div class="flex justify-between items-center mb-2 text-[10px] font-bold">
                        <span class="text-gray-400">Target: <span class="text-white">{{ $targetName }}</span></span>
                        
                        <div class="flex items-center gap-3">
                            <span class="text-white bg-white/10 px-2 py-0.5 rounded-md">{{ $persenTabungan }}%</span>
                            <!-- Tombol Edit Muncul pas disentuh/hover -->
                            <button @click="editMode = true" class="text-gray-400 hover:text-white transition-colors">
                                <i class="fas fa-pen bg-white/10 p-1.5 rounded-full"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="w-full h-2 bg-gray-900/50 rounded-full overflow-hidden shadow-inner">
                        <div class="h-full bg-gradient-to-r from-red-600 to-red-400 rounded-full relative transition-all duration-1000 ease-out" style="width: {{ $persenTabungan }}%">
                            <div class="absolute inset-0 bg-white/20 animate-[pulse_2s_ease-in-out_infinite]"></div>
                        </div>
                    </div>
                    <div class="text-[9px] text-gray-500 mt-1.5 text-right font-medium tracking-wide">
                        Sisa Rp {{ number_format(max(0, $targetAmount - $saldo), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT TARGET (Elegan minimalis) -->
        <div x-cloak x-show="editMode" class="fixed inset-0 z-[100] flex items-center justify-center p-6 pointer-events-none">
            <div x-show="editMode" x-transition.opacity class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm pointer-events-auto" @click="editMode = false"></div>
            
            <div x-show="editMode" 
                 x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                 class="relative w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl pointer-events-auto border border-gray-100">
                
                <h3 class="text-lg font-extrabold text-gray-900 mb-4">Ubah Target Tabungan</h3>
                
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Mimpi Baru Kamu</label>
                <input type="text" x-model="inputNama" placeholder="Misal: Liburan ke Bali" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-900 focus:outline-none focus:border-red-500 mb-4">
                
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Butuh Dana Berapa?</label>
                <div class="flex items-center gap-2 border border-gray-200 rounded-xl px-4 focus-within:border-red-500 bg-gray-50 mb-6">
                    <span class="text-gray-400 font-bold">Rp</span>
                    <input type="text" inputmode="numeric" x-model="inputNominal" @input="formatUang($event)" class="w-full bg-transparent py-3 font-bold text-gray-900 focus:outline-none">
                </div>
                
                <div class="flex gap-3">
                    <button @click="editMode = false" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-200 transition-colors">Batal</button>
                    <button @click="$wire.simpanTarget(inputNama, inputNominal).then(() => editMode = false)" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 transition-colors shadow-lg">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MINI TERMINAL LOG (Agak dilembutkan warnanya) -->
    <div class="bg-gray-50 border border-gray-100 p-3 rounded-xl mt-4 flex flex-col gap-1 font-mono text-[9px] text-gray-600 relative overflow-hidden">
        <div class="flex justify-between items-center mb-1">
            <span class="text-gray-400 font-bold">Aktivitas Terakhir</span>
            <i class="fas fa-history text-gray-300"></i>
        </div>
        @forelse($logs as $log)
            <div class="text-gray-700"><span class="text-emerald-500 font-bold">✓</span> {{ $log }}</div>
        @empty
            <div class="text-gray-400">Belum ada transaksi...</div>
        @endforelse
    </div>

    <!-- RADAR PRIORITAS -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-4 bg-red-600 rounded-full animate-pulse"></div>
                <h3 class="text-sm font-extrabold text-gray-900">Radar Prioritas</h3>
            </div>
            <span class="text-[10px] font-bold text-gray-400 cursor-pointer hover:text-red-500">Lihat Semua <i class="fas fa-arrow-right"></i></span>
        </div>
        
        @if(!$topTask)
            <div class="p-5 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-center flex flex-col items-center gap-2">
                <i class="fas fa-mug-hot text-2xl text-gray-300"></i>
                <p class="text-xs font-bold text-gray-400">Ngga ada misi mendesak boss!</p>
            </div>
        @else
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between gap-3 relative overflow-hidden group hover:border-red-200 transition-colors">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                <div class="flex items-center gap-3 flex-1 overflow-hidden">
                    <button wire:click="selesaiTugas({{ $topTask->id }})" class="shrink-0 w-7 h-7 rounded border-2 border-gray-200 flex items-center justify-center text-transparent hover:border-red-500 hover:text-red-500 hover:bg-red-50 transition-all cursor-pointer">
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

    <!-- QUICK NOTES -->
    <div class="mt-8">
        <h3 class="text-sm font-extrabold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-red-600 text-lg"></i> Quick Notes
        </h3>
        
        @if(count($notes) == 0)
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center text-xs font-semibold text-gray-400">Belum ada ide tersimpan.</div>
        @else
            <div class="flex gap-3 overflow-x-auto pb-4 pt-1 snap-x -mx-2 px-2">
                @php 
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
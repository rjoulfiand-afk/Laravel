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

    // Variabel Rank / Piala
    public $rankTitle = 'Novice';
    public $rankColor = 'text-orange-400';
    public $rankIcon = 'fa-medal';

    // Variabel Target Dinamis
    public $targetName = 'Rakit PC';
    public $targetAmount = 2500000;

    public function boot()
    {
        $this->setQuote();
        $this->muatData();
    }

    public function setQuote()
    {
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
            $target = DB::table('targets')->where('user_id', $user->id)->first();
            if($target) {
                $this->targetName = $target->name;
                $this->targetAmount = $target->amount;
            }

            $this->topTask = DB::table('tasks')
                ->where('user_id', $user->id)
                ->where('is_completed', false)
                ->where(function($query) {
                    $query->where('priority', 'mendesak')
                          ->orWhere('due_date', 'hari_ini')
                          ->orWhere('due_date', 'hari ini');
                })
                ->orderBy('created_at', 'asc')
                ->first();

            $this->notes = DB::table('notes')->where('user_id', $user->id)->orderBy('id', 'desc')->limit(12)->get();

            $uangMasuk = DB::table('savings')->where('user_id', $user->id)->sum('amount');
            $uangKeluar = DB::table('expenses')->where('user_id', $user->id)->sum('amount');
            $this->saldo = $uangMasuk - $uangKeluar;

            // 🌟 LOGIKA GAMIFIKASI & PIALA
            $totalMisiKelar = DB::table('tasks')->where('user_id', $user->id)->where('is_completed', true)->count();
            $totalNabung = DB::table('savings')->where('user_id', $user->id)->count();
            
            $totalExp = ($totalMisiKelar * 50) + ($totalNabung * 20);
            $this->level = floor($totalExp / 100) + 1;
            $this->exp = $totalExp % 100;

            // Penentuan Title & Piala berdasarkan Level
            if ($this->level >= 15) {
                $this->rankTitle = 'Legend';
                $this->rankColor = 'text-yellow-500';
                $this->rankIcon = 'fa-trophy';
            } elseif ($this->level >= 10) {
                $this->rankTitle = 'Pro Dev';
                $this->rankColor = 'text-purple-500';
                $this->rankIcon = 'fa-crown';
            } elseif ($this->level >= 5) {
                $this->rankTitle = 'Hustler';
                $this->rankColor = 'text-blue-500';
                $this->rankIcon = 'fa-star';
            } else {
                $this->rankTitle = 'Novice';
                $this->rankColor = 'text-orange-500';
                $this->rankIcon = 'fa-medal';
            }

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
        $this->muatData();
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

<style>
    .premium-scroll::-webkit-scrollbar { height: 6px; }
    .premium-scroll::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
    .premium-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .premium-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .premium-scroll { -webkit-overflow-scrolling: touch; scroll-behavior: smooth; }
</style>

<main class="flex-1 overflow-y-auto pb-28 p-6 relative bg-white" wire:poll.3s="muatData">
    
    <!-- HEADER: OTAK JAM REALTIME & PROFIL GAMIFIKASI -->
    <div x-data="{
        waktu: '',
        sapaan: '',
        updateJam() {
            const now = new Date();
            const h = now.getHours();
            const m = now.getMinutes().toString().padStart(2, '0');
            this.waktu = h.toString().padStart(2, '0') + ':' + m;
            
            if (h >= 5 && h < 11) this.sapaan = 'Pagi boss! Udah ngopi belum? ☕';
            else if (h >= 11 && h < 15) this.sapaan = 'Siang Rixsan! Tetep fokus koding ya. 💻';
            else if (h >= 15 && h < 18) this.sapaan = 'Sore Rixsan! Sby lagi panas, minum es dulu 🧊';
            else this.sapaan = 'Malam boss! Waktunya nge-push commit nih 🚀';
        }
    }" x-init="updateJam(); setInterval(() => updateJam(), 1000)" class="flex justify-between items-start mb-6 mt-2">
        
        <div class="flex-1 pr-4">
            <h1 class="text-xl font-extrabold text-gray-900 leading-tight" x-text="sapaan"></h1>
            <p class="text-[10px] text-gray-500 font-bold mt-1.5 line-clamp-2">"{{ $quote }}"</p>
            
            <div class="mt-3 inline-flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all">
                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></div>
                <span class="font-mono text-xs font-black text-gray-800 tracking-widest" x-text="waktu"></span>
                <span class="text-[8px] font-black uppercase tracking-widest text-gray-400">Lokal</span>
            </div>
        </div>
        
        <!-- 🌟 PROFIL & GAMIFIKASI WIDGET 🌟 -->
        <!-- Tombol ini udah disiapin buat ngebuka halaman profil nantinya -->
        <button @click="activeForm = 'profil'" class="flex flex-col items-center group cursor-pointer relative z-10 focus:outline-none">
            
            <!-- Trophy / Rank Badge (Muncul melayang) -->
            <div class="absolute -top-3 bg-white px-2 py-0.5 rounded-full shadow-sm border border-gray-100 flex items-center gap-1 z-20 group-hover:-translate-y-1 transition-transform">
                <i class="fas {{ $rankIcon }} {{ $rankColor }} text-[8px]"></i>
                <span class="text-[8px] font-black text-gray-700 tracking-wider">{{ $rankTitle }}</span>
            </div>

            <!-- Foto Profil & Level Badge -->
            <div class="relative mt-2">
                <!-- Ring Progress / Glow luar -->
                <div class="w-14 h-14 rounded-full p-0.5 bg-gradient-to-tr from-red-500 to-orange-400 shadow-md group-hover:scale-105 transition-all">
                    <!-- Avatar UI Otomatis -->
                    <img src="https://ui-avatars.com/api/?name=Rixsan&background=1f2937&color=fff&bold=true" alt="Profil" class="w-full h-full rounded-full border-2 border-white object-cover bg-gray-100">
                </div>

                <!-- Level Badge kecil di pojok foto -->
                <div class="absolute -bottom-1 -right-1 bg-red-600 text-white w-6 h-6 rounded-full border-2 border-white flex items-center justify-center shadow-sm">
                    <span class="text-[9px] font-black">{{ $level }}</span>
                </div>
            </div>

            <!-- Bar Stamina / EXP di bawah foto -->
            <div class="w-16 h-1.5 bg-gray-100 rounded-full mt-3 overflow-hidden shadow-inner relative">
                <div class="h-full bg-gradient-to-r from-red-500 to-orange-400 rounded-full transition-all duration-700 ease-out" style="width: {{ $exp }}%"></div>
            </div>
        </button>
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
         
        <div class="bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 rounded-[2rem] p-7 shadow-[0_15px_40px_rgba(0,0,0,0.2)] relative overflow-hidden border border-gray-700/50">
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
                
                @php 
                    $persenTabungan = $targetAmount > 0 ? min(100, round(($saldo / $targetAmount) * 100)) : 0; 
                @endphp
                
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-4 border border-white/10 relative group">
                    <div class="flex justify-between items-center mb-2 text-[10px] font-bold">
                        <span class="text-gray-400">Target: <span class="text-white">{{ $targetName }}</span></span>
                        
                        <div class="flex items-center gap-3">
                            <span class="text-white bg-white/10 px-2 py-0.5 rounded-md">{{ $persenTabungan }}%</span>
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

    <!-- MINI TERMINAL LOG -->
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
                <div class="w-1.5 h-4 bg-red-600 rounded-full animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.8)]"></div>
                <h3 class="text-sm font-extrabold text-gray-900">Radar Prioritas</h3>
            </div>
            <button @click="activeForm = 'daftar-tugas'" class="text-[10px] font-bold text-gray-400 cursor-pointer hover:text-red-500 flex items-center gap-1">
                Lihat Semua <i class="fas fa-chevron-right text-[8px] mt-0.5"></i>
            </button>
        </div>
        
        @if(!$topTask)
            <div class="p-5 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-center flex flex-col items-center gap-2">
                <i class="fas fa-shield-alt text-2xl text-gray-300"></i>
                <p class="text-xs font-bold text-gray-400">Aman boss! Ngga ada misi darurat.</p>
            </div>
        @else
            <div x-data="{ konfirmasi: false }" class="bg-red-50/30 rounded-2xl p-4 shadow-sm border border-red-100 relative overflow-hidden group transition-all">
                <div x-cloak x-show="konfirmasi" x-transition.opacity.duration.200ms class="absolute inset-0 bg-white/90 backdrop-blur-sm z-20 flex flex-col items-center justify-center gap-2 rounded-2xl border border-red-100">
                    <span class="font-extrabold text-gray-800 text-xs mb-1">Yakin tugas ini udah kelar bro? 🤔</span>
                    <div class="flex gap-3">
                        <button @click="konfirmasi = false" class="px-4 py-1.5 bg-gray-100 text-gray-500 font-bold text-[10px] rounded-lg hover:bg-gray-200 transition-colors">Batal</button>
                        <button wire:click="selesaiTugas({{ $topTask->id }})" class="px-4 py-1.5 bg-red-500 text-white font-bold text-[10px] rounded-lg shadow-md hover:bg-red-600 transition-colors">Yakin, Sikat!</button>
                    </div>
                </div>

                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500 animate-pulse"></div>
                
                <div class="flex items-center gap-3 flex-1 pl-2">
                    <button @click="konfirmasi = true" class="shrink-0 w-8 h-8 rounded-full border-2 border-red-200 flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white transition-all cursor-pointer bg-white shadow-sm">
                        <i class="fas fa-check text-[10px]"></i>
                    </button>
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="font-extrabold text-red-900 text-sm truncate">{{ $topTask->title }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[8px] font-black uppercase tracking-widest text-white bg-red-500 px-1.5 py-0.5 rounded shadow-sm">
                                <i class="fas fa-fire mr-0.5"></i> {{ $topTask->priority ?: 'Normal' }}
                            </span>
                            <span class="text-[9px] font-bold text-red-500 truncate"><i class="far fa-clock mr-0.5"></i> {{ str_replace('_', ' ', $topTask->due_date) ?: 'Kapan aja' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- QUICK NOTES DENGAN PREMIUM SCROLL -->
    <div class="mt-8">
        <h3 class="text-sm font-extrabold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-red-600 text-lg"></i> Quick Notes
        </h3>
        
        @if(count($notes) == 0)
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-center text-xs font-semibold text-gray-400">Belum ada ide tersimpan.</div>
        @else
            <div class="flex gap-3 overflow-x-auto pb-4 pt-1 snap-x -mx-2 px-2 premium-scroll">
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
<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

new class extends Component {
    use WithFileUploads;

    public string $pesan = '';
    public array $chats = [];
    public array $apiHistory = [];
    public $gambar; // Buat fitur akses Galeri/Kamera

    public string $identitasJell = "Kamu adalah Jell, asisten AI pribadi yang tertanam di dalam web Dashboard Productivity milik Rixsan Joulfiand (dipanggil Boss Jull). Gunakan bahasa santai dan gaul (lu/gue atau boss). Kamu ahli dalam programming (Laravel, Livewire, Alpine.js, Tailwind) dan hal sehari-hari. Kamu sekarang bisa melihat gambar, jadi analisislah gambar dengan detail jika Boss Jull mengirimkannya. Jawab dengan ringkas, proaktif, dan jangan kaku.";

    public function mount(): void
    {
        // 1. FITUR MEMORI: Tarik ingatan chat dari Session biar ngga hilang pas di-refresh
        if (Session::has('jell_chats') && Session::has('jell_apiHistory')) {
            $this->chats = Session::get('jell_chats');
            $this->apiHistory = Session::get('jell_apiHistory');
        } else {
            $this->chats[] = [
                'role' => 'ai',
                'text' => "Heii Jull! ✨ Gue Jell, AI Assistant lo. Memori gue udah permanen, mata gue udah aktif buat lihat gambar! Ada yang bisa gue bantu hari ini boss?",
                'time' => now()->format('H:i'),
            ];
        }
    }

    public function hapusMemori(): void
    {
        Session::forget(['jell_chats', 'jell_apiHistory']);
        $this->chats = [];
        $this->apiHistory = [];
        $this->mount();
    }

    public function kirimPesan(): void
    {
        $pesanUser = trim($this->pesan);
        
        // Kalau pesan kosong dan ngga ada gambar, batalin
        if (empty($pesanUser) && !$this->gambar) return;

        // 2. FITUR SHORTCUT MAGIC (Buka Aplikasi Realtime)
        $perintah = strtolower($pesanUser);
        $shortcutTriggered = false;

        if (str_contains($perintah, 'open instagram') || str_contains($perintah, 'buka ig') || str_contains($perintah, 'buka instagram')) {
            $this->tambahChatLokal('user', $pesanUser);
            $this->tambahChatLokal('ai', 'Siap boss! Meluncur ke Instagram sekarang... 🚀📸');
            $this->js("window.open('https://instagram.com', '_blank')");
            $shortcutTriggered = true;
        } elseif (str_contains($perintah, 'open whatsapp') || str_contains($perintah, 'buka wa') || str_contains($perintah, 'buka whatsapp')) {
            $this->tambahChatLokal('user', $pesanUser);
            $this->tambahChatLokal('ai', 'Dilaksanakan boss! Ngebuka WhatsApp Web... 💬🚀');
            $this->js("window.open('https://web.whatsapp.com', '_blank')");
            $shortcutTriggered = true;
        } elseif (str_contains($perintah, 'open tiktok') || str_contains($perintah, 'buka tiktok')) {
            $this->tambahChatLokal('user', $pesanUser);
            $this->tambahChatLokal('ai', 'Gasss boss! Scroll TikTok dulu kita... 🎵🔥');
            $this->js("window.open('https://tiktok.com', '_blank')");
            $shortcutTriggered = true;
        }

        // Kalau shortcut kepanggil, stop sampai sini (ngga perlu nembak API)
        if ($shortcutTriggered) {
            $this->pesan = '';
            $this->dispatch('scrollBottom');
            return;
        }

        $parts = [];
        if (!empty($pesanUser)) {
            $parts[] = ['text' => $pesanUser];
        } else {
            $parts[] = ['text' => 'Tolong analisis gambar ini boss Jell.'];
        }

        $gambarUrlUI = null;
        if ($this->gambar) {
            $mimeType = $this->gambar->getMimeType();
            $base64Data = base64_encode(file_get_contents($this->gambar->getRealPath()));
            
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $mimeType,
                    'data' => $base64Data
                ]
            ];
            $gambarUrlUI = $this->gambar->temporaryUrl(); 
        }

        $this->chats[] = [
            'role'  => 'user',
            'text'  => $pesanUser,
            'image' => $gambarUrlUI, 
            'time'  => now()->format('H:i'),
        ];

        $this->apiHistory[] = [
            'role'  => 'user',
            'parts' => $parts,
        ];

        $this->pesan = '';
        $this->gambar = null; 
        $this->dispatch('scrollBottom');

        // Cek API Key
        $apiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            $this->tambahPesanError("API Key Gemini belum diset di .env boss! 🔴");
            return;
        }

        try {
            // 4. TEMBAK API GEMINI (Dengan dukungan Multimodal/Gambar)
            $response = Http::withoutVerifying()
                ->timeout(120)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey,
                    [
                        'system_instruction' => [
                            'parts' => [['text' => $this->identitasJell]],
                        ],
                        'contents' => $this->apiHistory,
                        'generationConfig' => [
                            'maxOutputTokens' => 1024,
                            'temperature'     => 0.7,
                        ],
                    ]
                );

            if ($response->successful()) {
                $balasan = $response->json('candidates.0.content.parts.0.text') ?? 'Jell bingung boss, coba tanya lagi ya 🤔';

                $this->apiHistory[] = [
                    'role'  => 'model',
                    'parts' => [['text' => $balasan]],
                ];

                $this->chats[] = [
                    'role' => 'ai',
                    'text' => $balasan,
                    'time' => now()->format('H:i'),
                ];
                
                // Simpan ingatan ke Session!
                Session::put('jell_chats', $this->chats);
                Session::put('jell_apiHistory', $this->apiHistory);
                
            } else {
                $errorMsg = $response->json('error.message') ?? 'Error tidak diketahui';
                $this->tambahPesanError("Kata Google: {$errorMsg} 😵");
            }
        } catch (\Exception $e) {
            $this->tambahPesanError("Koneksi ke Gemini gagal boss! Error: " . $e->getMessage() . " 🔌");
        }

        $this->dispatch('scrollBottom');
    }

    private function tambahChatLokal($role, $pesan) {
        $this->chats[] = ['role' => $role, 'text' => $pesan, 'time' => now()->format('H:i')];
        Session::put('jell_chats', $this->chats);
    }

    private function tambahPesanError(string $pesan): void
    {
        $this->chats[] = ['role' => 'ai', 'text' => $pesan, 'time' => now()->format('H:i')];
        $this->dispatch('scrollBottom');
    }
};
?>

<div>
    <style>
        /* Animasi Jell Bernapas (Lebih Realistis) *//* Animasi Jell Bernapas (Bersih Tanpa Kotak) */
        @keyframes jellBreathe {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .anim-jell-breathe { animation: jellBreathe 3.5s cubic-bezier(0.4, 0, 0.2, 1) infinite; }

        /* Animasi Pas Jell Mikir Keras */
        @keyframes jellThinking {
            0% { transform: rotate(0deg) scale(1.1); filter: brightness(1.2); }
            100% { transform: rotate(360deg) scale(1.1); filter: brightness(1.5); }
        }
        .anim-jell-thinking { animation: jellThinking 1.5s linear infinite; }

        @keyframes chatFadeUp {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .chat-appear { animation: chatFadeUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .hide-scroll::-webkit-scrollbar { display: none; width: 0; }
    </style>

    <div x-cloak x-show="activeForm === 'asisten'" class="fixed inset-0 z-[80] flex justify-end pointer-events-none">

        <!-- Backdrop -->
        <div x-show="activeForm === 'asisten'" x-transition.opacity.duration.400ms @click="activeForm = ''" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm pointer-events-auto"></div>

        <!-- Panel Chatbot Premium -->
        <div x-show="activeForm === 'asisten'"
     x-transition:enter="transition ease-out duration-400 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
     x-transition:leave="transition ease-in duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
     class="fixed inset-0 md:left-auto md:w-md w-full h-[100dvh] bg-slate-50 pointer-events-auto flex flex-col overflow-hidden shadow-2xl z-[99999]">
            <!-- Header dengan Tombol Reset Memori -->
            <div class="bg-white/95 backdrop-blur-xl border-b border-slate-100 px-5 pt-12 pb-4 flex items-center justify-between z-30 shadow-sm shrink-0">
                <div class="flex items-center gap-4">
                    <button type="button" @click="activeForm = ''" class="w-9 h-9 bg-slate-50 border border-slate-100 rounded-full text-slate-500 flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all shadow-sm active:scale-95">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="relative w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-rose-600 p-[2px] shadow-md">
                            <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                                <i class="fas fa-robot text-red-500 text-[15px]"></i>
                            </div>
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full animate-pulse"></div>
                        </div>
                        <div>
                            <h3 class="text-[17px] font-black text-slate-800 tracking-tight leading-none mb-1">Jell AI</h3>
                            <p class="text-[9px] text-red-500 font-extrabold uppercase tracking-widest flex items-center gap-1">
                                Memory Active
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Tombol Hapus Ingatan -->
                <button type="button" wire:click="hapusMemori" class="w-8 h-8 bg-slate-50 border border-slate-100 rounded-full text-slate-400 hover:text-red-500 transition-colors shadow-sm flex items-center justify-center" title="Reset Ingatan">
                    <i class="fas fa-broom text-[11px]"></i>
                </button>
            </div>

            <!-- Area Chat -->
            <div id="chat-container-ai" class="flex-1 overflow-y-auto px-5 pt-6 pb-40 space-y-6 hide-scroll relative z-10 flex flex-col"
                 x-init="$watch('activeForm', val => { if (val === 'asisten') setTimeout(() => { let c = document.getElementById('chat-container-ai'); if (c) c.scrollTop = c.scrollHeight; }, 100); })"
                 @scroll-bottom.window="setTimeout(() => { let c = document.getElementById('chat-container-ai'); if (c) c.scrollTop = c.scrollHeight; }, 50)">
                
                <div class="text-center my-1">
                    <span class="px-4 py-1.5 bg-slate-200/50 border border-slate-200 text-slate-400 rounded-full text-[9px] font-extrabold tracking-widest uppercase shadow-sm">Percakapan Tersimpan</span>
                </div>

                @foreach($chats as $chat)
                    @if($chat['role'] === 'ai')
                        <!-- Bubble Jell -->
                        <div class="flex flex-col gap-1 items-start chat-appear w-full">
                            <div class="flex items-end gap-2.5 max-w-[85%]">
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm shrink-0 mb-1">
                                    <i class="fas fa-robot text-red-500 text-[10px]"></i>
                                </div>
                                <div class="bg-white text-slate-700 text-[13.5px] font-medium px-4 py-3.5 rounded-2xl rounded-bl-none shadow-sm border border-slate-100 leading-relaxed break-words">
                                    {!! nl2br(e($chat['text'])) !!}
                                </div>
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 ml-[2.8rem]">{{ $chat['time'] }}</span>
                        </div>
                    @else
                        <!-- Bubble User (Bisa nampilin Gambar) -->
                        <div class="flex flex-col gap-1 items-end chat-appear w-full">
                            <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white text-[13.5px] font-medium p-3 rounded-2xl rounded-br-none shadow-md leading-relaxed max-w-[85%] flex flex-col gap-2">
                                <!-- Kalau User ngirim gambar, tampilin di sini -->
                                @if(isset($chat['image']) && $chat['image'])
                                    <img src="{{ $chat['image'] }}" class="w-full rounded-xl object-cover shadow-sm border border-white/20">
                                @endif
                                @if(!empty($chat['text']))
                                    <div class="px-1">{!! nl2br(e($chat['text'])) !!}</div>
                                @endif
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 mr-1 flex items-center gap-1">
                                {{ $chat['time'] }} <i class="fas fa-check-double text-red-500 ml-0.5"></i>
                            </span>
                        </div>
                    @endif
                @endforeach

                <!-- Loader Saat Jell Mikir -->
                <div wire:loading wire:target="kirimPesan" class="flex flex-col gap-1 items-start chat-appear w-full">
                    <div class="flex items-end gap-2.5 max-w-[85%]">
                        <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm shrink-0 mb-1">
                            <i class="fas fa-robot text-red-500 text-[10px] animate-bounce"></i>
                        </div>
                        <div class="bg-white px-4 py-4 rounded-2xl rounded-bl-none shadow-sm border border-slate-100 flex items-center gap-1.5">
                            <div class="w-1.5 h-1.5 bg-red-400 rounded-full animate-bounce"></div>
                            <div class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-1.5 h-1.5 bg-red-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 🤖 ORB JELL EKSPRESIF REALISTIS DI BAWAH -->
            <!-- ========================================== -->
            <div class="absolute bottom-24 right-5 z-[60] flex items-end justify-end gap-3 pointer-events-none anim-jell-breathe bg-transparent">
                <!-- Bubble Teks (Efek Kaca) -->
                <div class="bg-white/80 backdrop-blur-md border border-white/60 px-4 py-2.5 rounded-2xl rounded-br-sm shadow-[0_8px_15px_rgba(225,29,72,0.12)] mb-2 transition-all duration-300">
                    <p wire:loading.remove wire:target="kirimPesan" class="text-[11px] font-black text-red-500 tracking-wide drop-shadow-sm">Ready Jull! ✨</p>
                    <p wire:loading wire:target="kirimPesan" class="text-[11px] font-black text-rose-500 tracking-wide drop-shadow-sm">Mikir keras... 🧠</p>
                </div>
                
                <!-- Orb Kaca 3D -->
                <div class="relative w-14 h-14 rounded-full bg-gradient-to-tr from-rose-50 via-white to-rose-100 shadow-[0_10px_25px_rgba(225,29,72,0.25)] border-[2.5px] border-white flex items-center justify-center overflow-hidden transition-all duration-300">
                    <!-- Efek Cahaya Belakang (Muter Pas Mikir) -->
                    <div wire:loading.class="anim-jell-thinking" class="absolute inset-[-50%] bg-[conic-gradient(from_90deg_at_50%_50%,transparent_0%,#ffe4e6_50%,transparent_100%)] opacity-70"></div>
                    
                    <!-- Pantulan Cahaya Hologram -->
                    <div class="absolute top-1 left-2 w-5 h-2.5 bg-white rounded-full opacity-90 rotate-[-40deg] blur-[0.5px]"></div>
                    
                    <!-- Ekspresi Ikon -->
                    <i wire:loading.remove wire:target="kirimPesan" class="fas fa-robot text-red-500 text-[26px] drop-shadow-md relative z-10"></i>
                    <i wire:loading wire:target="kirimPesan" class="fas fa-cog fa-spin text-rose-500 text-[26px] drop-shadow-md relative z-10"></i>
                </div>
            </div>

            <!-- Preview Gambar Sebelum Dikirim -->
            @if($gambar)
            <div class="absolute bottom-[5.5rem] left-5 bg-white p-2 rounded-xl shadow-lg border border-slate-200 z-50 flex items-center gap-2 chat-appear">
                <img src="{{ $gambar->temporaryUrl() }}" class="w-14 h-14 object-cover rounded-lg border border-slate-100">
                <div class="flex flex-col pr-2">
                    <span class="text-[10px] font-bold text-slate-700">Gambar Siap!</span>
                    <button type="button" wire:click="$set('gambar', null)" class="text-[9px] text-red-500 font-bold hover:underline text-left">Batal</button>
                </div>
            </div>
            @endif

            <!-- FLOATING INPUT FORM -->
            <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-slate-50 via-slate-50/95 to-transparent z-50 pointer-events-auto">
                <form wire:submit.prevent="kirimPesan" class="flex items-center gap-2 bg-white p-1.5 rounded-full shadow-[0_15px_30px_rgba(0,0,0,0.08)] border border-slate-200 focus-within:border-red-400 focus-within:ring-4 focus-within:ring-red-500/10 transition-all">

                    <!-- TOMBOL AKSES GALERI/KAMERA -->
                    <label class="w-10 h-10 shrink-0 text-slate-300 hover:text-red-500 transition-colors cursor-pointer flex items-center justify-center rounded-full hover:bg-red-50">
                        <i class="fas fa-image text-[15px]"></i>
                        <input type="file" wire:model="gambar" class="hidden" accept="image/*">
                    </label>

                    <input wire:model="pesan" type="text" placeholder="Tanya atau suruh Jell..." class="flex-1 bg-transparent py-2.5 px-1 text-[13.5px] font-semibold text-slate-800 placeholder-slate-400 focus:outline-none" wire:loading.attr="disabled">

                    <button type="submit" class="w-10 h-10 shrink-0 bg-red-500 text-white rounded-full flex items-center justify-center shadow-md hover:bg-red-600 transition-colors active:scale-95 disabled:opacity-50 mr-0.5" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="kirimPesan, gambar">
                            <i class="fas fa-paper-plane text-xs -translate-x-[1px] translate-y-[1px]"></i>
                        </span>
                        <span wire:loading wire:target="kirimPesan, gambar">
                            <i class="fas fa-circle-notch fa-spin text-xs"></i>
                        </span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
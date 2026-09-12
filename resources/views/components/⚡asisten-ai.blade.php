<?php

use Livewire\Component;
use Illuminate\Support\Facades\Http;

new class extends Component {
    public string $pesan = '';
    public array $chats = [];

    // History khusus untuk dikirim ke API (format Gemini)
    // Ini terpisah dari $chats yang untuk tampilan UI
    public array $apiHistory = [];

    public string $identitasJell = "Kamu adalah Jell, asisten AI pribadi yang tertanam di dalam web Dashboard Productivity milik Rixsan Joulfiand (dipanggil Boss Jull). Gunakan bahasa santai dan gaul (lu/gue atau boss). Kamu ahli dalam programming (Laravel, Livewire, Alpine.js, Tailwind) dan hal sehari-hari. Jawab dengan ringkas dan langsung ke intinya.";

    public function mount(): void
    {
        $this->chats[] = [
            'role' => 'ai',
            'text' => "Heii Jull! ✨ Gue Jell, AI Assistant lo. Ada yang bisa gue bantu hari ini boss?",
            'time' => now()->format('H:i'),
        ];
    }

    public function kirimPesan(): void
    {
        $pesanUser = trim($this->pesan);
        if (empty($pesanUser)) return;

        // 1. Tampilkan pesan user di UI
        $this->chats[] = [
            'role'  => 'user',
            'text'  => $pesanUser,
            'time'  => now()->format('H:i'),
        ];

        // 2. Tambah ke history API (format Gemini)
        $this->apiHistory[] = [
            'role'  => 'user',
            'parts' => [['text' => $pesanUser]],
        ];

        $this->pesan = '';
        $this->dispatch('scrollBottom');

        // 3. Cek API Key
        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            $this->tambahPesanError("API Key Gemini belum diset di .env boss! Tambahkan GEMINI_API_KEY=xxx 🔴");
            return;
        }

        try {
            // 4. Kirim ke Gemini API dengan full conversation history
            // Model: gemini-3.8-flash (model terbaru yang aktif per Sept 2026)
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent?key=' . $apiKey,
                    [
                        // System instruction dikirim terpisah (cara yang benar untuk Gemini)
                        'system_instruction' => [
                            'parts' => [['text' => $this->identitasJell]],
                        ],
                        // Kirim SEMUA history, bukan cuma pesan terakhir
                        'contents' => $this->apiHistory,
                        // Batasi output biar tidak terlalu panjang/lambat
                        'generationConfig' => [
                            'maxOutputTokens' => 1024,
                            'temperature'     => 0.7,
                        ],
                    ]
                );

            if ($response->successful()) {
                $balasan = $response->json('candidates.0.content.parts.0.text')
                    ?? 'Jell bingung boss, coba tanya lagi ya 🤔';

                // 5. Tambah balasan Jell ke history API juga
                $this->apiHistory[] = [
                    'role'  => 'model',
                    'parts' => [['text' => $balasan]],
                ];

                $this->chats[] = [
                    'role' => 'ai',
                    'text' => $balasan,
                    'time' => now()->format('H:i'),
                ];
            } else {
                $errorMsg = $response->json('error.message') ?? 'Error tidak diketahui (HTTP ' . $response->status() . ')';
                $this->tambahPesanError("Kata Google: {$errorMsg} 😵");
            }

        } catch (\Exception $e) {
            $this->tambahPesanError("Koneksi ke Gemini gagal boss! Cek internet. Error: " . $e->getMessage() . " 🔌");
        }

        $this->dispatch('scrollBottom');
    }

    private function tambahPesanError(string $pesan): void
    {
        $this->chats[] = [
            'role' => 'ai',
            'text' => $pesan,
            'time' => now()->format('H:i'),
        ];
        $this->dispatch('scrollBottom');
    }
};
?>

<div>
    <style>
        @keyframes jellFloat {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-10px) scale(1.02); }
        }
        .anim-jell-float { animation: jellFloat 4s ease-in-out infinite; }

        @keyframes chatFadeUp {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .chat-appear { animation: chatFadeUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        .hide-scroll::-webkit-scrollbar { display: none; width: 0; }
    </style>

    <div x-cloak x-show="activeForm === 'asisten'" class="fixed inset-0 z-[80] flex justify-end pointer-events-none">

        <!-- Backdrop -->
        <div
            x-show="activeForm === 'asisten'"
            x-transition.opacity.duration.400ms
            @click="activeForm = ''"
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm pointer-events-auto"
        ></div>

        <!-- Panel Chatbot -->
        <div
            x-show="activeForm === 'asisten'"
            x-transition:enter="transition ease-out duration-400 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="relative w-full md:max-w-md h-screen bg-slate-50 pointer-events-auto flex flex-col overflow-hidden shadow-2xl border-l border-white/50"
        >

            <!-- Header -->
            <div class="bg-white/95 backdrop-blur-xl border-b border-slate-100 px-5 pt-12 pb-4 flex items-center justify-between z-30 shadow-sm shrink-0">
                <div class="flex items-center gap-4">
                    <button type="button" @click="activeForm = ''"
                        class="w-9 h-9 bg-slate-50 border border-slate-100 rounded-full text-slate-500 flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all shadow-sm active:scale-95">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="relative w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-rose-600 p-[2px] shadow-md">
                            <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                                <i class="fas fa-robot text-red-500 text-[15px]"></i>
                            </div>
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full"></div>
                        </div>
                        <div>
                            <h3 class="text-[17px] font-black text-slate-800 tracking-tight leading-none mb-1">Jell AI</h3>
                            <p class="text-[9px] text-red-500 font-extrabold uppercase tracking-widest flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span> Assistant
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Chat -->
            <div
                id="chat-container-ai"
                class="flex-1 overflow-y-auto px-5 pt-6 pb-40 space-y-6 hide-scroll relative z-10 flex flex-col"
                x-init="$watch('activeForm', val => {
                    if (val === 'asisten') setTimeout(() => {
                        let c = document.getElementById('chat-container-ai');
                        if (c) c.scrollTop = c.scrollHeight;
                    }, 100);
                })"
                @scroll-bottom.window="setTimeout(() => {
                    let c = document.getElementById('chat-container-ai');
                    if (c) c.scrollTop = c.scrollHeight;
                }, 50)"
            >
                <div class="text-center my-1">
                    <span class="px-4 py-1.5 bg-slate-200/50 border border-slate-200 text-slate-400 rounded-full text-[9px] font-extrabold tracking-widest uppercase shadow-sm">
                        Hari Ini
                    </span>
                </div>

                @foreach($chats as $chat)
                    @if($chat['role'] === 'ai')
                        <!-- Bubble Jell -->
                        <div class="flex flex-col gap-1 items-start chat-appear w-full">
                            <div class="flex items-end gap-2.5 max-w-[85%]">
                                <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm shrink-0 mb-1">
                                    <i class="fas fa-robot text-red-500 text-[10px]"></i>
                                </div>
                                <div class="bg-white text-slate-700 text-[13.5px] font-medium px-4 py-3.5 rounded-2xl rounded-bl-none shadow-sm border border-slate-100 leading-relaxed">
                                    {!! nl2br(e($chat['text'])) !!}
                                </div>
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 ml-[2.8rem]">{{ $chat['time'] }}</span>
                        </div>
                    @else
                        <!-- Bubble User -->
                        <div class="flex flex-col gap-1 items-end chat-appear w-full">
                            <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white text-[13.5px] font-medium px-4 py-3.5 rounded-2xl rounded-br-none shadow-md leading-relaxed max-w-[85%]">
                                {!! nl2br(e($chat['text'])) !!}
                            </div>
                            <span class="text-[9px] font-extrabold text-slate-400 mr-1 flex items-center gap-1">
                                {{ $chat['time'] }} <i class="fas fa-check-double text-red-500 ml-0.5"></i>
                            </span>
                        </div>
                    @endif
                @endforeach

                <!-- Loader -->
                <div wire:loading wire:target="kirimPesan" class="flex flex-col gap-1 items-start chat-appear w-full">
                    <div class="flex items-end gap-2.5 max-w-[85%]">
                        <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm shrink-0 mb-1">
                            <i class="fas fa-robot text-red-500 text-[10px]"></i>
                        </div>
                        <div class="bg-white px-4 py-4 rounded-2xl rounded-bl-none shadow-sm border border-slate-100 flex items-center gap-1.5">
                            <div class="w-1.5 h-1.5 bg-red-400 rounded-full animate-bounce"></div>
                            <div class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-1.5 h-1.5 bg-red-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orb Jell mengapung -->
            <div class="absolute bottom-24 right-5 z-40 flex items-center justify-end gap-3 pointer-events-none anim-jell-float">
                <div class="bg-white/90 backdrop-blur-sm border border-red-100 px-3.5 py-2 rounded-2xl rounded-br-sm shadow-md">
                    <p class="text-[10px] font-black text-red-500 tracking-wide">Heii Jull! ✨</p>
                </div>
                <div class="relative w-14 h-14 rounded-full bg-gradient-to-tr from-white to-slate-100 shadow-lg border border-white flex items-center justify-center overflow-hidden">
                    <div class="absolute inset-0 bg-red-500/10 animate-pulse"></div>
                    <div class="absolute top-1 left-2 w-5 h-2 bg-white rounded-full opacity-60 rotate-[-45deg] blur-[1px]"></div>
                    <i class="fas fa-robot text-red-500 text-2xl drop-shadow-md relative z-10"></i>
                </div>
            </div>

            <!-- Input Form -->
            <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-slate-50 via-slate-50/95 to-transparent z-50 pointer-events-none">
                <form wire:submit.prevent="kirimPesan"
                    class="flex items-center gap-2 bg-white p-1.5 rounded-full shadow-lg border border-slate-200 focus-within:border-red-400 focus-within:ring-4 focus-within:ring-red-500/10 transition-all pointer-events-auto">

                    <button type="button" class="w-10 h-10 shrink-0 text-slate-300 hover:text-red-500 transition-colors ml-0.5">
                        <i class="fas fa-keyboard text-[15px]"></i>
                    </button>

                    <input
                        wire:model="pesan"
                        type="text"
                        placeholder="Tanya apa saja ke Jell..."
                        class="flex-1 bg-transparent py-2.5 px-1 text-[13.5px] font-semibold text-slate-800 placeholder-slate-400 focus:outline-none"
                        wire:loading.attr="disabled"
                    >

                    <button
                        type="submit"
                        class="w-10 h-10 shrink-0 bg-red-500 text-white rounded-full flex items-center justify-center shadow-md hover:bg-red-600 transition-colors active:scale-95 disabled:opacity-50 mr-0.5"
                        wire:loading.attr="disabled"
                        wire:target="kirimPesan"
                    >
                        <span wire:loading.remove wire:target="kirimPesan">
                            <i class="fas fa-paper-plane text-xs -translate-x-[1px] translate-y-[1px]"></i>
                        </span>
                        <span wire:loading wire:target="kirimPesan">
                            <i class="fas fa-circle-notch fa-spin text-xs"></i>
                        </span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
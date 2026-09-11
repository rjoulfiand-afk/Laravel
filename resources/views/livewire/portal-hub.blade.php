<?php

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

new class extends Component
{
    public $viewState = 'home'; // home, lemari, form
    public $activeLemari = '';
    
    // Data
    public $semuaLemari = [];
    public $linksDiLemari = [];

    // Form Variables
    public $link_id = null;
    public $input_lemari = '';
    public $input_judul = '';
    public $input_url = '';

    public function boot()
    {
        $this->muatData();
    }

    public function muatData()
    {
        $user = User::where('email', 'boss@harian.com')->first();
        if ($user) {
            // Tarik nama-nama Lemari (Folder) yang unik
            $this->semuaLemari = DB::table('portal_links')
                ->where('user_id', $user->id)
                ->select('lemari')
                ->distinct()
                ->pluck('lemari');
                
            // Kalau lagi buka lemari tertentu, muat isi link-nya
            if ($this->viewState === 'lemari' && $this->activeLemari != '') {
                $this->linksDiLemari = DB::table('portal_links')
                    ->where('user_id', $user->id)
                    ->where('lemari', $this->activeLemari)
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }
    }

    // --- NAVIGASI ANTAR HALAMAN PORTAL ---
    public function bukaLemari($namaLemari)
    {
        $this->activeLemari = $namaLemari;
        $this->viewState = 'lemari';
        $this->muatData();
    }

    public function tambahBaru()
    {
        $this->resetForm();
        $this->input_lemari = $this->activeLemari; // Auto isi nama lemari kalau lagi buka lemari
        $this->viewState = 'form';
    }

    public function editLink($id)
    {
        $link = DB::table('portal_links')->where('id', $id)->first();
        $this->link_id = $link->id;
        $this->input_lemari = $link->lemari;
        $this->input_judul = $link->judul;
        $this->input_url = $link->url;
        $this->viewState = 'form';
    }

    public function hapusLink($id)
    {
        DB::table('portal_links')->where('id', $id)->delete();
        $this->muatData();
        // Kalau lemarinya kosong setelah dihapus, balik ke home
        if(count($this->linksDiLemari) == 0) {
            $this->viewState = 'home';
            $this->muatData();
        }
    }

    public function kembali()
    {
        if ($this->viewState === 'form') {
            $this->viewState = $this->activeLemari != '' ? 'lemari' : 'home';
        } elseif ($this->viewState === 'lemari') {
            $this->viewState = 'home';
            $this->activeLemari = '';
        }
        $this->muatData();
    }

    // --- PROSES SIMPAN ---
    public function simpan()
    {
        $user = User::where('email', 'boss@harian.com')->first();
        $data = [
            'user_id' => $user->id,
            'lemari' => strtoupper(trim($this->input_lemari)),
            'judul' => $this->input_judul,
            'url' => $this->input_url,
            'updated_at' => now(),
        ];

        if ($this->link_id) {
            DB::table('portal_links')->where('id', $this->link_id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('portal_links')->insert($data);
        }

        $this->activeLemari = $data['lemari'];
        $this->viewState = 'lemari';
        $this->muatData();
    }

    public function resetForm()
    {
        $this->link_id = null;
        $this->input_lemari = '';
        $this->input_judul = '';
        $this->input_url = '';
    }
};
?>

<div>
    <!-- Overlay & Bottom Sheet PORTAL -->
    <div x-cloak x-show="activeForm === 'portal'" class="fixed inset-0 z-[80] flex items-end justify-center pointer-events-none">
        <div x-show="activeForm === 'portal'" x-transition.opacity @click="activeForm = ''" class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm pointer-events-auto"></div>
        
        <div x-show="activeForm === 'portal'" 
             x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" 
             x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" 
             class="relative w-full h-[90vh] bg-gray-50 rounded-t-[2.5rem] shadow-2xl pointer-events-auto flex flex-col overflow-hidden">
            
            <!-- HEADER DINAMIS -->
            <div class="flex items-center gap-4 p-6 border-b border-gray-200 bg-white relative z-10 shadow-sm">
                <button type="button" @click="if($wire.viewState !== 'home') { $wire.kembali() } else { backToMenu() }" class="w-10 h-10 bg-gray-50 border border-gray-200 rounded-full text-gray-600 flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <i class="fas" :class="$wire.viewState !== 'home' ? 'fa-arrow-left' : 'fa-chevron-down'"></i>
                </button>
                <div class="flex-1">
                    <h3 class="text-xl font-extrabold text-gray-900">
                        @if($viewState === 'home') Portal Hub
                        @elseif($viewState === 'lemari') Lemari: {{ $activeLemari }}
                        @else Setup Link
                        @endif
                    </h3>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                        @if($viewState === 'home') Shortcut & Arsip
                        @elseif($viewState === 'lemari') Kumpulan Tautan Penting
                        @else Form Data Link
                        @endif
                    </p>
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas {{ $viewState === 'home' ? 'fa-rocket' : ($viewState === 'lemari' ? 'fa-folder-open' : 'fa-link') }} text-lg"></i>
                </div>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1">
                
                <!-- STATE 1: HOME (SHORTCUT IPHONE + DAFTAR LEMARI) -->
                @if($viewState === 'home')
                    <!-- SHORTCUT APPS -->
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Akses Cepat</h3>
                    <div class="grid grid-cols-5 gap-3 mb-8">
                        <!-- GitHub -->
                        <a href="https://github.com/rjoulfiand-afk" target="_blank" class="flex flex-col items-center gap-2 group">
                            <div class="w-12 h-12 bg-gray-900 text-white rounded-2xl shadow-md flex items-center justify-center text-2xl group-active:scale-95 transition-transform"><i class="fab fa-github"></i></div>
                            <span class="text-[9px] font-bold text-gray-600">GitHub</span>
                        </a>
                        <!-- Instagram -->
                        <a href="https://instagram.com" target="_blank" class="flex flex-col items-center gap-2 group">
                            <div class="w-12 h-12 bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-500 text-white rounded-2xl shadow-md flex items-center justify-center text-2xl group-active:scale-95 transition-transform"><i class="fab fa-instagram"></i></div>
                            <span class="text-[9px] font-bold text-gray-600">Instagram</span>
                        </a>
                        <!-- WhatsApp -->
                        <a href="https://wa.me" target="_blank" class="flex flex-col items-center gap-2 group">
                            <div class="w-12 h-12 bg-green-500 text-white rounded-2xl shadow-md flex items-center justify-center text-2xl group-active:scale-95 transition-transform"><i class="fab fa-whatsapp"></i></div>
                            <span class="text-[9px] font-bold text-gray-600">WhatsApp</span>
                        </a>
                        <!-- TikTok -->
                        <a href="https://tiktok.com" target="_blank" class="flex flex-col items-center gap-2 group">
                            <div class="w-12 h-12 bg-black text-white rounded-2xl shadow-md flex items-center justify-center text-2xl group-active:scale-95 transition-transform"><i class="fab fa-tiktok"></i></div>
                            <span class="text-[9px] font-bold text-gray-600">TikTok</span>
                        </a>
                        <!-- GoPay -->
                        <a href="#" class="flex flex-col items-center gap-2 group">
                            <div class="w-12 h-12 bg-blue-500 text-white rounded-2xl shadow-md flex items-center justify-center text-xl group-active:scale-95 transition-transform"><i class="fas fa-wallet"></i></div>
                            <span class="text-[9px] font-bold text-gray-600">GoPay</span>
                        </a>
                    </div>

                    <!-- LEMARI LINK (KATEGORI) -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Lemari Link Pintar</h3>
                        <button wire:click="tambahBaru" class="text-[10px] bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded-full font-bold transition-colors">+ Link Baru</button>
                    </div>

                    @if(count($semuaLemari) == 0)
                        <div class="p-8 bg-white rounded-3xl border-2 border-dashed border-gray-200 text-center">
                            <i class="fas fa-cabinet-filing text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-bold text-sm">Lemari masih kosong.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($semuaLemari as $lemari)
                                <button wire:click="bukaLemari('{{ $lemari }}')" class="w-full bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between hover:border-blue-300 hover:shadow-md transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-folder"></i></div>
                                        <div class="text-left">
                                            <h4 class="font-extrabold text-gray-800 text-sm">{{ $lemari }}</h4>
                                            <p class="text-[9px] font-bold text-gray-400">Klik untuk melihat isi link</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                                </button>
                            @endforeach
                        </div>
                    @endif

                <!-- STATE 2: ISI LEMARI (DAFTAR LINK) -->
                @elseif($viewState === 'lemari')
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-extrabold text-gray-800"><i class="fas fa-link text-blue-500 mr-1"></i> Isi Lemari Ini</h3>
                        <button wire:click="tambahBaru" class="px-4 py-2 bg-blue-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full hover:bg-blue-700 transition-colors shadow-md">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($linksDiLemari as $link)
                            <div x-data="{ copied: false }" class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col gap-3 relative group">
                                <!-- Tombol Aksi Hapus & Edit (Di Pojok Atas) -->
                                <div class="absolute top-3 right-3 flex gap-2">
                                    <button wire:click="editLink({{ $link->id }})" class="w-7 h-7 bg-gray-50 hover:bg-blue-50 text-gray-400 hover:text-blue-500 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button wire:click="hapusLink({{ $link->id }})" class="w-7 h-7 bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-full flex items-center justify-center transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>

                                <div>
                                    <h4 class="font-black text-gray-900 text-sm pr-16 leading-tight">{{ $link->judul }}</h4>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Tersimpan di Vault</p>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex justify-between items-center gap-3">
                                    <p class="text-blue-500 font-mono text-xs truncate">{{ $link->url }}</p>
                                    
                                    <div class="flex gap-2 shrink-0">
                                        <!-- Tombol Copy -->
                                        <button @click="navigator.clipboard.writeText('{{ $link->url }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                class="w-8 h-8 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 flex items-center justify-center transition-all">
                                            <i class="fas" :class="copied ? 'fa-check text-green-600' : 'fa-copy'"></i>
                                        </button>
                                        <!-- Tombol Kunjungi Langsung -->
                                        <a href="{{ $link->url }}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition-all shadow-sm">
                                            <i class="fas fa-external-link-alt text-[10px]"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                <!-- STATE 3: FORM TAMBAH / EDIT LINK -->
                @else
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-5">
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Pilih / Buat Lemari Baru</label>
                            <input type="text" wire:model="input_lemari" placeholder="Cth: LINK TUGAS KELOMPOK" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-900 focus:outline-none focus:border-blue-500 transition-colors uppercase">
                            <p class="text-[9px] text-gray-400 mt-1">*Ketik nama baru untuk bikin lemari baru, atau samakan dengan yang ada.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Judul Link (Wajib Jelas)</label>
                            <input type="text" wire:model="input_judul" placeholder="Cth: Drive Pengumpulan Pak Adi" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-900 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">URL / Tautan Asli</label>
                            <input type="url" wire:model="input_url" placeholder="Cth: https://drive.google.com/..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-blue-600 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>

                        <button wire:click="simpan" class="w-full bg-blue-600 text-white font-bold text-sm py-4 rounded-2xl shadow-lg hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-2 mt-4">
                            <span wire:loading.remove wire:target="simpan"><i class="fas fa-save"></i> Simpan ke Lemari</span>
                            <span wire:loading wire:target="simpan"><i class="fas fa-circle-notch fa-spin"></i> Menyimpan...</span>
                        </button>
                        
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
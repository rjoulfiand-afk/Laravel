<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Models\User;

new class extends Component
{
    use WithFileUploads; // Sihir biar Livewire bisa nerima file dari galeri HP!

    public $wallets = [];
    public $isFormOpen = false; // Deteksi lagi buka daftar dompet atau buka form

    // Form Variables
    public $wallet_id = null;
    public $provider = 'DANA';
    public $account_number = '';
    public $account_name = '';
    public $qr_image; // Nangkep file upload baru
    public $existing_qr; // Nampilin file lama kalau lagi edit

    public function boot()
    {
        $this->muatData();
    }

    public function muatData()
    {
        $user = User::where('email', 'boss@harian.com')->first();
        if ($user) {
            $this->wallets = DB::table('wallets')->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        }
    }

    public function tambahBaru()
    {
        $this->resetForm();
        $this->isFormOpen = true;
    }

    public function editWallet($id)
    {
        $wallet = DB::table('wallets')->where('id', $id)->first();
        $this->wallet_id = $wallet->id;
        $this->provider = $wallet->provider;
        $this->account_number = $wallet->account_number;
        $this->account_name = $wallet->account_name;
        $this->existing_qr = $wallet->qr_image_path;
        $this->isFormOpen = true;
    }

    public function hapusWallet($id)
    {
        DB::table('wallets')->where('id', $id)->delete();
        $this->muatData();
    }

    public function simpan()
    {
        $user = User::where('email', 'boss@harian.com')->first();
        
        $data = [
            'user_id' => $user->id,
            'provider' => $this->provider,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'updated_at' => now(),
        ];

        // Cek kalau ada file QR baru yang diupload dari HP/Laptop
        if ($this->qr_image) {
            $path = $this->qr_image->store('qris', 'public');
            $data['qr_image_path'] = $path;
        }

        if ($this->wallet_id) {
            DB::table('wallets')->where('id', $this->wallet_id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('wallets')->insert($data);
        }

        $this->resetForm();
        $this->muatData();
    }

    public function resetForm()
    {
        $this->wallet_id = null;
        $this->provider = 'DANA';
        $this->account_number = '';
        $this->account_name = '';
        $this->qr_image = null;
        $this->existing_qr = null;
        $this->isFormOpen = false;
    }
};
?>

<div>
    <!-- Overlay & Bottom Sheet -->
    <div x-cloak x-show="activeForm === 'dompet'" class="fixed inset-0 z-[80] flex items-end justify-center pointer-events-none">
        <div x-show="activeForm === 'dompet'" x-transition.opacity @click="activeForm = ''" class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm pointer-events-auto"></div>
        
        <div x-show="activeForm === 'dompet'" 
             x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" 
             x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" 
             class="relative w-full h-[90vh] bg-gray-50 rounded-t-[2.5rem] shadow-2xl pointer-events-auto flex flex-col overflow-hidden">
            
            <!-- HEADER -->
            <div class="flex items-center gap-4 p-6 border-b border-gray-200 bg-white relative z-10">
                <button type="button" @click="if($wire.isFormOpen) { $wire.resetForm() } else { backToMenu() }" class="w-10 h-10 bg-gray-50 border border-gray-200 rounded-full text-gray-600 flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <i class="fas" :class="$wire.isFormOpen ? 'fa-arrow-left' : 'fa-chevron-down'"></i>
                </button>
                <div class="flex-1">
                    <h3 class="text-xl font-extrabold text-gray-900" x-text="$wire.isFormOpen ? ($wire.wallet_id ? 'Edit Dompet' : 'Tambah Dompet') : 'Dompet Digital'"></h3>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Aset & Penerimaan Dana</p>
                </div>
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-wallet text-lg"></i>
                </div>
            </div>
            
            <div class="p-6 overflow-y-auto flex-1">
                
                <!-- STATE 1: DAFTAR DOMPET -->
                @if(!$isFormOpen)
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-extrabold text-gray-800"><i class="fas fa-layer-group text-red-500 mr-1"></i> Koleksi Dompet</h3>
                        <button wire:click="tambahBaru" class="px-4 py-2 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest rounded-full hover:bg-gray-800 transition-colors shadow-md">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>

                    @if(count($wallets) == 0)
                        <div class="p-8 bg-white rounded-3xl border-2 border-dashed border-gray-200 text-center flex flex-col items-center justify-center">
                            <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-bold text-sm">Belum ada dompet tersimpan.</p>
                            <p class="text-gray-400 text-[10px]">Klik tombol tambah untuk mendaftarkan e-wallet / bank.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($wallets as $wallet)
                                @php
                                    // Logika Warna Dinamis Berdasar Bank/E-Wallet
                                    $bg = 'bg-gray-800'; $icon = 'fa-wallet';
                                    $p = strtoupper($wallet->provider);
                                    if(str_contains($p, 'DANA')) { $bg = 'bg-blue-500'; $icon = 'fa-mobile-alt'; }
                                    elseif(str_contains($p, 'GOPAY')) { $bg = 'bg-emerald-500'; $icon = 'fa-motorcycle'; }
                                    elseif(str_contains($p, 'OVO')) { $bg = 'bg-purple-600'; $icon = 'fa-ring'; }
                                    elseif(str_contains($p, 'BCA')) { $bg = 'bg-blue-800'; $icon = 'fa-university'; }
                                    elseif(str_contains($p, 'MANDIRI')) { $bg = 'bg-yellow-500'; $icon = 'fa-university'; }
                                    elseif(str_contains($p, 'SHOPEE')) { $bg = 'bg-orange-500'; $icon = 'fa-shopping-bag'; }
                                @endphp

                                <div x-data="{ copied: false }" class="{{ $bg }} rounded-3xl p-6 relative overflow-hidden shadow-lg group">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
                                    
                                    <!-- Aksi Edit & Hapus (Nongol kalau kartu disentuh) -->
                                    <div class="absolute top-4 right-4 flex gap-2 z-20">
                                        <button wire:click="editWallet({{ $wallet->id }})" class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm text-white flex items-center justify-center hover:bg-white/40 transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button wire:click="hapusWallet({{ $wallet->id }})" class="w-8 h-8 rounded-full bg-red-500/80 backdrop-blur-sm text-white flex items-center justify-center hover:bg-red-600 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                    </div>

                                    <div class="relative z-10 flex flex-col gap-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-sm"><i class="fas {{ $icon }} text-lg"></i></div>
                                            <div>
                                                <h4 class="text-white font-black text-xl tracking-tight">{{ $wallet->provider }}</h4>
                                                <p class="text-white/70 text-[10px] font-bold uppercase tracking-widest">{{ $wallet->account_name }}</p>
                                            </div>
                                        </div>

                                        <div class="bg-black/20 rounded-2xl p-4 backdrop-blur-md border border-white/10 flex justify-between items-center">
                                            <p class="text-white font-mono text-lg tracking-widest font-semibold truncate pr-4">{{ $wallet->account_number }}</p>
                                            <button @click="navigator.clipboard.writeText('{{ $wallet->account_number }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                                    class="shrink-0 w-10 h-10 rounded-full bg-white text-gray-900 flex items-center justify-center hover:scale-105 transition-all shadow-md">
                                                <i class="fas" :class="copied ? 'fa-check text-emerald-500' : 'fa-copy'"></i>
                                            </button>
                                        </div>

                                        <!-- Menampilkan QR Code Asli Kalau Ada -->
                                        @if($wallet->qr_image_path)
                                            <div class="mt-2 bg-white rounded-2xl p-4 flex flex-col items-center gap-2 border-2 border-dashed border-white/40">
                                                <p class="text-gray-800 text-[10px] font-black uppercase tracking-widest">Scan QRIS</p>
                                                <img src="{{ asset('storage/' . $wallet->qr_image_path) }}" alt="QR" class="w-32 h-32 object-contain rounded-xl border border-gray-100">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                <!-- STATE 2: FORM TAMBAH / EDIT -->
                @else
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-5">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Bank / E-Wallet</label>
                            <input type="text" wire:model="provider" placeholder="Cth: DANA, BCA, OVO" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-900 focus:outline-none focus:border-red-500 focus:bg-white transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor Rekening / HP</label>
                            <input type="text" wire:model="account_number" placeholder="Cth: 08123456789" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-900 focus:outline-none focus:border-red-500 focus:bg-white transition-colors font-mono">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Atas Nama</label>
                            <input type="text" wire:model="account_name" placeholder="Cth: Rixsan Joulfiand" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-900 focus:outline-none focus:border-red-500 focus:bg-white transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 flex justify-between">
                                <span>Upload QRIS (Opsional)</span>
                                <i class="fas fa-camera text-red-500"></i>
                            </label>
                            
                            <!-- Input File yang bisa ngebuka Galeri HP -->
                            <div class="relative w-full bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-100 transition-colors cursor-pointer overflow-hidden group">
                                <input type="file" wire:model="qr_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                
                                <div class="relative z-10 flex flex-col items-center pointer-events-none">
                                    <!-- Indikator Loading Saat File Diupload ke Livewire Server -->
                                    <div wire:loading wire:target="qr_image" class="mb-2 text-red-500">
                                        <i class="fas fa-spinner fa-spin text-2xl"></i>
                                    </div>

                                    @if($qr_image)
                                        <p class="text-emerald-500 font-bold text-sm"><i class="fas fa-check-circle mr-1"></i> Foto Siap Disimpan!</p>
                                    @elseif($existing_qr)
                                        <p class="text-blue-500 font-bold text-sm"><i class="fas fa-image mr-1"></i> QR Sudah Ada (Klik buat ganti)</p>
                                    @else
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2 group-hover:text-red-500 transition-colors"></i>
                                        <p class="text-gray-500 font-bold text-sm">Pilih foto dari Galeri HP/Laptop</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button wire:click="simpan" class="w-full bg-red-600 text-white font-bold text-lg py-4 rounded-2xl shadow-lg hover:bg-red-700 active:scale-95 transition-all flex items-center justify-center gap-2 mt-4">
                            <span wire:loading.remove wire:target="simpan"><i class="fas fa-save"></i> Simpan Dompet Asli</span>
                            <span wire:loading wire:target="simpan"><i class="fas fa-circle-notch fa-spin"></i> Menyimpan...</span>
                        </button>
                        
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
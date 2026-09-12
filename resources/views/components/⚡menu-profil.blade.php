<?php
use Livewire\Component;
new class extends Component {};
?>

<div>
    <!-- Overlay & Bottom Sheet Menu Profil -->
    <div x-data="{ lobiBuka: false }" @buka-lobi.window="lobiBuka = true" x-cloak x-show="lobiBuka" class="fixed inset-0 z-[90] flex items-end justify-center pointer-events-none">
        
        <div x-show="lobiBuka" x-transition.opacity.duration.400ms @click="lobiBuka = false" class="absolute inset-0 bg-gray-900/50 backdrop-blur-md pointer-events-auto"></div>
        
        <div x-show="lobiBuka" 
             x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" 
             x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" 
             class="relative w-full max-w-md bg-white rounded-t-[2.5rem] shadow-2xl pointer-events-auto flex flex-col overflow-hidden pb-10 border-t border-gray-100">
            
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mt-4 mb-2"></div>
            
            <!-- HEADER MEWAH -->
            <div class="px-7 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Julljhha Account</h3>
                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest flex items-center gap-1.5 mt-1">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span> Sistem Aktif
                    </p>
                </div>
                <button @click="lobiBuka = false" class="w-10 h-10 bg-gray-50 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 border border-gray-100 flex items-center justify-center transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- LIST MENU CARDS -->
            <div class="px-6 space-y-3 mt-3">
                
                <!-- MENU 1: HISTORI (Desain Dark Mode Mewah) -->
                <button @click="lobiBuka = false; setTimeout(() => $dispatch('buka-active-form', 'histori'), 300)" class="w-full bg-slate-900 rounded-2xl p-4 flex items-center gap-4 transition-all group shadow-lg shadow-slate-900/20 hover:scale-[1.02] border border-slate-700">
                    <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-blue-400 text-xl border border-white/10 group-hover:rotate-12 transition-all"><i class="fas fa-history"></i></div>
                    <div class="text-left flex-1">
                        <h4 class="font-extrabold text-white text-sm tracking-wide">Log & Histori Sistem</h4>
                        <p class="text-[10px] font-medium text-slate-400 mt-0.5">Lacak file dihapus & aktivitas</p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-500 group-hover:text-blue-400 transition-colors"></i>
                </button>

                <!-- MENU 2: UBAH FOTO PROFIL (Desain Clean Modern) -->
                <!-- OTAK PENGGANTI FOTO REALTIME -->
                <input type="file" id="upload-avatar-mesin" class="hidden" accept="image/*" 
                       @change="
                           let file = $event.target.files[0];
                           if(file) {
                               let reader = new FileReader();
                               reader.onload = (e) => {
                                   $dispatch('ganti-foto-profil', e.target.result); // Kirim foto base64 ke Beranda!
                                   lobiBuka = false; // Tutup lobi otomatis
                               };
                               reader.readAsDataURL(file);
                           }
                       ">
                
                <button @click="document.getElementById('upload-avatar-mesin').click()" class="w-full bg-emerald-50/50 hover:bg-emerald-50 rounded-2xl p-4 flex items-center gap-4 transition-all group border border-emerald-100 hover:border-emerald-300">
                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-emerald-600 text-xl group-hover:scale-110 transition-all border border-emerald-50"><i class="fas fa-camera-retro"></i></div>
                    <div class="text-left flex-1">
                        <h4 class="font-extrabold text-gray-800 text-sm">Ubah Foto Profil</h4>
                        <p class="text-[10px] font-bold text-gray-400 mt-0.5">Pilih dari Galeri HP/Laptop</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-emerald-500 transition-colors"></i>
                </button>

                <!-- MENU 3: RELATIONSHIP (VIP Design Dipertahankan) -->
                <button @click="lobiBuka = false; setTimeout(() => $dispatch('buka-3d'), 300)" class="w-full bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl p-4 flex items-center gap-4 transition-all group relative overflow-hidden shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 hover:-translate-y-1">
                    <i class="fas fa-heart absolute -right-2 -bottom-4 text-7xl text-white/10 group-hover:scale-150 group-hover:text-white/20 transition-transform duration-700"></i>
                    
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-xl border border-white/30 group-hover:scale-110 transition-all"><i class="fas fa-gem"></i></div>
                    <div class="text-left flex-1 relative z-10">
                        <h4 class="font-extrabold text-white text-sm tracking-wide">Ruang Relationship</h4>
                        <p class="text-[10px] font-bold text-pink-100 mt-0.5">Jull & Mickhayla</p>
                    </div>
                    <i class="fas fa-chevron-right text-white/60 group-hover:text-white transition-colors relative z-10"></i>
                </button>

            </div>
        </div>
    </div>
</div>
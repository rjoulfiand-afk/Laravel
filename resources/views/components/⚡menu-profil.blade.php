<?php

use Livewire\Component;

new class extends Component
{
    // Komponen UI Menu Lobi
};
?>

<div>
    <!-- Overlay & Bottom Sheet Menu Profil -->
    <div x-cloak x-show="activeForm === 'menu-profil'" class="fixed inset-0 z-[90] flex items-end justify-center pointer-events-none">
        <!-- Background Blur -->
        <div x-show="activeForm === 'menu-profil'" x-transition.opacity @click="activeForm = ''" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm pointer-events-auto"></div>
        
        <!-- Sheet Container -->
        <div x-show="activeForm === 'menu-profil'" 
             x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" 
             x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" 
             class="relative w-full max-w-md bg-gray-50 rounded-t-[2.5rem] shadow-2xl pointer-events-auto flex flex-col overflow-hidden pb-8">
            
            <!-- HEADER -->
            <div class="flex items-center gap-4 p-6 border-b border-gray-200 bg-white relative z-10">
                <button type="button" @click="activeForm = ''" class="w-10 h-10 bg-gray-50 border border-gray-200 rounded-full text-gray-600 flex items-center justify-center hover:bg-gray-100 transition-colors">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="flex-1">
                    <h3 class="text-xl font-extrabold text-gray-900">Akun & Sistem</h3>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Pengaturan & Log</p>
                </div>
            </div>
            
            <!-- LIST MENU -->
            <div class="p-6 space-y-4">
                
                <!-- MENU 1: HISTORI -->
                <button @click="activeForm = 'histori'" class="w-full bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between hover:border-blue-300 hover:shadow-md transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform"><i class="fas fa-history"></i></div>
                        <div class="text-left">
                            <h4 class="font-extrabold text-gray-800 text-sm">Histori & Log</h4>
                            <p class="text-[9px] font-bold text-gray-400 mt-0.5">Pantau data dihapus, tugas, dan aktivitas web</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                </button>

                <!-- MENU 2: UBAH FOTO PROFIL -->
                <button @click="activeForm = 'ubah-foto'" class="w-full bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between hover:border-emerald-300 hover:shadow-md transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform"><i class="fas fa-image"></i></div>
                        <div class="text-left">
                            <h4 class="font-extrabold text-gray-800 text-sm">Ubah Foto Profil</h4>
                            <p class="text-[9px] font-bold text-gray-400 mt-0.5">Ganti avatar dari galeri HP/Laptop</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-emerald-500 transition-colors"></i>
                </button>

                <!-- MENU 3: RELATIONSHIP (KARTU 3D) -->
                <button @click="activeForm = 'profil'" class="w-full bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-4 shadow-sm border border-pink-100 flex items-center justify-between hover:border-pink-300 hover:shadow-md transition-all group relative overflow-hidden">
                    <!-- Efek cinta melayang -->
                    <i class="fas fa-heart absolute -right-4 -top-4 text-5xl text-pink-500/10 group-hover:scale-150 transition-transform duration-500"></i>
                    
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 bg-gradient-to-tr from-red-500 to-pink-500 text-white rounded-xl flex items-center justify-center text-xl group-hover:scale-110 shadow-md transition-transform"><i class="fas fa-user-friends"></i></div>
                        <div class="text-left">
                            <h4 class="font-extrabold text-gray-900 text-sm">Relationship</h4>
                            <p class="text-[9px] font-bold text-pink-600 mt-0.5">Akses Kartu 3D Julljhha & Mickhayla</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-pink-300 group-hover:text-pink-500 transition-colors relative z-10"></i>
                </button>

            </div>
        </div>
    </div>
</div>
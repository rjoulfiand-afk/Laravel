<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Harian | Rixsan</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        ::-webkit-scrollbar { display: none; }
        [x-cloak] { display: none !important; }
        textarea.paper-style { background-image: linear-gradient(transparent, transparent 28px, #e5e7eb 28px); background-size: 100% 30px; line-height: 30px; }
        
        @keyframes float-bubble {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-8px) scale(1.02); }
        }
        .animate-float { animation: float-bubble 3s ease-in-out infinite; }
        .bubble-shape { border-radius: 45% 55% 40% 60% / 55% 45% 60% 40%; transition: border-radius 0.3s ease; }
        .bubble-shape:hover { border-radius: 50%; }
        
        .blow-away-leave-active { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .blow-away-leave-to { opacity: 0; transform: translateY(-100px) scale(1.5) rotate(15deg); filter: blur(10px); }
    </style>
    @livewireStyles
</head>

<body x-data="{ 
        // 1. LOGIKA MENU BAWAH
        showAddMenu: false, 
        activeForm: '',
        backToMenu() {
            this.activeForm = ''; 
            setTimeout(() => this.showAddMenu = true, 300);
        },

        // 2. LOGIKA GELEMBUNG CATATAN
        showNoteModal: false,
        activeNote: null,
        bukaGelembung(note) {
            this.activeNote = note;
            this.showNoteModal = true;
        },
        tiupGelembung() {
            this.showNoteModal = false;
            setTimeout(() => this.activeNote = null, 500); 
        },
        dummyNotes: [
            { id: 1, title: 'Ide Web Sekolah', date: 'Hari ini, 07:30', color: 'from-blue-400 to-cyan-300', icon: 'fa-code', text: 'Bikin UI survey buat SMKN 10 pake Laravel Livewire.' },
            { id: 2, title: 'Beli Jajanan', date: 'Kemarin', color: 'from-pink-400 to-rose-300', icon: 'fa-shopping-bag', text: 'Jangan lupa beli basreng di kantin sekolah besok.' },
            { id: 3, title: 'Script Bot', date: '12 Mei', color: 'from-purple-400 to-fuchsia-300', icon: 'fa-robot', text: 'Perbaiki regex di bot telegram biar ngga error pas baca log.' }
        ],

        // 3. LOGIKA TUGAS
        taskPriority: 'normal',
        taskDate: 'besok',

        // 4. LOGIKA NABUNG (UANG MASUK)
        uangStr: '',
        keteranganNabung: '',
        formatRupiah(event) {
            let angka = event.target.value.replace(/[^0-9]/g, '');
            this.uangStr = angka ? new Intl.NumberFormat('id-ID').format(angka) : '';
        },
        tambahUang(nominal, reset = false) {
            if (reset) { this.uangStr = ''; return; }
            let current = parseInt(this.uangStr.replace(/[^0-9]/g, '')) || 0;
            this.uangStr = new Intl.NumberFormat('id-ID').format(current + nominal);
        },

        // 5. LOGIKA PENGELUARAN (UANG KELUAR)
        uangKeluarStr: '',
        keteranganKeluar: '',
        formatRupiahKeluar(event) {
            let angka = event.target.value.replace(/[^0-9]/g, '');
            this.uangKeluarStr = angka ? new Intl.NumberFormat('id-ID').format(angka) : '';
        },
        tambahUangKeluar(nominal, reset = false) {
            if (reset) { this.uangKeluarStr = ''; return; }
            let current = parseInt(this.uangKeluarStr.replace(/[^0-9]/g, '')) || 0;
            this.uangKeluarStr = new Intl.NumberFormat('id-ID').format(current + nominal);
        }
    }" 
    class="bg-gray-50 text-gray-800 h-screen overflow-hidden flex flex-col relative">

    <livewire:beranda />

    <!-- Bottom Nav -->
    <nav class="fixed bottom-0 w-full px-6 py-4 bg-white rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.05)] z-40">
        <ul class="flex justify-between items-center text-gray-400">
            <li><a href="/harian" class="flex flex-col items-center gap-1.5 text-red-600"><i class="fas fa-home text-xl"></i><span class="text-[9px] font-bold tracking-widest uppercase">Beranda</span></a></li>
            <li>
    <button type="button" @click="activeForm = 'dompet'" class="flex flex-col items-center gap-1.5 hover:text-red-500 transition-colors focus:outline-none">
        <i class="fas fa-wallet text-xl"></i>
        <span class="text-[9px] font-bold tracking-widest uppercase">Uang</span>
    </button>
</li>
            <li class="-mt-12 relative">
                <div class="absolute inset-0 bg-red-600 blur-lg opacity-40 rounded-full"></div>
                <button @click="showAddMenu = true" class="relative flex items-center justify-center w-14 h-14 bg-red-600 rounded-full text-white hover:scale-105 active:scale-95 transition-all shadow-lg">
                    <i class="fas fa-plus text-2xl transition-transform duration-300" :class="{'rotate-45': showAddMenu}"></i>
                </button>
            </li>
            <li><a href="#" class="flex flex-col items-center gap-1.5 hover:text-red-500"><i class="fas fa-clipboard-list text-xl"></i><span class="text-[9px] font-bold tracking-widest uppercase">Tugas</span></a></li>
            <li><a href="#" class="flex flex-col items-center gap-1.5 hover:text-red-500"><i class="fas fa-robot text-xl"></i><span class="text-[9px] font-bold tracking-widest uppercase">Asisten</span></a></li>
        </ul>
    </nav>

    <!-- ========================================== -->
    <!-- 🌟 MODAL BACA CATATAN (GELEMBUNG PECAH) 🌟 -->
    <!-- ========================================== -->
    <div x-cloak x-show="showNoteModal" class="fixed inset-0 z-[70] flex items-center justify-center pointer-events-none p-6">
        <div x-show="showNoteModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-gray-900/40 backdrop-blur-md pointer-events-auto" @click="tiupGelembung()"></div>
        
        <div x-show="showNoteModal" 
             x-transition:enter="transition ease-out duration-300 transform" 
             x-transition:enter-start="scale-50 opacity-0 translate-y-10" 
             x-transition:enter-end="scale-100 opacity-100 translate-y-0" 
             x-transition:leave="blow-away-leave-active"
             x-transition:leave-start="scale-100 opacity-100 translate-y-0 rotate-0 filter-none"
             x-transition:leave-end="blow-away-leave-to"
             class="relative w-full max-w-sm bg-white rounded-[2rem] p-6 shadow-2xl pointer-events-auto"
             x-data="{ note: {} }" x-init="$watch('activeNote', val => { if(val) note = val })">
            
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-12 h-6 bg-white/50 backdrop-blur-sm rounded-full border border-gray-200 shadow-sm flex items-center justify-center">
                <div class="w-2 h-2 bg-red-400 rounded-full shadow-inner"></div>
            </div>

            <button @click="tiupGelembung()" class="absolute top-4 right-4 w-8 h-8 bg-gray-100 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-200 flex items-center justify-center transition-colors">
                <i class="fas fa-wind text-sm"></i>
            </button>

            <div class="mt-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white bg-gradient-to-br shadow-md" :class="note.color">
                        <i class="fas text-xl" :class="note.icon"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900 leading-tight" x-text="note.title"></h2>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider" x-text="note.date"></p>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 min-h-[150px]">
                    <p class="text-gray-700 font-medium leading-relaxed" x-text="note.text"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 1. POPUP MENU PILIHAN AWAL -->
    <!-- ========================================== -->
    <div x-cloak x-show="showAddMenu" class="fixed inset-0 z-50 flex items-end justify-center pointer-events-none">
        <div x-show="showAddMenu" x-transition.opacity.duration.300ms @click="showAddMenu = false" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm pointer-events-auto"></div>
        <div x-show="showAddMenu" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="relative w-full bg-white rounded-t-[2.5rem] p-6 pb-12 shadow-2xl pointer-events-auto">
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-6"></div>
            <h3 class="text-lg font-extrabold text-gray-800 mb-6 text-center">Mau catat apa sekarang?</h3>
            <div class="grid grid-cols-4 gap-4">
                <button @click="showAddMenu = false; setTimeout(() => activeForm = 'nabung', 250)" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl group-active:scale-95 transition-transform"><i class="fas fa-piggy-bank"></i></div>
                    <span class="text-[10px] font-bold text-gray-600">Nabung</span>
                </button>
                <button @click="showAddMenu = false; setTimeout(() => activeForm = 'keluar', 250)" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-2xl group-active:scale-95 transition-transform"><i class="fas fa-shopping-cart"></i></div>
                    <span class="text-[10px] font-bold text-gray-600">Keluar</span>
                </button>
                <button @click="showAddMenu = false; setTimeout(() => activeForm = 'tugas', 250)" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl group-active:scale-95 transition-transform"><i class="fas fa-clipboard-check"></i></div>
                    <span class="text-[10px] font-bold text-gray-600">Tugas</span>
                </button>
                <button @click="showAddMenu = false; setTimeout(() => activeForm = 'catatan', 250)" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl group-active:scale-95 transition-transform"><i class="fas fa-pen-nib"></i></div>
                    <span class="text-[10px] font-bold text-gray-600">Catatan</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 2. JEROAN: FORM NABUNG -->
    <!-- ========================================== -->
    <livewire:form-nabung />

    <!-- ========================================== -->
    <!-- 3. JEROAN: FORM TUGAS -->
    <!-- ========================================== -->
    <livewire:form-tugas />

    <livewire:form-keluar />
    <!-- ========================================== -->
    <!-- 5. JEROAN: FORM CATATAN -->
    <!-- ========================================== -->
    <livewire:form-catatan />

    <!-- ========================================== -->
    <!-- 💳 JEROAN: DOMPET DIGITAL PRIBADI -->
    <!-- ========================================== -->
    <livewire:dompet-pribadi />
    
    @livewireScripts
</body>
</html>
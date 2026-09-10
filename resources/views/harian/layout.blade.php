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

    <!-- ========================================== -->
    <!-- 4. JEROAN: FORM PENGELUARAN (Masih HTML manual, nunggu giliran dibikin Livewire) -->
    <!-- ========================================== -->
    <div x-cloak x-show="activeForm === 'keluar'" class="fixed inset-0 z-[60] flex items-end justify-center pointer-events-none">
        <div x-show="activeForm === 'keluar'" x-transition.opacity @click="activeForm = ''" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm pointer-events-auto"></div>
        <div x-show="activeForm === 'keluar'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="relative w-full h-[95vh] bg-white rounded-t-[2.5rem] shadow-2xl pointer-events-auto flex flex-col">
            <div class="flex items-center gap-4 p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-white rounded-t-[2.5rem] relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-200 opacity-30 rounded-full blur-xl"></div>
                <button @click="backToMenu()" class="relative z-10 w-10 h-10 bg-white shadow-sm border border-red-100 rounded-full text-red-600 flex items-center justify-center hover:bg-red-50"><i class="fas fa-chevron-left text-lg"></i></button>
                <div class="flex-1 relative z-10"><h3 class="text-xl font-extrabold text-gray-900">Pengeluaran</h3><p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Waduh, Jajan Apa Nih?</p></div>
                <div class="relative z-10 w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-500 transform -rotate-12"><i class="fas fa-receipt text-2xl"></i></div>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <div class="bg-gray-900 rounded-3xl p-6 mb-6 relative overflow-hidden shadow-[0_15px_30px_rgba(220,38,38,0.15)]">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-red-500 opacity-20 rounded-full blur-3xl -mr-10 -mt-10"></div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 text-center">Nominal Keluar</label>
                    <div class="flex items-center justify-center gap-2 border-b-2 border-gray-700 pb-2 focus-within:border-red-400 transition-colors">
                        <span class="text-red-400 font-extrabold text-3xl">Rp</span>
                        <input type="text" inputmode="numeric" x-model="uangKeluarStr" @input="formatRupiahKeluar($event)" placeholder="0" class="bg-transparent w-full max-w-[220px] text-center text-5xl font-black text-white focus:outline-none placeholder-gray-600">
                    </div>
                </div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fas fa-bolt text-red-500 mr-1"></i> Nominal Cepat</label>
                <div class="grid grid-cols-3 gap-3 mb-8">
                    <button @click="tambahUangKeluar(10000)" class="py-3 bg-red-50 text-red-600 rounded-xl font-bold text-sm">+ 10rb</button>
                    <button @click="tambahUangKeluar(20000)" class="py-3 bg-red-50 text-red-600 rounded-xl font-bold text-sm">+ 20rb</button>
                    <button @click="tambahUangKeluar(50000)" class="py-3 bg-red-50 text-red-600 rounded-xl font-bold text-sm">+ 50rb</button>
                    <button @click="tambahUangKeluar(100000)" class="py-3 bg-red-50 text-red-600 rounded-xl font-bold text-sm">+ 100rb</button>
                    <button @click="tambahUangKeluar(0, true)" class="py-3 col-span-2 bg-gray-100 text-gray-500 rounded-xl font-bold text-sm"><i class="fas fa-eraser mr-1"></i> Reset Uang</button>
                </div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fas fa-tag text-red-500 mr-1"></i> Buat Apa?</label>
                <div class="flex gap-2 mb-3 overflow-x-auto pb-2">
                    <button @click="keteranganKeluar = 'Makan / Minum'" :class="keteranganKeluar === 'Makan / Minum' ? 'bg-gray-800 text-white' : 'bg-white border-2 border-gray-100 text-gray-500'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap">Makan / Minum</button>
                    <button @click="keteranganKeluar = 'Bensin / Parkir'" :class="keteranganKeluar === 'Bensin / Parkir' ? 'bg-gray-800 text-white' : 'bg-white border-2 border-gray-100 text-gray-500'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap">Bensin / Parkir</button>
                    <button @click="keteranganKeluar = 'Kuota Internet'" :class="keteranganKeluar === 'Kuota Internet' ? 'bg-gray-800 text-white' : 'bg-white border-2 border-gray-100 text-gray-500'" class="px-5 py-2.5 rounded-full font-bold text-sm whitespace-nowrap">Kuota Internet</button>
                </div>
                <input type="text" x-model="keteranganKeluar" placeholder="Ketik rincian pengeluaranmu..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 font-bold text-gray-800 focus:outline-none focus:border-red-500">
            </div>
            <div class="p-6 border-t border-gray-50 bg-white">
                <button class="w-full bg-red-600 text-white font-bold text-lg py-4 rounded-2xl flex justify-center items-center gap-2"><i class="fas fa-minus-circle"></i> Catat Pengeluaran</button>
            </div>
        </div>
    </div>

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
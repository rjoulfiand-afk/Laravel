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
            // 1. LOGIKA MENU BAWAH & PLUS
            showAddMenu: false, 
            activeForm: '',
            
            backToMenu() {
                // Kita kasih tahu sistem: Mana aja form yang asalnya dari tombol Plus (+)
                let dariPlusMenu = ['nabung', 'keluar', 'tugas', 'catatan'].includes(this.activeForm);
                
                this.activeForm = ''; // Tutup form apapun yang lagi aktif
                
                // Kalau formnya dari menu Plus, balikin ke menu Plus (delay dikit biar smooth)
                if (dariPlusMenu) {
                    setTimeout(() => this.showAddMenu = true, 300);
                } 
                // Kalau dari menu Nav bawah (kayak dompet / daftar-tugas), dia bakal otomatis diem di Beranda!
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
        @buka-active-form.window="activeForm = $event.detail"
        @buka-gelembung.window="activeNote = $event.detail; showNoteModal = true"
        class="bg-gray-50 text-gray-800 h-[100dvh] min-h-screen overflow-hidden flex flex-col relative">

        <livewire:beranda />

        <!-- Bottom Nav -->
        <nav class="fixed bottom-0 w-full px-6 py-4 bg-white rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.05)] z-40">
            <ul class="flex justify-between items-center text-gray-400">
                <li><a href="/harian" class="flex flex-col items-center gap-1.5 text-red-600"><i class="fas fa-home text-xl"></i><span class="text-[9px] font-bold tracking-widest uppercase">Beranda</span></a></li>
                <li>
        <button type="button" @click="activeForm = 'dompet'" class="flex flex-col items-center gap-1.5 hover:text-red-500 transition-colors focus:outline-none">
            <i class="fas fa-wallet text-xl"></i>
            <span class="text-[9px] font-bold tracking-widest uppercase">Dompet</span>
        </button>
    </li>
                <li class="-mt-12 relative">
                    <div class="absolute inset-0 bg-red-600 blur-lg opacity-40 rounded-full"></div>
                    <button @click="showAddMenu = true" class="relative flex items-center justify-center w-14 h-14 bg-red-600 rounded-full text-white hover:scale-105 active:scale-95 transition-all shadow-lg">
                        <i class="fas fa-plus text-2xl transition-transform duration-300" :class="{'rotate-45': showAddMenu}"></i>
                    </button>
                </li>
                <li>
        <button type="button" @click="activeForm = 'portal'" class="flex flex-col items-center gap-1.5 hover:text-red-500 transition-colors focus:outline-none">
            <i class="fas fa-rocket text-xl"></i>
            <span class="text-[9px] font-bold tracking-widest uppercase">Portal</span>
        </button>
                </li>
               <li>
                    <button type="button" @click="activeForm = 'asisten'" class="flex flex-col items-center gap-1.5 text-gray-400 hover:text-blue-500 transition-colors focus:outline-none">
                        <i class="fas fa-robot text-xl"></i>
                        <span class="text-[9px] font-bold tracking-widest uppercase">Asisten</span>
                    </button>
                </li>
        </nav>

       <!-- ========================================== -->
        <!-- 🌟 MODAL BACA & EDIT (REALISTIC NOTEPAD) 🌟 -->
        <!-- ========================================== -->
        <div x-cloak x-show="showNoteModal" class="fixed inset-0 z-[70] flex items-center justify-center pointer-events-none p-4 sm:p-6">
            <!-- Backdrop -->
            <div x-show="showNoteModal" x-transition.opacity.duration.400ms class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm pointer-events-auto" @click="if(!isEditing) tiupGelembung()"></div>
            
            <!-- Box Modal (Anti Nyusut) -->
            <div x-show="showNoteModal" 
                x-transition:enter="transition ease-out duration-300 transform" 
                x-transition:enter-start="scale-95 opacity-0 translate-y-4" 
                x-transition:enter-end="scale-100 opacity-100 translate-y-0" 
                class="relative w-full max-w-md bg-white rounded-[2rem] shadow-2xl pointer-events-auto flex flex-col overflow-hidden" 
                style="max-height: 85vh;"
                x-data="{ note: {}, isEditing: false, editTitle: '', editContent: '' }" 
                x-init="$watch('activeNote', val => { 
                    if(val) { note = val; isEditing = false; editTitle = val.title; editContent = val.text; } 
                })">
                
                <!-- Header Modal Putih Bersih -->
                <div class="px-6 py-5 flex items-center justify-between border-b border-slate-100 bg-white z-20 shrink-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-md transition-colors" :class="isEditing ? 'bg-blue-500' : note.color">
                            <i class="fas text-xl" :class="isEditing ? 'fa-pen-nib' : note.icon"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 text-base" x-text="isEditing ? 'Mode Edit' : 'Review Catatan'"></h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5" x-text="isEditing ? 'Perbarui Ide Brilianmu' : note.date"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button x-show="!isEditing" @click="isEditing = true" class="w-9 h-9 rounded-full bg-slate-50 text-blue-500 hover:text-white hover:bg-blue-500 transition-all flex items-center justify-center border border-slate-200 shadow-sm active:scale-95">
                            <i class="fas fa-pen text-[11px]"></i>
                        </button>
                        <button @click="if(isEditing){ isEditing=false; } else { tiupGelembung(); }" class="w-9 h-9 rounded-full bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all flex items-center justify-center border border-slate-200 shadow-sm active:scale-95">
                            <i class="fas fa-times text-[13px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Area Konten (Background Abu Halus) -->
                <div class="flex-1 overflow-y-auto bg-slate-50/50 p-6 premium-scroll relative">
                    
                    <!-- 📖 MODE BACA -->
                    <div x-show="!isEditing" x-transition.opacity class="space-y-4">
                        <h2 class="text-2xl font-black text-slate-800 px-1" x-text="note.title"></h2>
                        
                        <!-- Kertas Garis Realistis -->
                        <div class="relative w-full rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden min-h-[250px]">
                            <!-- Garis Margin Merah -->
                            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-rose-300 z-10 opacity-70"></div>
                            <div class="absolute left-[36px] top-0 bottom-0 w-[1px] bg-rose-300 z-10 opacity-40"></div>
                            
                            <!-- Teks Bacaan -->
                            <div class="pl-14 pr-5 py-2 text-slate-700 font-medium text-[14px] whitespace-pre-wrap break-words min-h-[250px]"
                                 style="line-height: 32px; background-image: repeating-linear-gradient(transparent, transparent 31px, #bae6fd 31px, #bae6fd 32px); background-attachment: local;"
                                 x-text="note.text">
                            </div>
                        </div>
                    </div>

                    <!-- ✍️ MODE EDIT -->
                    <div x-show="isEditing" x-cloak class="space-y-5">
                        <!-- Input Judul -->
                        <div>
                            <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1.5 ml-1">Judul Baru</label>
                            <input type="text" x-model="editTitle" class="w-full bg-white border border-slate-200 rounded-xl py-3.5 px-4 font-black text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all text-sm">
                        </div>
                        
                        <!-- Textarea Kertas Edit -->
                        <div>
                            <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1.5 ml-1">Isi Catatan</label>
                            <div class="relative w-full rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                                <!-- Garis Margin Merah -->
                                <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-rose-300 z-10 opacity-70"></div>
                                <div class="absolute left-[36px] top-0 bottom-0 w-[1px] bg-rose-300 z-10 opacity-40"></div>
                                
                                <textarea x-model="editContent" rows="7" class="w-full bg-transparent pl-14 pr-5 py-2 text-slate-700 font-medium text-[14px] resize-none focus:outline-none premium-scroll relative z-20"
                                          style="line-height: 32px; background-image: repeating-linear-gradient(transparent, transparent 31px, #bae6fd 31px, #bae6fd 32px); background-attachment: local;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Simpan (Hanya muncul saat edit) -->
                <div x-show="isEditing" x-cloak class="p-5 bg-white border-t border-slate-100 z-20 shrink-0">
                    <button @click="
                            let now = new Date();
                            let jam = now.getHours().toString().padStart(2, '0');
                            let menit = now.getMinutes().toString().padStart(2, '0');
                            let hari = now.getDate().toString().padStart(2, '0');
                            let bln = now.toLocaleString('id-ID', { month: 'short' }).toUpperCase();
                            let thn = now.getFullYear();
                            let waktuAsli = `${hari} ${bln} ${thn}, ${jam}:${menit}`;

                            Livewire.dispatch('updateCatatanEvent', [{ id: note.id, title: editTitle, content: editContent }]);
                            note.title = editTitle;
                            note.text = editContent;
                            note.date = waktuAsli;
                            isEditing = false;"
                            class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[11px] font-black uppercase tracking-widest transition-all shadow-[0_8px_20px_rgba(37,99,235,0.25)] active:scale-95 flex items-center justify-center gap-2">
                        <i class="fas fa-save text-sm"></i> Simpan Perubahan
                    </button>
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
        <!-- JEROAN FORM BAWAAN MENU PLUS (+) -->
        <!-- ========================================== -->
        <livewire:form-nabung />
        <livewire:form-keluar />
        <livewire:form-tugas />
        <livewire:form-catatan />

        <!-- ========================================== -->
        <!-- 📋 JEROAN: DAFTAR TUGAS (MARKAS MISI) --> <!-- INI YANG KEMAREN KECABUT WKWK -->
        <!-- ========================================== -->
        <livewire:daftar-tugas />

        <!-- ========================================== -->
        <!-- 💳 JEROAN: DOMPET DIGITAL PRIBADI -->
        <!-- ========================================== -->
        <livewire:dompet-pribadi />

        <!-- ========================================== -->
        <!-- 🚀 JEROAN: PORTAL (SHORTCUT & LEMARI LINK) -->
        <!-- ========================================== -->
        <livewire:portal-hub />

        <!-- ========================================== -->
        <!-- 🎭 JEROAN: PROFIL 3D (JULL & MICKHAYLA) -->
        <!-- ========================================== -->
        <livewire:profil-user />

        <!-- ========================================== -->
        <!-- ⚙️ JEROAN: LOBI MENU PROFIL UTAMA -->
        <!-- ========================================== -->
        <livewire:menu-profil />

        <livewire:histori-log />

        <livewire:asisten-ai />

        @livewireScripts
    </body>
    </html>
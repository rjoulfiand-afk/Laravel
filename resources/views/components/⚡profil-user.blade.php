<?php

use Livewire\Component;

new class extends Component
{
    // Murni UI Component
};
?>

<!-- 🎨 SUNTIKAN CSS BAWAAN BOSS JULL (RESTORED 100% PERFECT) -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Poppins:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap');

    .profil-wrapper {
        /* Warna Mickhayla (Pink) */
        --pink-main: #FF7EB3;
        --pink-dark: #D6336C;
        --pink-glow: rgba(255, 126, 179, 0.5);
        
        /* Warna Julljhha (Merah) */
        --dev-main: #e74c3c;
        --dev-dark: #c0392b;
        --dev-glow: rgba(231, 76, 60, 0.5);
        
        --glass-white: rgba(255, 255, 255, 0.95);
        --shadow-cute: 0 15px 35px rgba(255, 126, 179, 0.25);
        --shadow-dev: 0 15px 35px rgba(231, 76, 60, 0.25);
        --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        
        font-family: 'Poppins', sans-serif;
        color: #2C3E50;
    }

    /* Background Partikel Asli */
    .bg-particles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; overflow: hidden; }
    .particle { position: absolute; border-radius: 50%; animation: floatUp linear infinite; transition: background 1s ease; }
    
    .theme-dev .particle { background: rgba(231, 76, 60, 0.3); }
    .theme-queen .particle { background: rgba(255, 126, 179, 0.3); }
    
    @keyframes floatUp { 
        0% { transform: translateY(100vh) scale(0); opacity: 0; } 
        50% { opacity: 1; } 
        100% { transform: translateY(-10vh) scale(1); opacity: 0; } 
    }

    /* Arsitektur 3D Flip Asli Lu */
    .profile-container { width: 100%; max-width: 440px; perspective: 1500px; position: relative; z-index: 1; margin: 0 auto; }
    .card-3d-wrapper { width: 100%; display: grid; transform-style: preserve-3d; transition: transform 1s cubic-bezier(0.68, -0.55, 0.265, 1.55); border-radius: 32px; }
    
    .card-3d-wrapper.levitate { transform: scale(1.03) translateY(-15px); }
    .card-3d-wrapper.flipped { transform: rotateY(180deg); }
    .card-3d-wrapper.levitate.flipped { transform: rotateY(180deg) scale(1.03) translateY(-15px); }

    /* Sisi Kartu Putih Elegan */
    .card-face {
        grid-area: 1 / 1; backface-visibility: hidden;
        background-color: var(--glass-white);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 32px; padding: 35px 20px 25px;
        position: relative; overflow: hidden; pointer-events: none; 
    }

    .card-face.front { pointer-events: auto; box-shadow: var(--shadow-dev); border: 1px solid rgba(231, 76, 60, 0.2); }
    .card-3d-wrapper.flipped .card-face.back { pointer-events: auto; }
    .card-3d-wrapper.flipped .card-face.front { pointer-events: none; }
    .card-face.back { transform: rotateY(180deg); box-shadow: var(--shadow-cute); border: 1px solid rgba(255, 126, 179, 0.2); }

    /* Tombol Sudut */
    .btn-top-left, .btn-top-right {
        position: absolute; top: 20px; width: 38px; height: 38px; border-radius: 50%; border: none;
        display: flex; justify-content: center; align-items: center;
        font-size: 16px; cursor: pointer; transition: var(--transition); z-index: 10;
    }
    .btn-top-left { left: 20px; background: rgba(0,0,0,0.05); color: #2C3E50; }
    .btn-top-left:hover { background: #ff4757; color: #fff; transform: rotate(90deg); }
    .btn-top-right { right: 20px; background: rgba(255,255,255,0.9); box-shadow: 0 4px 10px rgba(0,0,0,0.08); color: #2C3E50; }
    .btn-top-right:hover { transform: scale(1.1); }
    
    /* Teks Profil (Playfair Display) */
    .profile-info { text-align: center; margin-bottom: 20px; position: relative; z-index: 2; }
    .web-title { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; margin-bottom: 2px; display: flex; align-items: center; justify-content: center; gap: 6px; color: #2C3E50; }
    .verified-badge { font-size: 18px; color: #2C3E50; } /* Centang Hitam persis di screenshot */
    
    .web-subtitle { font-size: 12px; font-weight: 500; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: center; gap: 5px; color: #7f8c8d; }

    /* Tags Presisi */
    .credential-tags { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; margin-top: 12px; }
    .tag-badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 5px; letter-spacing: 0.5px; color: #2C3E50; }
    .tag-pink { background: rgba(255, 126, 179, 0.05); border: 1px solid rgba(255, 126, 179, 0.4); }
    .tag-dev { background: rgba(231, 76, 60, 0.05); border: 1px solid rgba(231, 76, 60, 0.4); }

    /* Gallery Premium */
    /* Gallery Premium (LOGIKA UKURAN DINAMIS ANTI POTONG) */
    .gallery-wrapper { position: relative; width: 100%; margin-bottom: 25px; z-index: 2; }
    .gallery-scroller { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; gap: 12px; padding-bottom: 15px; scrollbar-width: none; -ms-overflow-style: none; -webkit-overflow-scrolling: touch; cursor: grab; }
    .gallery-scroller.active { cursor: grabbing; scroll-snap-type: none; }
    .gallery-scroller::-webkit-scrollbar { display: none; }
    
    .gallery-card { 
        flex: 0 0 auto; /* Auto melar/nyusut ngikutin lebar foto */
        height: 320px; /* Tingginya dikunci sama rata biar rapi banget! */
        max-width: 85vw; /* Maksimal lebar biar foto landscape ngga kelebaran di HP */
        scroll-snap-align: center; 
        border-radius: 20px; position: relative; 
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1); 
        border: 1px solid rgba(255,255,255,0.7); overflow: hidden; 
        background: rgba(0,0,0,0.02); transform: translateZ(0); 
    }
    
    .gallery-card::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 35%; background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); z-index: 1; }
    
    .gallery-img { 
        width: auto; /* Lebar menyesuaikan rasio asli foto */
        height: 100%; /* Tinggi dipaksa full memenuhi tinggi kotak 320px */
        object-fit: contain; /* SIHIRNYA DI SINI: Foto utuh tanpa kepotong! */
        position: relative; pointer-events: none; display: block;
    }
    
    .gallery-counter { position: absolute; bottom: 12px; right: 15px; color: #fff; font-size: 12px; font-weight: 600; letter-spacing: 1px; z-index: 2; text-shadow: 0 2px 4px rgba(0,0,0,0.5); display: flex; align-items: center; gap: 6px; }
    .gallery-logo-pin { position: absolute; top: 12px; right: 15px; color: rgba(255,255,255,0.9); font-size: 20px; z-index: 2; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); }
    /* Bio Box */
    .bio-box { padding: 18px; border-radius: 18px; font-size: 12.5px; margin-bottom: 20px; line-height: 1.6; text-align: center; }
    .bio-pink { background: rgba(255, 182, 193, 0.15); border: 1px solid rgba(255, 126, 179, 0.2); color: #555; }
    .bio-dev { background: rgba(231, 76, 60, 0.05); border: 1px solid rgba(231, 76, 60, 0.3); color: #2C3E50; font-family: 'JetBrains Mono', monospace; text-align: left; }
    .cursor-blink { display: inline-block; width: 8px; height: 15px; background-color: var(--dev-main); margin-left: 2px; animation: blink 1s step-end infinite; vertical-align: middle; }

    /* Tombol Bawah */
    .btn-stack { display: flex; flex-direction: column; gap: 10px; z-index: 2; position: relative; }
    .btn-pro { width: 100%; padding: 14px; border: none; border-radius: 14px; font-size: 14px; font-weight: 600; cursor: pointer; transition: var(--transition); display: flex; justify-content: center; align-items: center; gap: 8px; font-family: 'Poppins', sans-serif; }
    .btn-pink-gradient { background: linear-gradient(135deg, #FF7EB3, #D6336C); color: white; box-shadow: 0 6px 15px rgba(214, 51, 108, 0.25); }
    .btn-pink-gradient:hover { transform: translateY(-2px); filter: brightness(1.05); }
    .btn-dev-gradient { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; box-shadow: 0 6px 15px rgba(231, 76, 60, 0.25); }
    .btn-dev-gradient:hover { transform: translateY(-2px); filter: brightness(1.05); }
    
    .swipe-hint { display: flex; align-items: center; justify-content: center; gap: 8px; color: #95a5a6; font-size: 11px; font-weight: 500; margin-bottom: 12px; animation: swipeHand 2s infinite; z-index: 2; position: relative; }

    /* Animasi Keluar/Masuk */
    .exiting { animation: cinematicExit 0.5s cubic-bezier(0.5, 0, 0.2, 1) forwards; }
    @keyframes cinematicExit {
        0% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        100% { opacity: 0; transform: translateY(-30px) scale(0.92); filter: blur(5px); }
    }
</style>

<!-- WRAPPER UTAMA ALPINE -->
<div x-cloak x-show="activeForm === 'profil'" class="fixed inset-0 z-[100] flex items-center justify-center pointer-events-none profil-wrapper"
     x-data="{ 
         flipped: false, levitate: false, exiting: false,
         isDown: false, startX: 0, scrollLeft: 0,
         
         rawBioText: 'root@juldev:~$ ./load_bio.sh\n\n[OK] Auth Success.\nGw Jull. Developer utama Harian Rixsan. Suka ngoding, UI/UX, dan naik gunung nyari inspirasi. Enjoy the system!',
         displayedBio: '', typeIndex: 0, isTyping: false, typingInterval: null,
         
         startHackerTyping() {
             this.displayedBio = ''; this.typeIndex = 0; this.isTyping = true;
             clearInterval(this.typingInterval);
             this.typingInterval = setInterval(() => {
                 if (this.typeIndex < this.rawBioText.length) {
                     let char = this.rawBioText.charAt(this.typeIndex);
                     this.displayedBio += (char === '\n') ? '<br>' : char;
                     this.typeIndex++;
                 } else {
                     this.isTyping = false; clearInterval(this.typingInterval);
                 }
             }, 30);
         },
         
         flipToQueen() {
             this.levitate = true;
             setTimeout(() => { this.flipped = true; }, 300);
             setTimeout(() => { this.levitate = false; }, 1000);
         },
         flipToDev() {
             this.levitate = true;
             setTimeout(() => { this.flipped = false; setTimeout(() => this.startHackerTyping(), 300); }, 300);
             setTimeout(() => { this.levitate = false; }, 1000);
         },

         startDrag(e, el) { this.isDown = true; el.classList.add('active'); this.startX = (e.pageX || e.touches[0].pageX) - el.offsetLeft; this.scrollLeft = el.scrollLeft; },
         stopDrag(el) { this.isDown = false; el.classList.remove('active'); },
         doDrag(e, el) { 
             if(!this.isDown) return; 
             e.preventDefault(); 
             const x = (e.pageX || e.touches[0].pageX) - el.offsetLeft; 
             const walk = (x - this.startX) * 1.5; 
             el.scrollLeft = this.scrollLeft - walk; 
         }
     }"
     x-init="$watch('activeForm', val => { if(val === 'profil') { exiting = false; flipped = false; setTimeout(() => startHackerTyping(), 600); } })">

    <!-- 🌌 BACKGROUND GRADIENT & PARTIKEL PUTIH BERSIH -->
    <div class="absolute inset-0 transition-colors duration-1000 pointer-events-auto" :class="flipped ? 'theme-queen' : 'theme-dev'">
        <div class="absolute inset-0 transition-colors duration-1000" :style="flipped ? 'background: linear-gradient(135deg, #ffffff 0%, #ffe4e1 100%);' : 'background: linear-gradient(135deg, #ffffff 0%, #fdecec 100%);'"></div>
        <div class="bg-particles">
            @for($i = 0; $i < 15; $i++)
                <div class="particle" style="width: {{ rand(10, 20) }}px; height: {{ rand(10, 20) }}px; left: {{ rand(0, 100) }}vw; animation-duration: {{ rand(8, 15) }}s; animation-delay: {{ rand(0, 5) }}s;"></div>
            @endfor
        </div>
    </div>

    <!-- 🎴 WADAH KARTU 3D UTAMA -->
    <div class="profile-container pointer-events-auto" :class="{'exiting': exiting}" x-show="!exiting"
         x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-16 scale-90 blur-md" x-transition:enter-end="opacity-100 translate-y-0 scale-100 blur-none">
         
        <div class="card-3d-wrapper" :class="{'flipped': flipped, 'levitate': levitate}">
            
            <!-- ============================================== -->
            <!-- 💻 SISI DEPAN: JULLJHHA (TEMA MERAH/PUTIH) -->
            <!-- ============================================== -->
            <div class="card-face front">
                
                <button class="btn-top-left" @click="exiting = true; setTimeout(() => activeForm = '', 400)" title="Kembali"><i class="fas fa-times"></i></button>
                <button class="btn-top-right" @click="flipToQueen()" title="Lihat Queen"><i class="fas fa-crown"></i></button>

                <div class="profile-info">
                    <h1 class="web-title">
                        jull 
                        <i class="fas fa-check-circle verified-badge" title="Verified Dev"></i>
                    </h1>
                    <p class="web-subtitle">
                        <i class="fas fa-code"></i> Official Web Developer
                    </p>
                    
                    <!-- Tags Baru Jull (+ Gunung) -->
                    <div class="credential-tags">
                        <div class="tag-badge tag-dev"><i class="fas fa-shield-alt"></i> Verified Developer</div>
                        <div class="tag-badge tag-dev"><i class="fas fa-laptop-code"></i> UI/UX Designer</div>
                        <div class="tag-badge tag-dev"><i class="fas fa-database"></i> Backend System</div>
                        <div class="tag-badge tag-dev"><i class="fas fa-mountain"></i> Mountain Explorer</div>
                    </div>
                </div>

                <div class="swipe-hint"><i class="fas fa-hand-pointer"></i> Geser foto atau Tap Mahkota</div>
                
                <div class="gallery-wrapper">
                    <div x-ref="scrollerJull" class="gallery-scroller"
                         @mousedown="startDrag($event, $refs.scrollerJull)" @touchstart="startDrag($event, $refs.scrollerJull)"
                         @mouseleave="stopDrag($refs.scrollerJull)" @touchend="stopDrag($refs.scrollerJull)"
                         @mouseup="stopDrag($refs.scrollerJull)"
                         @mousemove="doDrag($event, $refs.scrollerJull)" @touchmove="doDrag($event, $refs.scrollerJull)">
                        
                        @for($i=1; $i<=5; $i++)
                        <div class="gallery-card">
                            <i class="fas fa-code gallery-logo-pin" style="color:rgba(255,255,255,0.7);"></i>
                            
                            <!-- SIHIR PEMANGGIL FOTO JULL DARI FOLDER PUBLIC -->
                            <img src="{{ asset('images/profil/jul' . $i . '.jpg') }}" class="gallery-img" alt="Jull {{$i}}">
                            
                            <div class="gallery-counter"><i class="fas fa-laptop-code" style="color:var(--dev-main);"></i> {{$i}} / 5</div>
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="profile-info" style="margin-bottom:0;">
                    <div class="bio-box bio-dev">
                        <span style="color:var(--dev-main);">root@juldev:~$</span> ./load_bio.sh<br><br>
                        <span x-html="displayedBio"></span><span class="cursor-blink"></span>
                    </div>

                    <div class="btn-stack">
                        <button type="button" class="btn-pro btn-dev-gradient" onclick="window.open('https://www.instagram.com/julljhaa?stkn=MWl1c2c0amhvYTZvZg==', '_blank')">
                            <i class="fab fa-instagram" style="font-size:20px;"></i> Follow @julljhaa
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- 🌸 SISI BELAKANG: MICKHAYLA (TEMA PINK/PUTIH) -->
            <!-- ============================================== -->
            <div class="card-face back">
                
                <button class="btn-top-left" @click="exiting = true; setTimeout(() => activeForm = '', 400)" title="Kembali"><i class="fas fa-times"></i></button>
                <button class="btn-top-right" @click="flipToDev()" title="Kembali ke Dev"><i class="fas fa-fingerprint"></i></button>

                <div class="profile-info">
                    <h1 class="web-title">
                        Mickhayla 
                        <i class="fas fa-check-circle verified-badge" title="Queen"></i>
                    </h1>
                    <p class="web-subtitle">
                        <i class="fas fa-heart"></i> Girlfriend Supportive
                    </p>
                    
                    <!-- Tags Mickhayla (Fix Susunan 2x2 + English Expert) -->
                    <div class="credential-tags">
                        <div class="tag-badge tag-pink"><i class="fas fa-heart" style="color:var(--pink-main);"></i>Girlfriend</div>
                        <div class="tag-badge tag-pink"><i class="fas fa-camera-retro"></i> Fashion Model</div>
                        <div class="tag-badge tag-pink"><i class="fas fa-language"></i> English Expert</div>
                        <div class="tag-badge tag-pink"><i class="fas fa-heartbeat"></i> Support System</div>
                    </div>

                <div class="swipe-hint"><i class="fas fa-hand-pointer"></i> Geser foto atau Tap Sidik Jari</div>
                
                <div class="gallery-wrapper">
                    <div x-ref="scrollerMic" class="gallery-scroller"
                         @mousedown="startDrag($event, $refs.scrollerMic)" @touchstart="startDrag($event, $refs.scrollerMic)"
                         @mouseleave="stopDrag($refs.scrollerMic)" @touchend="stopDrag($refs.scrollerMic)"
                         @mouseup="stopDrag($refs.scrollerMic)"
                         @mousemove="doDrag($event, $refs.scrollerMic)" @touchmove="doDrag($event, $refs.scrollerMic)">
                        
                        @for($i=1; $i<=5; $i++)
                        <div class="gallery-card">
                            <i class="fas fa-heart gallery-logo-pin" style="color:rgba(255,255,255,0.7);"></i>
                            
                            <!-- BACA FILE: mica1.jpg, mica2.jpg, dst... -->
                            <img src="{{ asset('images/profil/mica' . $i . '.jpg') }}" class="gallery-img" alt="Mickhayla {{$i}}">
                            
                            <div class="gallery-counter"><i class="fas fa-star" style="color:var(--pink-main);"></i> {{$i}} / 5</div>
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="profile-info" style="margin-bottom:0;">
                    <div class="bio-box bio-pink">
                        "Hai semuanya! Kenalin aku Mickhayla. Aku support system nomor satu buat Jull. Kalau web ini keren dan tanpa bug, itu berkat doa dan kopi yang aku bikinin! ✨💖 <i>Enjoy the vibes!</i>"
                    </div>

                    <div class="btn-stack">
                        <button type="button" class="btn-pro btn-pink-gradient" onclick="window.open('https://www.instagram.com/k_m_a27?stkn=MXMxaGdudW11d3lkeg==', '_blank')">
                            <i class="fab fa-instagram" style="font-size:20px;"></i> Follow @k_m_a27
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
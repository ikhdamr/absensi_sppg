<!-- WRAPPER UTAMA NAVBAR -->
<div class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all">

    <header class="flex justify-between items-center py-3.5 px-4 lg:px-8 relative z-50 bg-white/95">

        <!-- BAGIAN KIRI: Hamburger Menu (Mobile) & Logo -->
        <div class="flex items-center gap-3 md:gap-3.5">
            <!-- Tombol Hamburger -->
            <button onclick="toggleMobileMenu()" type="button" class="md:hidden p-2 text-gray-500 hover:text-[#1868D5] hover:bg-blue-50 rounded-lg transition-colors focus:outline-none relative z-50">
                <svg id="hamburgerIcon" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Logo -->
            <div class="flex items-center gap-3.5 group cursor-pointer" onclick="window.location.href='/pegawai/dashboard'">
                <div class="relative w-10 h-10 md:w-11 md:h-11 bg-blue-50 rounded-xl p-1.5 flex items-center justify-center overflow-hidden border border-blue-100 group-hover:shadow-md transition-shadow">
                    <img src="{{ asset('assets/logos/logo-sppg.png') }}" alt="Logo" class="w-full h-full object-contain relative z-10 group-hover:scale-110 transition-transform duration-300">
                </div>
                <div class="text-[#0D3B66]">
                    <h2 class="font-black text-[14px] md:text-[15px] tracking-widest leading-none">SPPG <span class="text-[#1868D5]">LANGENSARI</span></h2>
                    <p class="font-bold text-[9px] md:text-[10px] text-gray-500 tracking-wider mt-1 uppercase hidden sm:block">Panel Pegawai</p>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: Profil User -->
        <div class="flex items-center gap-3 md:gap-6">

            <!-- Profil User (Bisa di Klik) -->
            <div class="relative flex items-center cursor-pointer h-full py-1">
                <div class="flex items-center gap-3 group" onclick="toggleUser(event)">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-extrabold text-gray-800 leading-none group-hover:text-[#1868D5] transition-colors">{{ Auth::user()->name ?? 'Pegawai' }}</p>
                        <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase tracking-wider">{{ Auth::user()->role ?? 'Pegawai' }}</p>
                    </div>
                    @if(Auth::user()->photo)
    <img src="{{ asset('uploads/pegawai/' . Auth::user()->photo) }}" alt="Profile" class="w-9 h-9 md:w-10 md:h-10 rounded-xl border-2 border-white shadow-sm group-hover:border-blue-100 transition-colors object-cover">
@else
    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Pegawai') }}&background=1868D5&color=fff&bold=true" alt="Profile" class="w-9 h-9 md:w-10 md:h-10 rounded-xl border-2 border-white shadow-sm group-hover:border-blue-100 transition-colors object-cover">
@endif
                    <svg class="w-4 h-4 text-gray-400 hidden md:block group-hover:text-[#1868D5] transition-transform group-hover:translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                <!-- Dropdown Profil -->
                <div id="profileDropdown" class="absolute right-0 top-full mt-2 w-48 md:w-52 bg-white border border-gray-100 rounded-2xl opacity-0 invisible transition-all duration-300 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] flex flex-col text-[14px] overflow-hidden transform origin-top scale-95 z-50">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 md:hidden">
                        <p class="text-sm font-extrabold text-gray-800">{{ Auth::user()->name ?? 'Pegawai' }}</p>
                        <p class="text-[11px] font-bold text-gray-400 uppercase mt-0.5">{{ Auth::user()->role ?? 'Pegawai' }}</p>
                    </div>
                    <a href="javascript:void(0)" onclick="toggleProfileModal()" class="flex items-center gap-3 px-5 py-3.5 font-semibold text-gray-600 hover:text-[#1868D5] hover:bg-blue-50/50 transition-colors border-b border-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil Saya
                    </a>
                    <a href="javascript:void(0)" onclick="togglePasswordModal()" class="flex items-center gap-3 px-5 py-3.5 font-semibold text-gray-600 hover:text-amber-600 hover:bg-amber-50/50 transition-colors border-b border-gray-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        Ubah Password
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="m-0">@csrf</form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-5 py-3.5 font-bold text-red-600 hover:bg-red-50 transition-colors bg-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar Aplikasi
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ========================================== -->
    <!-- 1. MENU NAVIGASI KHUSUS DESKTOP (PEGAWAI) -->
    <!-- ========================================== -->
    <nav class="hidden md:block border-t border-gray-100 bg-white/50 relative z-40 px-4 md:px-0">
        <ul class="flex flex-wrap justify-center items-center gap-x-6 gap-y-1 md:gap-x-8 text-[14px] font-bold text-gray-500">
            <!-- Home / Dashboard -->
            <li>
                <a href="/pegawai/dashboard" class="flex items-center gap-2 py-4 px-2 border-b-2 transition-all duration-300 whitespace-nowrap {{ request()->is('pegawai/dashboard') ? 'text-[#1868D5] border-[#1868D5]' : 'border-transparent hover:text-[#1868D5] hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Beranda
                </a>
            </li>

            <!-- Riwayat Presensi -->
            <li>
                <a href="/pegawai/rekap" class="flex items-center gap-2 py-4 px-2 border-b-2 transition-all duration-300 whitespace-nowrap {{ request()->is('pegawai/rekap*') ? 'text-[#1868D5] border-[#1868D5]' : 'border-transparent hover:text-[#1868D5] hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Riwayat Kehadiran
                </a>
            </li>

            <!-- Pengajuan Ketidakhadiran -->
            <li>
                <a href="/pegawai/ketidakhadiran" class="flex items-center gap-2 py-4 px-2 border-b-2 transition-all duration-300 whitespace-nowrap {{ request()->is('pegawai/ketidakhadiran*') ? 'text-[#1868D5] border-[#1868D5]' : 'border-transparent hover:text-[#1868D5] hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pengajuan Izin / Cuti
                </a>
            </li>
        </ul>
    </nav>

    <!-- ========================================== -->
    <!-- 2. MENU NAVIGASI KHUSUS MOBILE (PEGAWAI) -->
    <!-- ========================================== -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-inner flex-col overflow-y-auto max-h-[70vh] w-full absolute left-0 top-full z-40">
        <div class="p-4 flex flex-col gap-1.5 text-[15px] font-bold text-gray-600">

            <!-- Home -->
            <a href="/pegawai/dashboard" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-colors {{ request()->is('pegawai/dashboard') ? 'bg-blue-50 text-[#1868D5]' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Beranda
            </a>

            <!-- Riwayat Presensi -->
            <a href="/pegawai/rekap" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-colors {{ request()->is('pegawai/rekap*') ? 'bg-blue-50 text-[#1868D5]' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Riwayat Kehadiran
            </a>

            <!-- Ketidakhadiran -->
            <a href="/pegawai/ketidakhadiran" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-colors {{ request()->is('pegawai/ketidakhadiran*') ? 'bg-blue-50 text-[#1868D5]' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pengajuan Izin / Cuti
            </a>
        </div>
    </div>
</div> <!-- /AKHIR WRAPPER NAVBAR -->

<!-- ========================================== -->
<!-- MODAL PROFIL & PASSWORD (PEGAWAI) -->
<!-- ========================================== -->
<div id="modalProfile" class="fixed inset-0 z-60 hidden bg-gray-900/70 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-full">
        <button type="button" onclick="toggleProfileModal()" class="absolute top-4 right-4 z-20 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full p-1.5 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="bg-linear-to-tr from-[#0D3B66] to-[#114B82] pt-10 pb-6 px-6 text-center relative overflow-hidden shrink-0">
            <div class="absolute -left-16 -top-16 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-500/10 rounded-full pointer-events-none blur-xl"></div>
            <div class="relative z-10">
                @if(Auth::user()->photo)
    <img src="{{ asset('uploads/pegawai/' . Auth::user()->photo) }}" alt="Foto ID" class="w-24 h-24 rounded-2xl border-4 border-white/20 shadow-lg object-cover mx-auto bg-white">
@else
    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Pegawai') }}&background=ffffff&color=0D3B66&bold=true&size=128" alt="Foto ID" class="w-24 h-24 rounded-2xl border-4 border-white/20 shadow-lg object-cover mx-auto bg-white">
@endif
                <h2 class="text-xl font-black text-white mt-4 tracking-wide truncate">{{ Auth::user()->name ?? 'Pegawai' }}</h2>
                <p class="text-[11px] font-bold text-blue-300 uppercase tracking-widest mt-1">{{ Auth::user()->jabatan->nama_jabatan ?? 'Pegawai SPPG' }}</p>
            </div>
        </div>
        <div class="overflow-y-auto px-6 py-6 custom-scroll bg-white">
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 mb-6 shadow-sm">
                <div class="flex flex-col space-y-3.5 text-sm">
                    <div class="flex items-center gap-3 border-b border-gray-200/60 pb-3">
                        <div class="bg-amber-100 text-amber-600 p-2 rounded-lg shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div class="overflow-hidden w-full">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Username Login</p>
                            <p class="font-bold text-gray-800 truncate w-full">{{ Auth::user()->username ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 border-b border-gray-200/60 pb-3">
                        <div class="bg-green-100 text-green-600 p-2 rounded-lg shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">No Handphone</p>
                            <p class="font-bold text-gray-800 truncate">{{ Auth::user()->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="bg-purple-100 text-purple-600 p-2 rounded-lg shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alamat Lengkap</p>
                            <p class="font-bold text-gray-800 text-[13px] leading-tight mt-0.5 wrap-break-words">{{ Auth::user()->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 w-full">
                <button type="button" onclick="bukaModalEdit(event)" class="w-full bg-yellow-500 hover:bg-yellow-600 text-yellow-950 font-bold py-3.5 rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                    Edit Profil
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL UBAH PASSWORD DENGAN IKON MATA -->
<!-- ========================================== -->
<div id="modalPassword" class="fixed inset-0 z-60 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
        <div class="bg-amber-500 p-5 text-center text-white relative shrink-0">
            <h2 class="text-lg font-bold text-amber-950">Ubah Password Login</h2>
            <button type="button" onclick="togglePasswordModal()" class="absolute top-4 right-4 text-amber-900/70 hover:text-amber-950 bg-black/10 hover:bg-black/20 rounded-full p-1.5 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 md:p-8">
            <form action="/pegawai/ubah-password" method="POST">
                @csrf
                @method('PUT')

                <!-- Password Lama -->
                <div class="mb-5 relative">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Password Lama</label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required placeholder="Masukkan password saat ini" class="w-full border border-gray-300 p-3 pr-12 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none transition shadow-sm">
                        <button type="button" onclick="togglePass('current_password', 'eye1_open', 'eye1_closed')" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <svg id="eye1_open" class="w-5 h-5 text-gray-400 hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eye1_closed" class="w-5 h-5 hidden text-gray-400 hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Password Baru -->
                <div class="mb-5 relative">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" required placeholder="Minimal 6 karakter" class="w-full border border-gray-300 p-3 pr-12 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none transition shadow-sm">
                        <button type="button" onclick="togglePass('new_password', 'eye2_open', 'eye2_closed')" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <svg id="eye2_open" class="w-5 h-5 text-gray-400 hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eye2_closed" class="w-5 h-5 hidden text-gray-400 hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-8 relative">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required placeholder="Ketik ulang password baru" class="w-full border border-gray-300 p-3 pr-12 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none transition shadow-sm">
                        <button type="button" onclick="togglePass('new_password_confirmation', 'eye3_open', 'eye3_closed')" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <svg id="eye3_open" class="w-5 h-5 text-gray-400 hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eye3_closed" class="w-5 h-5 hidden text-gray-400 hover:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button type="button" onclick="togglePasswordModal()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-amber-950 font-bold py-3 rounded-xl transition-colors shadow-md">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL EDIT PROFIL PEGAWAI -->
<!-- ============================================== -->
<div id="modalEditProfile" class="fixed inset-0 z-60 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
    <div class="modal-box bg-white rounded-2xl w-full max-w-2xl shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-full">
        <!-- Header Modal -->
        <div class="bg-yellow-500 p-5 text-center text-white relative shrink-0">
            <h2 class="text-lg font-bold text-yellow-950">Edit Profil Saya</h2>
            <button type="button" onclick="toggleModal('modalEditProfile')" class="absolute top-4 right-4 text-yellow-900/70 hover:text-yellow-950 bg-black/10 hover:bg-black/20 rounded-full p-1.5 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="overflow-y-auto p-6 md:p-8 custom-scroll">
            <form action="{{ url('/pegawai/profil') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                    </div>

                    <!-- Username dibuat memanjang (col-span-2) agar layout tetap rapi dan tidak bolong -->
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Username Login</label>
                        <input type="text" name="username" value="{{ auth()->user()->username }}" required class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">No Handphone</label>
                        <input type="text" name="phone" value="{{ auth()->user()->phone }}" required class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Alamat Lengkap</label>
                        <textarea name="alamat" required rows="2" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">{{ auth()->user()->alamat }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Ubah Foto Profil (Opsional)</label>
                        <input type="file" name="photo" accept="image/*" class="w-full border border-gray-300 p-2 rounded-lg text-sm bg-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 transition cursor-pointer">
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
                    <button type="button" onclick="toggleModal('modalEditProfile')" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2.5 px-6 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-yellow-950 font-bold py-2.5 px-6 rounded-xl transition-colors shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // SCRIPT KLIK DROPDOWN PROFIL USER
    function toggleUser(event) {
        event.stopPropagation();
        const profil = document.getElementById('profileDropdown');

        if (profil.classList.contains('invisible')) {
            profil.classList.remove('opacity-0', 'invisible', 'scale-95');
            profil.classList.add('opacity-100', 'visible', 'scale-100');
        } else {
            profil.classList.remove('opacity-100', 'visible', 'scale-100');
            profil.classList.add('opacity-0', 'invisible', 'scale-95');
        }
    }

    // MENUTUP DROPDOWN JIKA KLIK DI LUAR AREA
    document.addEventListener('click', function(event) {
        const profil = document.getElementById('profileDropdown');

        if (profil && !event.target.closest('#profileDropdown') && !event.target.closest('[onclick="toggleUser(event)"]')) {
            profil.classList.remove('opacity-100', 'visible', 'scale-100');
            profil.classList.add('opacity-0', 'invisible', 'scale-95');
        }
    });

    // SCRIPT MENU HAMBURGER UTAMA
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const hamburger = document.getElementById('hamburgerIcon');
        const close = document.getElementById('closeIcon');

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            menu.classList.add('flex');
            hamburger.classList.add('hidden');
            close.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
            menu.classList.remove('flex');
            hamburger.classList.remove('hidden');
            close.classList.add('hidden');
        }
    }

    // SCRIPT MENU SUB-DROPDOWN MOBILE
    function toggleMobileDropdown(menuId, chevronId) {
        const menu = document.getElementById(menuId);
        const chevron = document.getElementById(chevronId);

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            menu.classList.add('flex');
            chevron.classList.add('rotate-180');
        } else {
            menu.classList.add('hidden');
            menu.classList.remove('flex');
            chevron.classList.remove('rotate-180');
        }
    }

    // FUNGSI UMUM MODAL
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        const modalBox = modal.querySelector('.modal-box') || modal.firstElementChild;

        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                if (modalBox) modalBox.classList.remove('scale-95');
            }, 10);
        } else {
            modal.classList.add('opacity-0');
            if (modalBox) modalBox.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    }

    function togglePasswordModal() { toggleModal('modalPassword'); }
    function toggleProfileModal() { toggleModal('modalProfile'); }

    // FUNGSI PERPINDAHAN MODAL (Profil -> Edit Profil)
    function bukaModalEdit(event) {
        event.preventDefault();
        toggleProfileModal();
        setTimeout(() => {
            toggleModal('modalEditProfile');
        }, 350);
    }

    // FUNGSI TOGGLE LIHAT PASSWORD
    function togglePass(inputId, openId, closedId) {
        const input = document.getElementById(inputId);
        const eyeOpen = document.getElementById(openId);
        const eyeClosed = document.getElementById(closedId);

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
</script>

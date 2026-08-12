@php
    // Mengambil data ketidakhadiran yang statusnya masih 'Menunggu'
    $pendingCount = \App\Models\Ketidakhadiran::where('status', 'Menunggu')->count();
    $pendingNotif = \App\Models\Ketidakhadiran::with('user')
                        ->where('status', 'Menunggu')
                        ->latest()
                        ->take(5) // Ambil 5 notifikasi terbaru saja
                        ->get();
@endphp

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
            <div class="flex items-center gap-3.5 group cursor-pointer" onclick="window.location.href='/admin/dashboard'">
                <div class="relative w-10 h-10 md:w-11 md:h-11 bg-blue-50 rounded-xl p-1.5 flex items-center justify-center overflow-hidden border border-blue-100 group-hover:shadow-md transition-shadow">
                    <img src="{{ asset('assets/logos/logo-sppg.png') }}" alt="Logo" class="w-full h-full object-contain relative z-10 group-hover:scale-110 transition-transform duration-300">
                </div>
                <div class="text-[#0D3B66]">
                    <h2 class="font-black text-[14px] md:text-[15px] tracking-widest leading-none">SPPG <span class="text-[#1868D5]">LANGENSARI</span></h2>
                    <p class="font-bold text-[9px] md:text-[10px] text-gray-500 tracking-wider mt-1 uppercase hidden sm:block">Tarogong Kaler - Garut</p>
                </div>
            </div>
        </div>

        <!-- BAGIAN KANAN: Notifikasi & Profil User -->
        <div class="flex items-center gap-3 md:gap-6">

            <!-- Notifikasi (Sekarang Menggunakan Klik) -->
            <div class="relative flex items-center h-full py-1">
                <button onclick="toggleNotif(event)" class="relative p-2 text-gray-400 hover:text-[#1868D5] hover:bg-blue-50 rounded-xl transition-colors focus:outline-none" title="Notifikasi">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    @if($pendingCount > 0)
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full animate-ping"></span>
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full"></span>
                    @endif
                </button>

                <!-- Dropdown Notifikasi -->
                <div id="notifDropdown" class="absolute right-0 top-full mt-2 w-72 md:w-80 bg-white border border-gray-100 rounded-2xl opacity-0 invisible transition-all duration-300 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] flex flex-col overflow-hidden transform origin-top scale-95 z-50">
                    <div class="bg-gray-50/80 px-5 py-4 border-b border-gray-100 flex justify-between items-center backdrop-blur-sm">
                        <span class="font-extrabold text-gray-800">Notifikasi</span>
                        @if($pendingCount > 0)
                            <span class="bg-red-100 text-red-600 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $pendingCount }} Baru</span>
                        @endif
                    </div>

                    <div class="max-h-75 overflow-y-auto custom-scroll">
                        @forelse($pendingNotif as $notif)
                            <a href="{{ route('admin.ketidakhadiran.index') }}" class="block px-5 py-4 hover:bg-blue-50/50 border-b border-gray-50 transition-colors group/item">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-[#1868D5] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                        {{ substr($notif->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-800 group-hover/item:text-[#1868D5] transition-colors">{{ $notif->user->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Mengajukan <span class="font-bold text-gray-700">{{ $notif->keterangan }}</span> untuk tgl <span class="font-semibold">{{ \Carbon\Carbon::parse($notif->tanggal)->format('d/m/Y') }}</span></p>
                                        <p class="text-[10px] text-gray-400 mt-1.5 font-medium flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $notif->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="px-5 py-8 text-center flex flex-col items-center justify-center opacity-60">
                                <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm font-semibold text-gray-500">Tidak ada pengajuan baru.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($pendingCount > 0)
                        <a href="{{ route('admin.ketidakhadiran.index') }}" class="block bg-white px-5 py-3.5 text-center text-xs font-bold text-[#1868D5] hover:text-white hover:bg-[#1868D5] transition-colors border-t border-gray-100">
                            Lihat Semua Pengajuan
                        </a>
                    @endif
                </div>
            </div>

            <!-- Profil User (Sekarang Menggunakan Klik) -->
            <div class="relative flex items-center pl-3 md:pl-6 border-l border-gray-200 cursor-pointer h-full py-1">
                <div class="flex items-center gap-3 group" onclick="toggleUser(event)">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-extrabold text-gray-800 leading-none group-hover:text-[#1868D5] transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase tracking-wider">{{ Auth::user()->role ?? 'Admin' }}</p>
                    </div>
                    @if(Auth::user()->photo)
                        <img src="{{ asset('uploads/pegawai/' . Auth::user()->photo) }}" alt="Profile" class="w-9 h-9 md:w-10 md:h-10 rounded-xl border-2 border-white shadow-sm group-hover:border-blue-100 transition-colors object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=1868D5&color=fff&bold=true" alt="Profile" class="w-9 h-9 md:w-10 md:h-10 rounded-xl border-2 border-white shadow-sm group-hover:border-blue-100 transition-colors object-cover">
                    @endif
                    <svg class="w-4 h-4 text-gray-400 hidden md:block group-hover:text-[#1868D5] transition-transform group-hover:translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                <!-- Dropdown Profil -->
                <div id="profileDropdown" class="absolute right-0 top-full mt-2 w-48 md:w-52 bg-white border border-gray-100 rounded-2xl opacity-0 invisible transition-all duration-300 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] flex flex-col text-[14px] overflow-hidden transform origin-top scale-95 z-50">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 md:hidden">
                        <p class="text-sm font-extrabold text-gray-800">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-[11px] font-bold text-gray-400 uppercase mt-0.5">{{ Auth::user()->role ?? 'Admin' }}</p>
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
    <!-- 1. MENU NAVIGASI KHUSUS DESKTOP (LAPTOP) -->
    <!-- ========================================== -->
    <nav class="hidden md:block border-t border-gray-100 bg-white/50 relative z-40 px-4 md:px-0">
        <ul class="flex flex-wrap justify-center items-center gap-x-6 gap-y-1 md:gap-x-8 text-[14px] font-bold text-gray-500">
            <!-- Home -->
            <li>
                <a href="/admin/dashboard" class="flex items-center gap-2 py-4 px-2 border-b-2 transition-all duration-300 whitespace-nowrap {{ request()->is('admin/dashboard') ? 'text-[#1868D5] border-[#1868D5]' : 'border-transparent hover:text-[#1868D5] hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Home
                </a>
            </li>

            <!-- Pegawai -->
            <li>
                <a href="/admin/pegawai" class="flex items-center gap-2 py-4 px-2 border-b-2 transition-all duration-300 whitespace-nowrap {{ request()->is('admin/pegawai*') ? 'text-[#1868D5] border-[#1868D5]' : 'border-transparent hover:text-[#1868D5] hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Pegawai
                </a>
            </li>

            <!-- Master Data Dropdown -->
            <li class="relative group h-full">
                <button class="flex items-center gap-2 py-4 px-2 border-b-2 transition-all duration-300 cursor-pointer outline-none whitespace-nowrap {{ request()->is('admin/master*') ? 'text-[#1868D5] border-[#1868D5]' : 'border-transparent hover:text-[#1868D5] hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    Master Data
                    <svg class="w-3.5 h-3.5 opacity-70 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="absolute top-full left-0 w-full h-2"></div>
                <div class="absolute left-0 md:left-1/2 md:-translate-x-1/2 top-[calc(100%+4px)] w-56 bg-white border border-gray-100 rounded-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] flex flex-col overflow-hidden text-[13px] transform origin-top scale-95 group-hover:scale-100 z-50">
                    <a href="/admin/master/jabatan" class="flex items-center gap-3 px-5 py-3.5 hover:bg-blue-50 hover:pl-6 transition-all {{ request()->is('admin/master/jabatan') ? 'text-[#1868D5] font-black bg-blue-50/50' : 'text-gray-600 font-bold' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Data Jabatan
                    </a>
                    <a href="/admin/master/jam-kerja" class="flex items-center gap-3 px-5 py-3.5 hover:bg-blue-50 hover:pl-6 transition-all border-t border-gray-50 {{ request()->is('admin/master/jam-kerja') ? 'text-[#1868D5] font-black bg-blue-50/50' : 'text-gray-600 font-bold' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Data Jam Kerja
                    </a>
                    <a href="/admin/master/hari-libur" class="flex items-center gap-3 px-5 py-3.5 hover:bg-blue-50 hover:pl-6 transition-all border-t border-gray-50 {{ request()->is('admin/master/hari-libur') ? 'text-[#1868D5] font-black bg-blue-50/50' : 'text-gray-600 font-bold' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Data Hari Libur
                    </a>
                </div>
            </li>

            <!-- Rekap Presensi Dropdown -->
            <li class="relative group h-full">
                <button class="flex items-center gap-2 py-4 px-2 border-b-2 transition-all duration-300 cursor-pointer outline-none whitespace-nowrap {{ request()->is('admin/rekap-presensi*') ? 'text-[#1868D5] border-[#1868D5]' : 'border-transparent hover:text-[#1868D5] hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Rekap Presensi
                    <svg class="w-3.5 h-3.5 opacity-70 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="absolute top-full left-0 w-full h-2"></div>
                <div class="absolute left-0 md:left-1/2 md:-translate-x-1/2 top-[calc(100%+4px)] w-56 bg-white border border-gray-100 rounded-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] flex flex-col overflow-hidden text-[13px] transform origin-top scale-95 group-hover:scale-100 z-50">
                    <a href="/admin/rekap-presensi/harian" class="flex items-center gap-3 px-5 py-3.5 hover:bg-blue-50 hover:pl-6 transition-all {{ request()->is('admin/rekap-presensi/harian') ? 'text-[#1868D5] font-black bg-blue-50/50' : 'text-gray-600 font-bold' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Rekap Harian
                    </a>
                    <a href="/admin/rekap-presensi/bulanan" class="flex items-center gap-3 px-5 py-3.5 hover:bg-blue-50 hover:pl-6 transition-all border-t border-gray-50 {{ request()->is('admin/rekap-presensi/bulanan') ? 'text-[#1868D5] font-black bg-blue-50/50' : 'text-gray-600 font-bold' }}">
                        <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Rekap Bulanan
                    </a>
                </div>
            </li>

            <!-- Dropdown Portal Presensi -->
            <li class="relative group">
                <button class="flex items-center gap-2 text-gray-500 hover:text-indigo-600 py-4 px-2 border-b-2 border-transparent transition-all duration-300 hover:border-indigo-300 whitespace-nowrap cursor-pointer">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Portal Presensi
                    <svg class="w-4 h-4 opacity-70 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div class="absolute left-0 top-[100%] mt-0 w-52 bg-white border border-gray-100 rounded-lg shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 flex flex-col py-2">
                    <a href="{{ route('presensi.tap') }}" target="_blank" class="px-4 py-2.5 text-sm text-gray-600 hover:text-[#1868D5] hover:bg-blue-50/50 flex items-center gap-3 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        Scan QR
                    </a>
                    <a href="{{ route('admin.presensi.manual') }}" class="{{ request()->routeIs('admin.presensi.manual') ? 'text-[#1868D5] bg-blue-50/50 font-bold' : 'text-gray-600 hover:text-[#1868D5] hover:bg-blue-50/50 font-medium' }} px-4 py-2.5 text-sm flex items-center gap-3 transition-colors">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.presensi.manual') ? 'text-[#1868D5]' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Absen Manual
                    </a>
                </div>
            </li>

            <!-- Ketidakhadiran -->
            <li>
                <a href="{{ route('admin.ketidakhadiran.index') }}" class="flex items-center gap-2 py-4 px-2 border-b-2 transition-all duration-300 whitespace-nowrap {{ request()->routeIs('admin.ketidakhadiran.index') ? 'text-[#1868D5] border-[#1868D5]' : 'border-transparent hover:text-[#1868D5] hover:border-gray-300' }}">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Ketidakhadiran
                </a>
            </li>
        </ul>
    </nav>

    <!-- ========================================== -->
    <!-- 2. MENU NAVIGASI KHUSUS MOBILE (HP) -->
    <!-- ========================================== -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 shadow-inner flex-col overflow-y-auto max-h-[70vh] w-full absolute left-0 top-full z-40">
        <div class="p-4 flex flex-col gap-1.5 text-[15px] font-bold text-gray-600">

            <!-- Home -->
            <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-colors {{ request()->is('admin/dashboard') ? 'bg-blue-50 text-[#1868D5]' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Home
            </a>

            <!-- Pegawai -->
            <a href="/admin/pegawai" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-colors {{ request()->is('admin/pegawai*') ? 'bg-blue-50 text-[#1868D5]' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Pegawai
            </a>

            <!-- Master Data -->
            <div class="flex flex-col">
                <button type="button" onclick="toggleMobileDropdown('masterDataDropdown', 'masterDataChevron')" class="flex items-center justify-between px-4 py-3.5 rounded-xl transition-colors outline-none w-full {{ request()->is('admin/master*') ? 'bg-blue-50 text-[#1868D5]' : 'hover:bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        Master Data
                    </div>
                    <svg id="masterDataChevron" class="w-4 h-4 transition-transform duration-300 {{ request()->is('admin/master*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="masterDataDropdown" class="{{ request()->is('admin/master*') ? 'flex' : 'hidden' }} flex-col gap-1 pl-12 pr-4 pt-1 pb-2">
                    <a href="/admin/master/jabatan" class="py-2.5 px-3 rounded-lg text-[14px] {{ request()->is('admin/master/jabatan') ? 'text-[#1868D5] font-black' : 'text-gray-500 font-semibold hover:text-[#1868D5]' }}">Data Jabatan</a>
                    <a href="/admin/master/jam-kerja" class="py-2.5 px-3 rounded-lg text-[14px] {{ request()->is('admin/master/jam-kerja') ? 'text-[#1868D5] font-black' : 'text-gray-500 font-semibold hover:text-[#1868D5]' }}">Data Jam Kerja</a>
                    <a href="/admin/master/hari-libur" class="py-2.5 px-3 rounded-lg text-[14px] {{ request()->is('admin/master/hari-libur') ? 'text-[#1868D5] font-black' : 'text-gray-500 font-semibold hover:text-[#1868D5]' }}">Data Hari Libur</a>
                </div>
            </div>

            <!-- Rekap Presensi -->
            <div class="flex flex-col">
                <button type="button" onclick="toggleMobileDropdown('rekapDropdown', 'rekapChevron')" class="flex items-center justify-between px-4 py-3.5 rounded-xl transition-colors outline-none w-full {{ request()->is('admin/rekap-presensi*') ? 'bg-blue-50 text-[#1868D5]' : 'hover:bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Rekap Presensi
                    </div>
                    <svg id="rekapChevron" class="w-4 h-4 transition-transform duration-300 {{ request()->is('admin/rekap-presensi*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="rekapDropdown" class="{{ request()->is('admin/rekap-presensi*') ? 'flex' : 'hidden' }} flex-col gap-1 pl-12 pr-4 pt-1 pb-2">
                    <a href="/admin/rekap-presensi/harian" class="py-2.5 px-3 rounded-lg text-[14px] {{ request()->is('admin/rekap-presensi/harian') ? 'text-[#1868D5] font-black' : 'text-gray-500 font-semibold hover:text-[#1868D5]' }}">Rekap Harian</a>
                    <a href="/admin/rekap-presensi/bulanan" class="py-2.5 px-3 rounded-lg text-[14px] {{ request()->is('admin/rekap-presensi/bulanan') ? 'text-[#1868D5] font-black' : 'text-gray-500 font-semibold hover:text-[#1868D5]' }}">Rekap Bulanan</a>
                </div>
            </div>

            <!-- Mesin Presensi -->
            <a href="{{ route('presensi.tap') }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-colors text-indigo-600 hover:bg-indigo-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Buka Mesin Presensi
            </a>

            <!-- Ketidakhadiran -->
            <a href="{{ route('admin.ketidakhadiran.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-colors {{ request()->routeIs('admin.ketidakhadiran.index') ? 'bg-blue-50 text-[#1868D5]' : 'hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Ketidakhadiran
            </a>
        </div>
    </div>
</div> <!-- /AKHIR WRAPPER NAVBAR -->

<!-- ========================================== -->
<!-- MODAL PROFIL & PASSWORD -->
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
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=ffffff&color=0D3B66&bold=true&size=128" alt="Foto ID" class="w-24 h-24 rounded-2xl border-4 border-white/20 shadow-lg object-cover mx-auto bg-white">
                @endif
                <h2 class="text-xl font-black text-white mt-4 tracking-wide truncate">{{ Auth::user()->name ?? 'Administrator' }}</h2>
                <p class="text-[11px] font-bold text-blue-300 uppercase tracking-widest mt-1">{{ Auth::user()->position ?? 'Admin SPPG' }}</p>
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

<div id="modalPassword" class="fixed inset-0 z-60 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
    <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
        <div class="bg-amber-500 p-5 text-center text-white relative shrink-0">
            <h2 class="text-lg font-bold text-amber-950">Ubah Password Login</h2>
            <button type="button" onclick="togglePasswordModal()" class="absolute top-4 right-4 text-amber-900/70 hover:text-amber-950 bg-black/10 hover:bg-black/20 rounded-full p-1.5 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 md:p-8">
            <form action="{{ route('admin.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-5">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Password Lama</label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required placeholder="Masukkan password saat ini" class="w-full border border-gray-300 p-3 pr-10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none transition shadow-sm">
                        <button type="button" onclick="event.preventDefault(); toggleEyeIcon('current_password', 'icon-eye-current')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-amber-500 transition-colors">
                            <svg id="icon-eye-current" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" required placeholder="Minimal 6 karakter" class="w-full border border-gray-300 p-3 pr-10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none transition shadow-sm">
                        <button type="button" onclick="event.preventDefault(); toggleEyeIcon('new_password', 'icon-eye-new')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-amber-500 transition-colors">
                            <svg id="icon-eye-new" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required placeholder="Ketik ulang password baru" class="w-full border border-gray-300 p-3 pr-10 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 outline-none transition shadow-sm">
                        <button type="button" onclick="event.preventDefault(); toggleEyeIcon('new_password_confirmation', 'icon-eye-confirm')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-amber-500 transition-colors">
                            <svg id="icon-eye-confirm" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
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
<!-- MODAL EDIT PROFIL ADMIN (PASTIKAN POSISINYA DI SINI) -->
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
            <form action="{{ url('/admin/profile/update') }}" method="POST" enctype="multipart/form-data">
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
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Username Login</label>
                        <input type="text" name="username" value="{{ auth()->user()->username }}" required class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Password Baru</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border border-gray-300 p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition placeholder-gray-400">
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
    // SCRIPT KLIK DROPDOWN NOTIFIKASI
    function toggleNotif(event) {
        event.stopPropagation();
        const notif = document.getElementById('notifDropdown');
        const profil = document.getElementById('profileDropdown');

        if(profil) {
            profil.classList.remove('opacity-100', 'visible', 'scale-100');
            profil.classList.add('opacity-0', 'invisible', 'scale-95');
        }

        if (notif.classList.contains('invisible')) {
            notif.classList.remove('opacity-0', 'invisible', 'scale-95');
            notif.classList.add('opacity-100', 'visible', 'scale-100');
        } else {
            notif.classList.remove('opacity-100', 'visible', 'scale-100');
            notif.classList.add('opacity-0', 'invisible', 'scale-95');
        }
    }

    // SCRIPT KLIK DROPDOWN PROFIL USER
    function toggleUser(event) {
        event.stopPropagation();
        const notif = document.getElementById('notifDropdown');
        const profil = document.getElementById('profileDropdown');

        if(notif) {
            notif.classList.remove('opacity-100', 'visible', 'scale-100');
            notif.classList.add('opacity-0', 'invisible', 'scale-95');
        }

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
        const notif = document.getElementById('notifDropdown');
        const profil = document.getElementById('profileDropdown');

        if (notif && !event.target.closest('#notifDropdown') && !event.target.closest('[onclick="toggleNotif(event)"]')) {
            notif.classList.remove('opacity-100', 'visible', 'scale-100');
            notif.classList.add('opacity-0', 'invisible', 'scale-95');
        }
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

    // 1. Script bawaan Anda untuk membuka/menutup modal password
    function togglePasswordModal() {
        const modal = document.getElementById('modalPassword');
        const modalBox = modal.querySelector('div');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { modal.classList.remove('opacity-0'); modalBox.classList.remove('scale-95'); }, 10);
        } else {
            modal.classList.add('opacity-0'); modalBox.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }
    }

    // 2. Script baru untuk ikon mata
    function toggleEyeIcon(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
            icon.classList.add('text-amber-500'); 
        } else {
            input.type = 'password';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
            `;
            icon.classList.remove('text-amber-500');
        }
    }

    // SCRIPT MODAL PROFIL
    function toggleProfileModal() {
        const modal = document.getElementById('modalProfile');
        const modalBox = modal.querySelector('div');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { modal.classList.remove('opacity-0'); modalBox.classList.remove('scale-95'); }, 10);
        } else {
            modal.classList.add('opacity-0'); modalBox.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }
    }

    // FUNGSI UMUM MODAL (Dibutuhkan untuk Modal Edit Profil)
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

    // FUNGSI PERPINDAHAN MODAL (Profil -> Edit Profil)
    function bukaModalEdit(event) {
        event.preventDefault(); // Mencegah reload
        toggleProfileModal(); // Menutup profil dengan animasinya
        setTimeout(() => {
            toggleModal('modalEditProfile'); // Membuka form edit setelah profil tertutup
        }, 350);
    }
</script>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex flex-col font-sans text-gray-800 antialiased selection:bg-blue-200 selection:text-blue-900">

    @php
        // Cek status presensi hari ini
        $cekPresensi = \App\Models\Presensi::where('user_id', Auth::id())
                                ->where('tanggal', \Carbon\Carbon::today()->format('Y-m-d'))
                                ->first();

        $statusAbsen = 'belum'; // Default merah
        $teksStatus = 'Belum Absen Masuk';
        $warnaDot = 'bg-red-500';
        $warnaPing = 'bg-red-400';
        $warnaTeks = 'text-red-500';

        if ($cekPresensi) {
            if (is_null($cekPresensi->jam_keluar)) {
                $statusAbsen = 'bekerja'; // Kuning
                $teksStatus = 'Sedang Bekerja';
                $warnaDot = 'bg-amber-500';
                $warnaPing = 'bg-amber-400';
                $warnaTeks = 'text-amber-500';
            } else {
                $statusAbsen = 'selesai'; // Hijau
                $teksStatus = 'Pekerjaan Selesai';
                $warnaDot = 'bg-green-500';
                $warnaPing = 'hidden'; // Matikan efek kedip jika sudah selesai
                $warnaTeks = 'text-green-600';
            }
        }
    @endphp

    @include('pegawai.layouts.navbar')

    <main class="grow px-6 lg:px-8 py-10 w-full max-w-7xl mx-auto">

        <!-- ========================================== -->
        <!-- AREA NOTIFIKASI PRESENSI / PESAN SISTEM -->
        <!-- ========================================== -->
        @if(session('success'))
            <div class="bg-green-50 border-2 border-green-500 text-green-700 px-5 py-4 rounded-2xl relative shadow-sm flex items-center gap-4 animate-fade-in-up mb-8">
                <div class="bg-green-500 text-white p-2 rounded-full shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="block sm:inline font-black text-[15px]">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-amber-50 border-2 border-amber-500 text-amber-700 px-5 py-4 rounded-2xl relative shadow-sm flex items-center gap-4 animate-fade-in-up mb-8">
                <div class="bg-amber-500 text-white p-2 rounded-full shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="block sm:inline font-black text-[15px]">{{ session('warning') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-2 border-red-500 text-red-700 px-5 py-4 rounded-2xl relative shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-4 animate-fade-in-up mb-8">
                <div class="bg-red-500 text-white p-2 rounded-full shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div class="flex flex-col gap-1">
                    @foreach ($errors->all() as $error)
                        <span class="block sm:inline font-black text-[15px]">{{ $error }}</span>
                    @endforeach
                </div>
            </div>
        @endif
        <!-- ========================================== -->

        <!-- HEADER & JAM DIGITAL (Sama seperti Admin) -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8 animate-fade-in-up">
            <div class="text-left flex-1">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-linear-to-r from-[#0D3B66] to-[#1868D5]">
                    Halo, {{ Auth::user()?->name ?? 'Pegawai' }} 👋
                </h1>
                <p class="text-gray-500 text-sm md:text-base mt-2 font-medium">
                    Selamat bekerja! Jangan lupa melakukan presensi tepat waktu.
                </p>
            </div>

            <div class="shrink-0 w-full lg:w-auto relative group overflow-hidden bg-white px-6 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                <div class="absolute inset-0 bg-linear-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10 flex items-center gap-5">
                    <div class="p-3 bg-blue-50 rounded-xl text-[#1868D5]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div id="clock-digital" class="text-3xl font-black tracking-tight font-mono text-[#0D3B66]">00:00:00</div>
                        <div id="date-today" class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Memuat...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANEL AKSI CEPAT: SCAN PRESENSI (Fitur Baru Khusus Pegawai) -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-blue-100 p-6 md:p-8 mb-10 flex flex-col md:flex-row items-center justify-between bg-linear-to-r from-blue-50/50 to-white relative overflow-hidden animate-fade-in-up delay-100">
            <!-- Hiasan Background -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-100 rounded-full opacity-50 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 mb-6 md:mb-0 text-center md:text-left">
                <h2 class="text-xl font-black text-[#0D3B66] mb-1">Presensi Hari Ini</h2>
                <p class="text-sm text-gray-500 font-medium">Jadwal Jam Kerja Anda: <span class="font-bold text-[#1868D5] px-2 py-1 bg-blue-100/50 rounded-lg ml-1">{{ $shiftHariIni ?? 'Memuat...' }}</span></p>

                <div class="mt-4 flex items-center justify-center md:justify-start gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $warnaPing }} opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 {{ $warnaDot }}"></span>
                    </span>
                    <span class="text-sm font-bold {{ $warnaTeks }} uppercase tracking-wide">{{ $teksStatus }}</span>
                </div>
            </div>

            <!-- Tombol Scan QR (Hanya muncul jika belum selesai) -->
            @if($statusAbsen != 'selesai')
                <a href="{{ route('pegawai.scan') }}" class="relative z-10 w-full md:w-auto bg-[#1868D5] hover:bg-[#1250A5] text-white font-bold text-lg py-4 px-8 rounded-2xl transition-all duration-300 shadow-[0_8px_20px_-6px_rgba(24,104,213,0.5)] hover:shadow-[0_12px_25px_-6px_rgba(24,104,213,0.6)] hover:-translate-y-1 flex items-center justify-center gap-3 group">
                    <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    Scan QR Absensi
                </a>
            @else
                <!-- Tombol Disabled Jika Sudah Pulang -->
                <button disabled class="relative z-10 w-full md:w-auto bg-gray-200 text-gray-400 font-bold text-lg py-4 px-8 rounded-2xl cursor-not-allowed flex items-center justify-center gap-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    Presensi Tuntas
                </button>
            @endif
        </div>

        <!-- KARTU STATISTIK BULANAN PEGAWAI -->
        <div class="mb-4 flex items-center justify-between animate-fade-in-up delay-200">
            <h3 class="text-lg font-black text-[#0D3B66]">Rekap Kehadiran Saya (Bulan {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F') }})</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <!-- Card: Hadir -->
            <div class="relative overflow-hidden bg-white border border-green-50 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(22,163,74,0.15)] transition-all duration-300 hover:-translate-y-1 animate-fade-in-up delay-200 group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-green-100 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-green-600/70 uppercase tracking-wider mb-1">Total Hadir</p>
                        <h3 class="font-black text-2xl text-gray-800 leading-none">{{ $totalHadir ?? 0 }} <span class="text-sm font-semibold text-gray-400">Hari</span></h3>
                    </div>
                </div>
            </div>

            <!-- Card: Terlambat -->
            <div class="relative overflow-hidden bg-white border border-orange-50 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(249,115,22,0.15)] transition-all duration-300 hover:-translate-y-1 animate-fade-in-up delay-300 group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-orange-500/70 uppercase tracking-wider mb-1">Terlambat</p>
                        <h3 class="font-black text-2xl text-gray-800 leading-none">{{ $totalTerlambat ?? 0 }} <span class="text-sm font-semibold text-gray-400">Kali</span></h3>
                    </div>
                </div>
            </div>

            <!-- Card: Cuti/Izin/Sakit -->
            <div class="relative overflow-hidden bg-white border border-blue-50 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(24,104,213,0.15)] transition-all duration-300 hover:-translate-y-1 animate-fade-in-up delay-400 group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-blue-100 text-[#1868D5] flex items-center justify-center group-hover:bg-[#1868D5] group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-blue-600/70 uppercase tracking-wider mb-1">Izin / Sakit</p>
                        <h3 class="font-black text-2xl text-gray-800 leading-none">{{ $totalIzin ?? 0 }} <span class="text-sm font-semibold text-gray-400">Hari</span></h3>
                    </div>
                </div>
            </div>

            <!-- Card: Alpa -->
            <div class="relative overflow-hidden bg-white border border-red-50 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(220,38,38,0.15)] transition-all duration-300 hover:-translate-y-1 animate-fade-in-up delay-500 group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-red-100 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-red-600/70 uppercase tracking-wider mb-1">Alpa</p>
                        <h3 class="font-black text-2xl text-gray-800 leading-none">{{ $totalAlpa ?? 0 }} <span class="text-sm font-semibold text-gray-400">Hari</span></h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Riwayat Pengajuan Izin/Cuti Terbaru -->
        <div class="mt-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-[#0D3B66]">Riwayat Pengajuan Terbaru</h2>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                                <th class="p-4 font-extrabold">Tanggal Izin</th>
                                <th class="p-4 font-extrabold">Kategori</th>
                                <th class="p-4 font-extrabold">Deskripsi / Alasan</th>
                                <th class="p-4 font-extrabold text-center">Status</th>
                                <th class="p-4 font-extrabold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @php
                                $riwayatPengajuan = \App\Models\Ketidakhadiran::where('user_id', auth()->id())
                                                    ->orderBy('created_at', 'desc')
                                                    ->take(5)
                                                    ->get();
                            @endphp

                            @forelse($riwayatPengajuan as $riwayat)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="p-4 font-semibold text-gray-800 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($riwayat->tanggal_izin)->translatedFormat('d F Y') }}
                                </td>
                                <td class="p-4">
                                    <span class="font-bold text-gray-700">{{ $riwayat->kategori }}</span>
                                </td>
                                <td class="p-4 text-gray-500 truncate max-w-50" title="{{ $riwayat->deskripsi }}">
                                    {{ $riwayat->deskripsi }}
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if($riwayat->status == 'Disetujui')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">✅ Disetujui</span>
                                    @elseif($riwayat->status == 'Ditolak')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">❌ Ditolak</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">⏳ Menunggu</span>
                                    @endif
                                </td>

                                <!-- KOLOM AKSI -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if($riwayat->status == 'Menunggu')
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('pegawai.ketidakhadiran.edit', $riwayat->id) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-bold transition-colors">
                                                Ubah
                                            </a>
                                            <form action="{{ route('pegawai.ketidakhadiran.destroy', $riwayat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini? Data akan dihapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg text-xs font-bold transition-colors">
                                                    Batal
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="font-medium text-sm">Belum ada riwayat pengajuan Izin, Cuti, atau Sakit.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="w-full px-6 lg:px-8 py-6 mt-auto border-t border-gray-200/60 bg-white">
        <p class="text-[13px] md:text-sm font-bold text-gray-400 text-center lg:text-left">
            © 2026 <span class="text-[#0D3B66]">SPPG Langensari Tarogong Kaler</span>. All Rights Reserved.
        </p>
    </footer>

    <script>
        // Script Jam Digital Real-time
        function startRealTimeClock() {
            const clockElement = document.getElementById('clock-digital');
            const dateElement = document.getElementById('date-today');

            function updateTime() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                if (clockElement) clockElement.innerText = `${hours}:${minutes}:${seconds}`;

                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                if (dateElement) dateElement.innerText = now.toLocaleDateString('id-ID', options);
            }

            updateTime();
            setInterval(updateTime, 1000);
        }
        startRealTimeClock();
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi Harian - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex flex-col font-sans text-gray-800 antialiased selection:bg-blue-200 selection:text-blue-900">

    @include('admin.layouts.navbar')

    <main class="grow px-6 lg:px-8 py-10 w-full max-w-7xl mx-auto animate-fade-in-up">

        <!-- HEADER & TOMBOL EXPORT -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Rekap Presensi Harian</h1>
                <p class="text-gray-500 text-sm font-medium mt-1">Pantau kehadiran harian karyawan secara detail.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('rekap.harian.pdf', ['tanggal' => $filterDate]) }}" target="_blank" class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold py-2.5 px-5 rounded-xl transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- FILTER TANGGAL -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
            <div>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Menampilkan Data Presensi</p>
                <h3 class="text-xl font-black text-gray-800">{{ $tanggalFormat }}</h3>
            </div>

            <form action="{{ route('presensi.harian') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="relative w-full sm:w-auto group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-[#1868D5] transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="date" name="tanggal" value="{{ $filterDate }}" class="w-full sm:w-56 bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl focus:ring-[#1868D5] focus:border-[#1868D5] block pl-10 p-2.5 outline-none transition hover:bg-gray-100">
                </div>
                <button type="submit" class="w-full sm:w-auto bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all hover:-translate-y-0.5">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <!-- TABEL DATA PRESENSI -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="w-full overflow-x-auto custom-scroll">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-[11px] uppercase tracking-wider font-extrabold text-gray-400">
                        <tr>
                            <th class="py-4 px-6 w-16 text-center">No</th>
                            <th class="py-4 px-6">Nama Pegawai</th>
                            <th class="py-4 px-6 text-center">Jam Masuk</th>
                            <th class="py-4 px-6 text-center">Jam Keluar</th>
                            <th class="py-4 px-6 text-center">Total Jam</th>
                            <th class="py-4 px-6 text-center">Terlambat</th>
                            <th class="py-4 px-6 text-center w-40">Status / Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($rekap as $index => $r)
                        <tr class="hover:bg-blue-50/30 transition-colors group">

                            <!-- 1. Nomor -->
                            <td class="py-5 px-6 text-center font-semibold text-gray-500">{{ $index + 1 }}</td>

                            <!-- 2. Nama Pegawai (Diambil dari relasi user) -->
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-[#1868D5] flex items-center justify-center font-black text-xs shrink-0 border border-blue-200">
                                        {{ substr($r->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-[14px]">{{ $r->user->name ?? 'Pegawai Dihapus' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- 3. Jam Masuk -->
                            <td class="py-5 px-6 text-center">
                                @if($r->jam_masuk)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                        {{ \Carbon\Carbon::parse($r->jam_masuk)->format('H:i') }} WIB
                                    </span>
                                @else
                                    <span class="text-gray-400 font-medium">-</span>
                                @endif
                            </td>

                            <!-- 4. Jam Keluar -->
                            <td class="py-5 px-6 text-center">
                                @if($r->jam_keluar)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 font-bold text-xs border border-rose-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        {{ \Carbon\Carbon::parse($r->jam_keluar)->format('H:i') }} WIB
                                    </span>
                                @else
                                    <span class="text-gray-400 font-medium italic text-xs">Belum Pulang</span>
                                @endif
                            </td>

                            <!-- 5. Total Jam Kerja (Otomatis Hitung Selisih Masuk & Keluar) -->
                            <td class="py-5 px-6 text-center">
                                @if($r->jam_masuk && $r->jam_keluar)
                                    @php
                                        $masuk = \Carbon\Carbon::parse($r->jam_masuk);
                                        $keluar = \Carbon\Carbon::parse($r->jam_keluar);
                                        $totalMenit = $masuk->diffInMinutes($keluar);
                                        $jamKerja = floor($totalMenit / 60);
                                        $menitKerja = $totalMenit % 60;
                                    @endphp
                                    <span class="font-extrabold text-[14px] text-[#0D3B66]">
                                        {{ $jamKerja }}<span class="text-[10px] text-gray-400 font-normal ml-0.5 mr-1.5">j</span>{{ $menitKerja }}<span class="text-[10px] text-gray-400 font-normal ml-0.5">m</span>
                                    </span>
                                @else
                                    <span class="text-gray-400 font-medium">-</span>
                                @endif
                            </td>

                            <!-- 6. Terlambat -->
                            <td class="py-5 px-6 text-center">
                                @if($r->menit_terlambat > 0)
                                    @php
                                        $jamTelat = floor($r->menit_terlambat / 60);
                                        $menitTelat = $r->menit_terlambat % 60;
                                        $teksTelat = '';
                                        if($jamTelat > 0) $teksTelat .= $jamTelat . 'j ';
                                        if($menitTelat > 0 || $jamTelat == 0) $teksTelat .= $menitTelat . 'm';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 font-bold text-xs border border-amber-200 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $teksTelat }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-xs border border-emerald-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        Tepat
                                    </span>
                                @endif
                            </td>

                            <!-- 7. Status / Keterangan -->
                            <td class="py-5 px-6 text-center">
                                @php $statusLabel = strtolower($r->status); @endphp

                                @if(in_array($statusLabel, ['hadir']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        Hadir
                                    </span>
                                @elseif(in_array($statusLabel, ['terlambat']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200">
                                        Terlambat
                                    </span>
                                @elseif(in_array($statusLabel, ['izin', 'sakit', 'cuti']))
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">
                                        {{ $r->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-red-100 text-red-700 border border-red-200">
                                        {{ $r->status ?: 'Alpa' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center opacity-60">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <p class="text-gray-500 font-bold text-lg">Tidak ada data presensi.</p>
                                    <p class="text-gray-400 text-sm mt-1">Belum ada karyawan yang absen pada tanggal ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="w-full px-6 lg:px-8 py-6 mt-auto border-t border-gray-200/60 bg-white">
        <p class="text-[13px] md:text-sm font-bold text-gray-400 text-center lg:text-left">
            © 2026 <span class="text-[#0D3B66]">SPPG Langensari Tarogong Kaler</span>. All Rights Reserved.
        </p>
    </footer>
</body>
</html>

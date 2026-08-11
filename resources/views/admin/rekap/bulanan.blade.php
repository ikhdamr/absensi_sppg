<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi Bulanan - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex flex-col font-sans text-gray-800 antialiased selection:bg-blue-200 selection:text-blue-900">

    @include('admin.layouts.navbar')

    <main class="grow px-6 lg:px-8 py-10 w-full max-w-7xl mx-auto">

        <!-- HEADER & TOMBOL EXPORT -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Rekap Presensi Bulanan</h1>
                <p class="text-gray-500 text-sm font-medium mt-1">Pantau akumulasi kehadiran karyawan setiap bulannya.</p>
            </div>
            <div>
                <a href="{{ route('rekap.bulanan.pdf', ['bulan' => $filterMonth, 'tahun' => $filterYear]) }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 font-bold py-2.5 px-5 rounded-xl transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- FILTER BULAN & TAHUN -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-5">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Menampilkan Rekapitulasi</p>
                <h3 class="text-xl font-black text-gray-800 uppercase">{{ $bulanFormat }}</h3>
            </div>

            <form action="{{ route('presensi.bulanan') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <!-- Select Bulan -->
                <div class="w-full sm:w-44">
                    <select name="bulan" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl focus:ring-[#1868D5] focus:border-[#1868D5] block p-2.5 outline-none transition cursor-pointer">
                        @foreach($daftarBulan as $key => $namaBulan)
                            <option value="{{ $key }}" {{ $filterMonth == $key ? 'selected' : '' }}>
                                {{ $namaBulan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Select Tahun (Diperlebar agar tidak terjepit) -->
                <div class="w-full sm:w-32">
                    <select name="tahun" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl focus:ring-[#1868D5] focus:border-[#1868D5] block p-2.5 outline-none transition cursor-pointer">
                        @php $tahunSekarang = date('Y'); @endphp
                        @for($t = $tahunSekarang - 2; $t <= $tahunSekarang + 1; $t++)
                            <option value="{{ $t }}" {{ $filterYear == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="w-full sm:w-auto bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all">
                    Terapkan Filter
                </button>
            </form>
        </div>

        <!-- TABEL DATA PRESENSI -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="w-full overflow-x-auto custom-scroll">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-[11px] uppercase font-extrabold text-gray-400">
                        <tr>
                            <th class="py-4 px-6 w-16 text-center">No</th>
                            <th class="py-4 px-6">Nama Pegawai</th>
                            <th class="py-4 px-6 text-center">Total Hadir</th>
                            <th class="py-4 px-6 text-center">Total Terlambat</th>
                            <th class="py-4 px-6 text-center">Izin / Sakit / Cuti</th>
                            <th class="py-4 px-6 text-center">Total Alpa</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($rekap as $index => $item)
                        <tr class="hover:bg-blue-50/30 transition-colors">

                            <!-- Nomor -->
                            <td class="py-4 px-6 text-center font-medium text-gray-500">{{ $index + 1 }}</td>

                            <!-- Nama -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-[#1868D5] flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr($item->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-[14px]">{{ $item->name }}</p>
                                        <p class="text-[11px] font-medium text-gray-400 mt-0.5">Pegawai</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Total Hadir -->
                            <td class="py-4 px-6 text-center">
                                @if($item->total_hadir > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 font-bold text-[13px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        {{ $item->total_hadir }} <span class="text-[10px] uppercase">Hari</span>
                                    </span>
                                @else
                                    <span class="text-gray-400 font-bold text-xs">0 HARI</span>
                                @endif
                            </td>

                            <!-- Total Terlambat -->
                            <td class="py-4 px-6 text-center">
                                @if($item->total_terlambat > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 text-amber-600 border border-amber-200 font-bold text-[13px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $item->total_terlambat }} <span class="text-[10px] uppercase">Kali</span>
                                    </span>
                                @else
                                    <span class="text-gray-400 font-bold text-xs">0 KALI</span>
                                @endif
                            </td>

                            <!-- Total Izin -->
                            <td class="py-4 px-6 text-center">
                                @if($item->total_izin > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 font-bold text-[13px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        {{ $item->total_izin }} <span class="text-[10px] uppercase">Hari</span>
                                    </span>
                                @else
                                    <span class="text-gray-400 font-bold text-xs">0 HARI</span>
                                @endif
                            </td>

                            <!-- Total Alpa -->
                            <td class="py-4 px-6 text-center">
                                @if($item->total_alpa > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-red-50 text-red-600 border border-red-200 font-bold text-[13px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        {{ $item->total_alpa }} <span class="text-[10px] uppercase">Hari</span>
                                    </span>
                                @else
                                    <span class="text-gray-400 font-bold text-xs">0 HARI</span>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-500 font-medium">
                                Tidak ada data pegawai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>

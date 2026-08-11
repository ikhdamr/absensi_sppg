<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi Pegawai - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans text-gray-800 antialiased">

    @include('pegawai.layouts.navbar')

    <main class="grow px-4 sm:px-8 py-10 w-full max-w-7xl mx-auto animate-fade-in-up">

        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Rekap Presensi Bulanan</h1>
                <p class="text-gray-500 font-medium mt-1">Pantau riwayat kehadiran Anda di bulan ini.</p>
            </div>

            <form action="{{ route('pegawai.rekap') }}" method="GET" class="flex gap-2">
                <select name="bulan" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none font-semibold">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $bulanIni == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <select name="tahun" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 outline-none font-semibold">
                    <option value="{{ date('Y') }}" {{ $tahunIni == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                    <option value="{{ date('Y') - 1 }}" {{ $tahunIni == (date('Y') - 1) ? 'selected' : '' }}>{{ date('Y') - 1 }}</option>
                </select>
                <button type="submit" class="bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wider font-bold">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Jam Masuk</th>
                            <th class="px-6 py-4 text-center">Jam Pulang</th>
                            <th class="px-6 py-4 text-center">Keterlambatan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($presensi as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-[#1868D5]">
                                    {{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '--:--' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-gray-600">
                                    {{ $item->jam_keluar ? \Carbon\Carbon::parse($item->jam_keluar)->format('H:i') : '--:--' }}
                                </td>

                                <!-- BAGIAN YANG DIUBAH: Logika Keterlambatan Jam & Menit -->
                                <td class="px-6 py-4 text-center font-medium">
                                    @if(is_numeric($item->menit_terlambat) && $item->menit_terlambat > 0)
                                        @php
                                            $jam = floor($item->menit_terlambat / 60);
                                            $menit = $item->menit_terlambat % 60;
                                        @endphp

                                        <span class="text-red-600 font-bold">
                                            @if($jam > 0)
                                                {{ $jam }} Jam {{ $menit }} Menit
                                            @else
                                                {{ $menit }} Menit
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <!-- ====================================================== -->

                                <td class="px-6 py-4 text-center font-bold uppercase text-sm">
                                    @if($item->status == 'hadir')
                                        <span class="text-green-600 bg-green-100 px-3 py-1 rounded-full">Hadir</span>
                                    @elseif($item->status == 'alpa')
                                        <span class="text-red-600 bg-red-100 px-3 py-1 rounded-full">Alpa</span>
                                    @else
                                        <span class="text-blue-600 bg-blue-100 px-3 py-1 rounded-full">{{ $item->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium text-lg">
                                    Belum ada data presensi di bulan ini.
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

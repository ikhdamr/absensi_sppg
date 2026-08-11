<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Presensi Manual - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans text-gray-800 antialiased">

    @include('admin.layouts.navbar')

    <main class="grow px-4 sm:px-8 py-10 w-full max-w-5xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Absen Manual Pegawai</h1>
            <p class="text-gray-500 font-medium mt-1">Centang status kehadiran masing-masing pegawai di bawah ini.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <p class="text-green-700 font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <p class="text-amber-700 font-bold">{{ session('warning') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
                <p class="text-red-700 font-bold">{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="{{ route('admin.presensi.manual.store') }}" method="POST">
            @csrf

            <!-- Filter Tanggal -->
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-lg text-[#1868D5]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 text-sm">Pilih Tanggal Absen</label>
                    </div>
                </div>
                <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full sm:w-auto border border-gray-300 rounded-lg p-2.5 outline-none focus:border-blue-500 bg-gray-50 font-bold text-gray-700">
            </div>

            <!-- Tabel Daftar Pegawai -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#0D3B66] text-white text-xs uppercase tracking-wider font-bold">
                                <th class="px-6 py-4">Nama Pegawai</th>
                                <th class="px-3 py-4 text-center">Hadir</th>
                                <th class="px-3 py-4 text-center">Izin</th>
                                <th class="px-3 py-4 text-center">Sakit</th>
                                <th class="px-3 py-4 text-center">Alpa</th>
                                <th class="px-6 py-4">Jam (Masuk / Pulang)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pegawai as $p)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-900">{{ $p->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $p->id_pegawai }}</p>
                                    </td>
                                    
                                    <!-- Radio Status -->
                                    <td class="px-3 py-4 text-center border-l border-gray-100">
                                        <input type="radio" name="presensi[{{ $p->id }}]" value="Hadir" data-id="{{ $p->id }}" class="radio-status w-5 h-5 text-green-600 cursor-pointer">
                                    </td>
                                    <td class="px-3 py-4 text-center">
                                        <input type="radio" name="presensi[{{ $p->id }}]" value="Izin" data-id="{{ $p->id }}" class="radio-status w-5 h-5 text-blue-600 cursor-pointer">
                                    </td>
                                    <td class="px-3 py-4 text-center">
                                        <input type="radio" name="presensi[{{ $p->id }}]" value="Sakit" data-id="{{ $p->id }}" class="radio-status w-5 h-5 text-orange-500 cursor-pointer">
                                    </td>
                                    <td class="px-3 py-4 text-center">
                                        <input type="radio" name="presensi[{{ $p->id }}]" value="Alpa" data-id="{{ $p->id }}" class="radio-status w-5 h-5 text-red-600 cursor-pointer">
                                    </td>

                                    <!-- Kolom Input Waktu (Hidden Default) -->
                                    <td class="px-6 py-4">
                                        <div id="waktu-input-{{ $p->id }}" class="hidden flex-col gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-gray-500 w-12 font-medium">Masuk</span>
                                                <input type="time" name="jam_masuk[{{ $p->id }}]" class="border border-gray-300 rounded p-1.5 text-sm outline-none focus:border-blue-500">
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-gray-500 w-12 font-medium">Pulang</span>
                                                <input type="time" name="jam_keluar[{{ $p->id }}]" class="border border-gray-300 rounded p-1.5 text-sm outline-none focus:border-blue-500">
                                            </div>
                                        </div>
                                        <span id="waktu-placeholder-{{ $p->id }}" class="text-xs text-gray-400 italic">Pilih 'Hadir' untuk isi jam</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end sticky bottom-6 z-10">
                <button type="submit" class="bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full shadow-[0_8px_20px_-6px_rgba(24,104,213,0.5)] transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Presensi Manual
                </button>
            </div>
        </form>
    </main>

    <!-- Script Logika Menampilkan Jam -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.radio-status');
            
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const userId = this.getAttribute('data-id');
                    const waktuInput = document.getElementById('waktu-input-' + userId);
                    const placeholder = document.getElementById('waktu-placeholder-' + userId);
                    
                    if (this.value === 'Hadir') {
                        waktuInput.classList.remove('hidden');
                        waktuInput.classList.add('flex');
                        placeholder.classList.add('hidden');
                    } else {
                        waktuInput.classList.add('hidden');
                        waktuInput.classList.remove('flex');
                        placeholder.classList.remove('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Ketidakhadiran Pegawai - SPPG</title>
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

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Persetujuan Ketidakhadiran</h1>
                <p class="text-gray-500 text-sm font-medium mt-1">Kelola dan tinjau pengajuan izin, sakit, dan cuti dari karyawan.</p>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES / ERROR -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 font-semibold flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 font-semibold flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- TABEL DATA PENGAJUAN -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="w-full overflow-x-auto custom-scroll">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-[11px] uppercase tracking-wider font-extrabold text-gray-500">
                        <tr>
                            <th class="py-4 px-6 w-16 text-center">No</th>
                            <th class="py-4 px-6">Nama Pegawai</th>
                            <th class="py-4 px-6">Tanggal Izin</th>
                            <th class="py-4 px-6 text-center">Kategori</th>
                            <th class="py-4 px-6">Alasan / Deskripsi</th>
                            <th class="py-4 px-6 text-center">File Bukti</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-center w-32">Aksi Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">

                        @forelse($pengajuan as $index => $item)
                        <tr class="hover:bg-blue-50/30 transition-colors group">

                            <!-- 1. Nomor -->
                            <td class="py-5 px-6 text-center font-semibold text-gray-500">{{ $index + 1 }}</td>

                            <!-- 2. Nama Pegawai -->
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-[#1868D5] flex items-center justify-center font-black text-xs shrink-0 border border-blue-200">
                                        {{ substr($item->user->name, 0, 1) }}
                                    </div>
                                    <p class="font-bold text-gray-900 text-[14px]">{{ $item->user->name }}</p>
                                </div>
                            </td>

                            <!-- 3. Tanggal Izin -->
                            <!-- (Menggunakan variabel fallback $item->tanggal_izin atau $item->tanggal sesuai database) -->
                            <td class="py-5 px-6">
                                <span class="font-bold text-gray-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal_izin ?? $item->tanggal)->translatedFormat('d M Y') }}
                                </span>
                            </td>

                            <!-- 4. Kategori (Warna Baru & Jelas) -->
                            <td class="py-5 px-6 text-center">
                                @php
                                    $kategoriTeks = $item->kategori ?? $item->keterangan;
                                @endphp
                                @if($kategoriTeks == 'Sakit')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-orange-100 text-orange-700 border border-orange-200">
                                         Sakit
                                    </span>
                                @elseif($kategoriTeks == 'Izin')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200">
                                         Izin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-purple-100 text-purple-700 border border-purple-200">
                                         Cuti
                                    </span>
                                @endif
                            </td>

                            <!-- 5. Deskripsi (Lebih padat, dipotong jika kepanjangan) -->
                            <td class="py-5 px-6">
                                <p class="text-[13px] text-gray-500 max-w-50 truncate" title="{{ $item->deskripsi }}">
                                    {{ $item->deskripsi }}
                                </p>
                            </td>

                            <!-- 6. File Bukti -->
                            <td class="py-5 px-6 text-center">
                                @php
                                    $fileBukti = $item->file_bukti ?? $item->file_surat;
                                @endphp
                                @if($fileBukti)
                                    <a href="{{ asset('storage/' . $fileBukti) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#1868D5]/10 hover:bg-[#1868D5] text-[#1868D5] hover:text-white font-bold text-[11px] uppercase tracking-wider transition-colors shadow-sm" title="Lihat Dokumen">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        Buka Surat
                                    </a>
                                @else
                                    <span class="text-gray-400 font-medium text-xs italic bg-gray-50 px-3 py-1 rounded border border-gray-100">Tanpa Lampiran</span>
                                @endif
                            </td>

                            <!-- 7. Status -->
                            <td class="py-5 px-6 text-center">
                                @if($item->status === 'Menunggu')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-600 font-bold text-[11px] border border-amber-200">
                                        <svg class="w-3.5 h-3.5 animate-spin-slow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Menunggu
                                    </span>
                                @elseif($item->status === 'Disetujui')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 font-bold text-[11px] border border-emerald-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-600 font-bold text-[11px] border border-red-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- 8. Aksi Pengajuan -->
                            <td class="py-5 px-6">
                                @if($item->status === 'Menunggu')
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Form Setuju -->
                                        <form action="{{ route('admin.ketidakhadiran.status', $item->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin MENYETUJUI pengajuan ini?');">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit" class="group flex items-center justify-center w-8 h-8 bg-emerald-50 hover:bg-emerald-500 border border-emerald-200 hover:border-emerald-500 rounded-lg transition-all shadow-sm" title="Setujui Pengajuan">
                                                <svg class="w-5 h-5 text-emerald-600 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>

                                        <!-- Form Tolak -->
                                        <form action="{{ route('admin.ketidakhadiran.status', $item->id) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin MENOLAK pengajuan ini?');">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="group flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-500 border border-red-200 hover:border-red-500 rounded-lg transition-all shadow-sm" title="Tolak Pengajuan">
                                                <svg class="w-5 h-5 text-red-600 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="flex justify-center">
                                        <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200/60 uppercase tracking-widest">
                                            Selesai
                                        </span>
                                    </div>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center opacity-60">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-gray-500 font-bold text-lg">Belum ada pengajuan izin.</p>
                                    <p class="text-gray-400 text-sm mt-1">Saat ini tidak ada karyawan yang mengajukan ketidakhadiran.</p>
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

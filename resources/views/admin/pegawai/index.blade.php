<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pegawai - SPPG</title>
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

       <!-- HEADER & TOOLBAR PENCARIAN -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Data Pegawai</h1>
                <p class="text-gray-500 text-sm font-medium mt-1">Kelola informasi pegawai</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- FORM PENCARIAN -->
                <form action="{{ url('/admin/pegawai') }}" method="GET" class="w-full sm:w-72 relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors group-focus-within:text-[#1868D5] text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau ID Pegawai..."
                        class="w-full bg-white border border-gray-200 text-gray-800 text-sm font-semibold rounded-xl focus:ring-4 focus:ring-[#1868D5]/15 focus:border-[#1868D5] block pl-10 pr-4 py-2.5 outline-none transition-all shadow-sm">

                    <!-- Tombol Reset (X) Muncul jika sedang mencari sesuatu -->
                    @if(request('search'))
                        <a href="{{ url('/admin/pegawai') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors" title="Hapus Pencarian">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>

                <!-- TOMBOL TAMBAH PEGAWAI -->
                <button onclick="toggleModal('modalTambah')" class="w-full sm:w-auto group relative inline-flex items-center justify-center gap-2 bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-[0_4px_15px_rgb(24,104,213,0.3)] hover:shadow-[0_6px_20px_rgb(24,104,213,0.4)] transition-all duration-300 hover:-translate-y-0.5 shrink-0">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pegawai
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 font-semibold flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="w-full overflow-x-auto custom-scroll">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-bold text-gray-500">
                        <tr>
                            <th class="py-4 px-6 w-16 text-center">No</th>
                            <th class="py-4 px-6">Profil Pegawai</th>
                            <th class="py-4 px-6">Jabatan</th>
                            <th class="py-4 px-6">Shift</th>
                            <th class="py-4 px-6 text-center">Role</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
    @forelse($pegawai as $index => $p)
    <tr class="hover:bg-blue-50/30 transition-colors group">
        <td class="py-4 px-6 text-center font-semibold text-gray-600">{{ $index + 1 }}</td>

        <td class="py-4 px-6">
            <div class="flex items-center gap-4">
                <div class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm group-hover:border-blue-100 transition-colors bg-gray-100">
                    @if($p->photo)
                        <img src="/uploads/pegawai/{{ $p->photo }}" alt="Foto" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($p->name) }}&background=0D3B66&color=fff" alt="Foto" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <p class="font-bold text-gray-900 text-base">{{ $p->name }}</p>
                    <p class="text-gray-500 text-xs mt-0.5">ID: <span class="font-mono font-bold text-gray-700">{{ $p->id_pegawai ?? '-' }}</span></p>
                </div>
            </div>
        </td>

        <td class="py-4 px-6">
            <p class="font-bold text-gray-700 text-[13px]">{{ $p->jabatan?->nama_jabatan ?? '-' }}</p>
        </td>

        <td class="py-4 px-6">
            @if($p->shift)
                <p class="text-[11px] font-bold text-[#1868D5] bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-full inline-block">{{ $p->shift->nama_shift }}</p>
            @else
                <p class="text-[11px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded-full inline-block">Belum ada Shift</p>
            @endif
        </td>

        <td class="py-4 px-6 text-center">
            <span class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider rounded-full {{ $p->role == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-[#1868D5]' }}">
                {{ $p->role }}
            </span>
        </td>

        <td class="py-4 px-6">
    <div class="flex justify-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">

        <button type="button" id="btn-detail-{{ $p->id }}" onclick="openDetailModal('{{ $p->id }}')"
            data-user="{{ json_encode($p) }}"
            data-shift="{{ $p->shift ? $p->shift->nama_shift : '-' }}"
            class="p-2 bg-gray-100 hover:bg-[#1868D5] hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Lihat ID Card">
            <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
        </button>

        <button type="button" id="btn-edit-{{ $p->id }}" onclick="openEditModal('{{ $p->id }}')"
            data-idpegawai="{{ $p->id_pegawai }}"
            data-name="{{ $p->name }}"
            data-jabatan="{{ $p->jabatan_id }}"
            data-shift="{{ $p->shift_id }}"
            data-email="{{ $p->email }}"
            data-username="{{ $p->username }}"
            data-role="{{ $p->role }}"
            data-phone="{{ $p->phone }}"
            data-alamat="{{ $p->alamat }}"
            class="p-2 bg-gray-100 hover:bg-yellow-500 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Edit">
            <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
        </button>

        <!-- TOMBOL RESET PASSWORD -->
        <button type="button" onclick="openResetModal('{{ $p->id }}', '{{ $p->name }}')"
            class="p-2 bg-gray-100 hover:bg-indigo-600 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Reset Password">
            <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
        </button>

        <button type="button" id="btn-delete-{{ $p->id }}" onclick="openDeleteModal('{{ $p->id }}')"
            class="p-2 bg-gray-100 hover:bg-red-600 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Hapus">
            <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>

    </div>
</td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="py-12 text-center text-gray-400 font-medium text-base">Belum ada data pegawai.</td>
    </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalDelete" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5 border-[6px] border-red-50/50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Hapus Data Pegawai?</h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Anda yakin ingin menghapus data ini?<br>
                    Tindakan ini permanen dan tidak dapat dibatalkan.
                </p>
            </div>
            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex gap-3 justify-center">
                <button type="button" onclick="toggleModal('modalDelete')" class="w-full bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 font-bold py-3 rounded-xl transition-colors shadow-sm">Batal</button>
                <form id="deleteForm" action="" method="POST" class="w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm">Hapus Data</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL RESET PASSWORD -->
    <div id="modalReset" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-5 border-[6px] border-indigo-50/50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Reset Password?</h2>
                <p class="text-gray-500 text-sm leading-relaxed mb-5">
                    Anda yakin ingin mereset password milik <br><strong id="resetPegawaiName" class="text-indigo-600 text-base">Nama Pegawai</strong>?
                </p>
                <div class="bg-indigo-50/50 border border-indigo-100 py-3 px-4 rounded-xl inline-block">
                    <p class="text-[10px] font-extrabold text-indigo-400 uppercase tracking-widest mb-1">Password akan direset ke:</p>
                    <p class="text-xl font-black text-indigo-600 tracking-widest font-mono">SPPG2026!</p>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex gap-3 justify-center">
                <button type="button" onclick="toggleModal('modalReset')" class="w-full bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 font-bold py-3 rounded-xl transition-colors shadow-sm">Batal</button>
                <form id="resetForm" action="" method="POST" class="w-full">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition-colors shadow-sm">Ya, Reset Sekarang</button>
                </form>
            </div>
        </div>
    </div>

    <div id="modalDetail" class="fixed inset-0 z-50 hidden bg-gray-900/70 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-sm shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-full">
            <button type="button" onclick="toggleModal('modalDetail')" class="absolute top-4 right-4 z-20 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full p-1.5 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="bg-linear-to-tr from-[#0D3B66] to-[#114B82] pt-10 pb-6 px-6 text-center relative overflow-hidden shrink-0">
                <div class="absolute -left-16 -top-16 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-500/10 rounded-full pointer-events-none blur-xl"></div>
                <div class="relative z-10">
                    <img id="detailPhoto" src="" alt="Foto ID" class="w-24 h-24 rounded-2xl border-4 border-white/20 shadow-lg object-cover mx-auto bg-white">
                    <h2 id="detailName" class="text-xl font-black text-white mt-4 tracking-wide truncate">Nama Pegawai</h2>
                    <p id="detailPosition" class="text-[11px] font-bold text-blue-300 uppercase tracking-widest mt-1">Jabatan</p>
                    <div class="mt-3 inline-block bg-white/10 backdrop-blur-sm border border-white/20 px-3 py-1 rounded-full text-white text-xs font-bold">
                        ⏱️ <span id="detailShift">Shift</span>
                    </div>
                </div>
            </div>
            <div class="overflow-y-auto px-6 py-6 custom-scroll bg-white">
                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 mb-6 shadow-sm">
                    <div class="flex flex-col space-y-3.5 text-sm">
                        <div class="flex items-center gap-3 border-b border-gray-200/60 pb-3">
                            <div class="bg-blue-100 text-[#1868D5] p-2 rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ID PEGAWAI</p>
                                <p id="detailIdPegawai" class="font-mono font-black text-gray-800 text-sm truncate">-</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 border-b border-gray-200/60 pb-3">
                            <div class="bg-green-100 text-green-600 p-2 rounded-lg shrink-0">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
</div>
                            <div class="overflow-hidden">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">No Handphone</p>
                                <p id="detailNoHp" class="font-bold text-gray-800 truncate">-</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 border-b border-gray-200/60 pb-3">
                            <div class="bg-amber-100 text-amber-600 p-2 rounded-lg shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="overflow-hidden w-full">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</p>
                                <p id="detailEmail" class="font-bold text-gray-800 truncate w-full">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="bg-purple-100 text-purple-600 p-2 rounded-lg shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Alamat Lengkap</p>
                                <p id="detailAlamat" class="font-bold text-gray-800 text-[13px] leading-tight mt-0.5 wrap-break-words">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-2 text-center">Rekap Kehadiran Bulan Ini</h4>
                    <div class="grid grid-cols-4 gap-2 text-center">
                        <div class="bg-green-50 p-2 rounded-lg border border-green-100">
                            <p class="text-[9px] font-bold text-green-600 uppercase tracking-wider">Hadir</p>
                            <p id="detailHadir" class="text-base font-black text-green-700 mt-0.5">0</p>
                        </div>
                        <div class="bg-amber-50 p-2 rounded-lg border border-amber-100">
                            <p class="text-[9px] font-bold text-amber-600 uppercase tracking-wider">Telat</p>
                            <p id="detailLate" class="text-base font-black text-amber-700 mt-0.5">0</p>
                        </div>
                        <div class="bg-blue-50 p-2 rounded-lg border border-blue-100">
                            <p class="text-[9px] font-bold text-blue-600 uppercase tracking-wider">Izin</p>
                            <p id="detailIzin" class="text-base font-black text-blue-700 mt-0.5">0</p>
                        </div>
                        <div class="bg-red-50 p-2 rounded-lg border border-red-100">
                            <p class="text-[9px] font-bold text-red-600 uppercase tracking-wider">Alpa</p>
                            <p id="detailAlpa" class="text-base font-black text-red-700 mt-0.5">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalTambah" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-2xl shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-full">
            <div class="bg-[#0D3B66] p-5 text-center text-white relative shrink-0">
                <h2 class="text-lg font-bold">Tambah Pegawai Baru</h2>
                <button type="button" onclick="toggleModal('modalTambah')" class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="overflow-y-auto p-6 md:p-8 custom-scroll">
                <form action="/admin/pegawai" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-blue-600 text-xs font-bold mb-2 uppercase tracking-wide">ID Pegawai </label>
                            <input type="text" name="id_pegawai" value="{{ old('id_pegawai') }}" readonly placeholder="Dibuat otomatis oleh sistem" class="w-full border {{ $errors->has('id_pegawai') ? 'border-red-500 ring-1 ring-red-500' : 'border-blue-300' }} bg-blue-50/30 p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                            @error('id_pegawai')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border {{ $errors->has('name') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                            @error('name')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Jabatan</label>
                            <select name="jabatan_id" class="w-full border {{ $errors->has('jabatan_id') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->id }}" {{ old('jabatan_id') == $pos->id ? 'selected' : '' }}>{{ $pos->nama_jabatan }}</option>
                                @endforeach
                            </select>
                            @error('jabatan_id')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Jam Kerja / Shift</label>
                            <select name="shift_id" required class="w-full border {{ $errors->has('shift_id') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                                <option value="">-- Pilih Shift Kerja --</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>{{ $shift->nama_shift }} ({{ $shift->jam_masuk }} - {{ $shift->jam_pulang }})</option>
                                @endforeach
                            </select>
                            @error('shift_id')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <!-- DITAMBAHKAN REQUIRED DAN TANDA BINTANG MERAH -->
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh: budi@gmail.com" class="w-full border {{ $errors->has('email') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                            @error('email')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Username Login</label>
                            <input type="text" name="username" value="{{ old('username') }}" required class="w-full border {{ $errors->has('username') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                            @error('username')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Password Login</label>
                            <input type="password" name="password" required class="w-full border {{ $errors->has('password') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                            @error('password')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Role Akses</label>
                            <select name="role" required class="w-full border {{ $errors->has('role') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                                <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">No Handphone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full border {{ $errors->has('phone') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">
                            @error('phone')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Alamat Lengkap</label>
                            <textarea name="alamat" required rows="2" class="w-full border {{ $errors->has('alamat') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2.5 rounded-lg text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Foto Profil (Opsional)</label>
                            <input type="file" name="photo" accept="image/*" class="w-full border {{ $errors->has('photo') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-2 rounded-lg text-sm bg-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-[#1868D5] hover:file:bg-blue-100 transition">
                            @error('photo')
                                <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
                        <button type="button" onclick="toggleModal('modalTambah')" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2.5 px-6 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-md">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-full">
            <div class="bg-yellow-500 p-5 text-center text-white relative shrink-0">
                <h2 class="text-lg font-bold text-yellow-950">Edit Pekerjaan Pegawai</h2>
                <button type="button" onclick="toggleModal('modalEdit')" class="absolute top-4 right-4 text-yellow-900/70 hover:text-yellow-950 bg-black/10 hover:bg-black/20 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="overflow-y-auto p-6 md:p-8 custom-scroll">
                <form id="formEdit" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Info Pegawai (Hanya untuk dibaca) -->
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 mb-6 shadow-sm">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Mengedit Data Milik:</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div class="overflow-hidden">
                                <p id="display_edit_name" class="font-bold text-gray-800 text-sm truncate">-</p>
                                <p id="display_edit_id" class="text-[11px] font-mono font-bold text-gray-500 truncate">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 mb-6">
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Jabatan</label>
                            <select id="edit_jabatan_id" name="jabatan_id" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->id }}">{{ $pos->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Jam Kerja / Shift</label>
                            <select id="edit_shift_id" name="shift_id" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                                <option value="">-- Pilih Shift Kerja --</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Role Akses</label>
                            <select id="edit_role" name="role" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                                <option value="pegawai">Pegawai</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end pt-4 border-t border-gray-100">
                        <button type="button" onclick="toggleModal('modalEdit')" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-yellow-950 font-bold py-3 px-6 rounded-xl transition-colors shadow-md">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            const modalBox = modal.querySelector('.modal-box');

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

        function openDeleteModal(id) {
            try {
                document.getElementById('deleteForm').action = '/admin/pegawai/' + id;
                toggleModal('modalDelete');
            } catch (e) {
                alert('Gagal Hapus: ' + e.message);
            }
        }

        function openEditModal(id) {
            try {
                const btn = document.getElementById('btn-edit-' + id);
                if(!btn) { alert('Sistem gagal membaca tombol Edit'); return; }

                const form = document.getElementById('formEdit');
                if(!form) { alert('Form Edit tidak ditemukan di HTML!'); return; }

                form.action = '/admin/pegawai/' + id;

                // Tampilkan nama dan ID untuk informasi (Visual saja)
                document.getElementById('display_edit_name').innerText = btn.getAttribute('data-name') || '-';
                document.getElementById('display_edit_id').innerText = btn.getAttribute('data-idpegawai') || '-';

                // Set nilai dropdown
                const setVal = (inputId, attr) => {
                    const el = document.getElementById(inputId);
                    if (el) el.value = btn.getAttribute(attr) || '';
                };

                setVal('edit_jabatan_id', 'data-jabatan');
                setVal('edit_shift_id', 'data-shift');
                setVal('edit_role', 'data-role');

                toggleModal('modalEdit');
            } catch (e) {
                alert('Gagal Edit: ' + e.message);
            }
        }

        function openDetailModal(id) {
            try {
                const btn = document.getElementById('btn-detail-' + id);
                const user = JSON.parse(btn.getAttribute('data-user'));
                const shiftName = btn.getAttribute('data-shift');

                document.getElementById('detailName').innerText = user.name;
                document.getElementById('detailPosition').innerText = (user.jabatan && user.jabatan.nama_jabatan) ? user.jabatan.nama_jabatan : 'Tidak Ada Jabatan';
                document.getElementById('detailShift').innerText = shiftName;
                document.getElementById('detailAlamat').innerText = user.alamat ? user.alamat : '-';
                document.getElementById('detailNoHp').innerText = user.phone ? user.phone : '-';
                document.getElementById('detailEmail').innerText = user.email ? user.email : '-';
                document.getElementById('detailIdPegawai').innerText = user.id_pegawai || '-';

                document.getElementById('detailHadir').innerText = user.total_hadir ?? '0';
                document.getElementById('detailLate').innerText  = user.total_late ?? '0';
                document.getElementById('detailIzin').innerText  = user.total_izin ?? '0';
                document.getElementById('detailAlpa').innerText  = user.total_alpa ?? '0';

                const photoEl = document.getElementById('detailPhoto');
                if(user.photo) {
                    photoEl.src = '/uploads/pegawai/' + user.photo;
                } else {
                    photoEl.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name) + '&background=ffffff&color=0D3B66';
                }
                toggleModal('modalDetail');
            } catch (e) {
                alert('Gagal Detail: ' + e.message);
            }
        }

        function openResetModal(id, name) {
            try {
                // Tampilkan nama pegawai di dalam modal
                document.getElementById('resetPegawaiName').innerText = name;

                // Ubah action form ke rute reset password yang benar
                document.getElementById('resetForm').action = '/admin/pegawai/' + id + '/reset-password';

                // Buka modal
                toggleModal('modalReset');
            } catch (e) {
                alert('Gagal membuka modal reset: ' + e.message);
            }
        }
    </script>

    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toggleModal('modalTambah');
        });
    </script>
    @endif
</body>
</html>

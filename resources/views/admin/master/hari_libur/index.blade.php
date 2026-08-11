<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Hari Libur - SPPG</title>
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

    <main class="grow px-6 lg:px-8 py-10 w-full max-w-6xl mx-auto animate-fade-in-up">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Data Hari Libur</h1>
                <p class="text-gray-500 text-sm font-medium mt-1">Kelola daftar tanggal merah dan hari libur nasional perusahaan.</p>
            </div>

            <!-- Area Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <!-- Tombol Sinkronisasi Otomatis -->
                <form action="{{ route('hari_libur.sync') }}" method="POST" class="m-0 w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full group relative inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-[0_4px_15px_rgb(22,163,74,0.3)] hover:shadow-[0_6px_20px_rgb(22,163,74,0.4)] transition-all duration-300 hover:-translate-y-0.5" onclick="this.innerHTML='Menyinkronkan... <svg class=\'animate-spin h-5 w-5 ml-2\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\' fill=\'none\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg>'">
                        <svg class="w-5 h-5 transition-transform duration-500 group-hover:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Tarik Libur Otomatis
                    </button>
                </form>

                <!-- Tombol Tambah Manual -->
                <button onclick="toggleModal('modalTambah')" class="w-full sm:w-auto group relative inline-flex items-center justify-center gap-2 bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-[0_4px_15px_rgb(24,104,213,0.3)] hover:shadow-[0_6px_20px_rgb(24,104,213,0.4)] transition-all duration-300 hover:-translate-y-0.5">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Manual
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 font-semibold flex items-center gap-3 shadow-sm animate-fade-in-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 font-medium shadow-sm animate-fade-in-up">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-gray-100 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="w-full overflow-x-auto custom-scroll">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-bold text-gray-500">
                        <tr>
                            <th class="py-4 px-6 w-16 text-center">No</th>
                            <th class="py-4 px-6 text-center">Tanggal Libur</th>
                            <th class="py-4 px-6">Keterangan / Nama Libur</th>
                            <th class="py-4 px-6 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($holidays as $index => $h)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="py-4 px-6 text-center font-semibold text-gray-600">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-600 font-bold text-xs border border-red-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <!-- PERBAIKAN ERROR: Menggunakan $h->tanggal -->
                                    {{ \Carbon\Carbon::parse($h->tanggal)->translatedFormat('d F Y') }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <!-- PERBAIKAN ERROR: Menggunakan $h->keterangan -->
                                <p class="font-bold text-gray-900 text-base">{{ $h->keterangan }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <!-- PERBAIKAN ERROR: Mengirim $h->tanggal dan $h->keterangan -->
                                    <button onclick="openEditModal({{ $h->id }}, '{{ $h->tanggal }}', '{{ addslashes($h->keterangan) }}')" class="p-2 bg-gray-100 hover:bg-yellow-500 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button type="button" onclick="openDeleteModal({{ $h->id }}, '{{ addslashes($h->keterangan) }}')" class="p-2 bg-gray-100 hover:bg-red-600 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400 font-medium text-base">Belum ada data hari libur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Tambah Data -->
    <div id="modalTambah" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="bg-[#0D3B66] p-5 text-center text-white relative shrink-0">
                <h2 class="text-lg font-bold">Tambah Hari Libur Manual</h2>
                <button type="button" onclick="toggleModal('modalTambah')" class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 md:p-8">
                <form action="{{ route('hari_libur.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Tanggal Libur</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal') }}" required class="w-full border {{ $errors->has('tanggal') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition shadow-sm">
                        @error('tanggal')
                            <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-8">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Keterangan / Nama Libur</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}" required placeholder="Contoh: Cuti Bersama Perusahaan" class="w-full border {{ $errors->has('keterangan') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition shadow-sm">
                        @error('keterangan')
                            <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex gap-3 justify-end border-t border-gray-100 pt-5">
                        <button type="button" onclick="toggleModal('modalTambah')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="w-full bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors shadow-md">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div id="modalEdit" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="bg-yellow-500 p-5 text-center text-white relative shrink-0">
                <h2 class="text-lg font-bold text-yellow-950">Edit Hari Libur</h2>
                <button type="button" onclick="toggleModal('modalEdit')" class="absolute top-4 right-4 text-yellow-900/70 hover:text-yellow-950 bg-black/10 hover:bg-black/20 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 md:p-8">
                <form id="formEdit" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-5">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Tanggal Libur</label>
                        <input type="date" id="editDate" name="tanggal" required class="w-full border {{ $errors->has('tanggal') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                        @error('tanggal')
                            <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-8">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Keterangan / Nama Libur</label>
                        <input type="text" id="editDescription" name="keterangan" required class="w-full border {{ $errors->has('keterangan') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                        @error('keterangan')
                            <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex gap-3 justify-end border-t border-gray-100 pt-5">
                        <button type="button" onclick="toggleModal('modalEdit')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-yellow-950 font-bold py-3 rounded-xl transition-colors shadow-md">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Data -->
    <div id="modalHapus" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5 border-[6px] border-red-50/50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Hapus Hari Libur?</h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Anda yakin ingin menghapus data libur <br><span id="hapusKeterangan" class="font-bold text-gray-800 text-base"></span>? <br>
                </p>
            </div>

            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex gap-3 justify-center">
                <button type="button" onclick="toggleModal('modalHapus')" class="w-full bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 font-bold py-3 rounded-xl transition-colors shadow-sm">Batal</button>
                <form id="formHapus" action="" method="POST" class="w-full m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors shadow-md">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="w-full px-6 lg:px-8 py-6 mt-auto border-t border-gray-200/60 bg-white">
        <p class="text-[13px] md:text-sm font-bold text-gray-400 text-center lg:text-left">
            © 2026 <span class="text-[#0D3B66]">SPPG Langensari Tarogong Kaler</span>. All Rights Reserved.
        </p>
    </footer>

    <script>
        @if($errors->any())
            document.addEventListener("DOMContentLoaded", function() {
                toggleModal('modalTambah');
            });
        @endif

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalBox = modal.querySelector('.modal-box');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => { modal.classList.remove('opacity-0'); modalBox.classList.remove('scale-95'); }, 10);
            } else {
                modal.classList.add('opacity-0'); modalBox.classList.add('scale-95');
                setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
            }
        }

        function openEditModal(id, date, description) {
            document.getElementById('formEdit').action = '/admin/master/hari-libur/' + id;
            document.getElementById('editDate').value = date;
            document.getElementById('editDescription').value = description;
            toggleModal('modalEdit');
        }

        function openDeleteModal(id, description) {
            document.getElementById('formHapus').action = '/admin/master/hari-libur/' + id;
            document.getElementById('hapusKeterangan').innerText = description;
            toggleModal('modalHapus');
        }
    </script>
</body>
</html>

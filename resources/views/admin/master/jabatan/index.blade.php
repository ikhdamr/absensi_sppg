<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Jabatan - SPPG</title>
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

    <main class="grow px-6 lg:px-8 py-10 w-full max-w-5xl mx-auto animate-fade-in-up">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Data Jabatan / Posisi</h1>
                <p class="text-gray-500 text-sm font-medium mt-1">Kelola daftar nama jabatan yang ada di perusahaan.</p>
            </div>
            <button onclick="toggleModal('modalTambah')" class="w-full sm:w-auto group relative inline-flex items-center justify-center gap-2 bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-[0_4px_15px_rgb(24,104,213,0.3)] hover:shadow-[0_6px_20px_rgb(24,104,213,0.4)] transition-all duration-300 hover:-translate-y-0.5">
                <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                Tambah Data
            </button>
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
                            <th class="py-4 px-6 w-20 text-center">No</th>
                            <th class="py-4 px-6">Nama Jabatan</th>
                            <th class="py-4 px-6 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse(isset($positions) ? $positions : (isset($jabatan) ? $jabatan : []) as $index => $item)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="py-4 px-6 text-center font-semibold text-gray-600">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1868D5] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div> <!-- PERBAIKAN: Tag penutup div ikon yang sebelumnya hilang -->
                                    <p class="font-bold text-gray-900 text-base">{{ $item->nama_jabatan }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->nama_jabatan) }}')" class="p-2 bg-gray-100 hover:bg-yellow-500 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button type="button" onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama_jabatan) }}')" class="p-2 bg-gray-100 hover:bg-red-600 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-gray-400 font-medium text-base">Belum ada data jabatan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- MODAL TAMBAH -->
    <div id="modalTambah" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="bg-[#0D3B66] p-5 text-center text-white relative shrink-0">
                <h2 class="text-lg font-bold">Tambah Jabatan Baru</h2>
                <button type="button" onclick="toggleModal('modalTambah')" class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 md:p-8">
                <form action="{{ route('jabatan.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan') }}" required placeholder="Contoh: IT Support" class="w-full border {{ $errors->has('nama_jabatan') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition shadow-sm">
                        @error('nama_jabatan')
                            <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex gap-3 justify-end pt-2">
                        <button type="button" onclick="toggleModal('modalTambah')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="w-full bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors shadow-md">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="modalEdit" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="bg-yellow-500 p-5 text-center text-white relative shrink-0">
                <h2 class="text-lg font-bold text-yellow-950">Edit Jabatan</h2>
                <button type="button" onclick="toggleModal('modalEdit')" class="absolute top-4 right-4 text-yellow-900/70 hover:text-yellow-950 bg-black/10 hover:bg-black/20 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 md:p-8">
                <form id="formEdit" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-6">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Nama Jabatan</label>
                        <input type="text" id="edit_name" name="nama_jabatan" required class="w-full border {{ $errors->has('nama_jabatan') ? 'border-red-500 ring-1 ring-red-500' : 'border-gray-300' }} p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                        @error('nama_jabatan')
                            <span class="text-red-500 text-[11px] font-bold mt-1 block">⚠️ {{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex gap-3 justify-end pt-2">
                        <button type="button" onclick="toggleModal('modalEdit')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-yellow-950 font-bold py-3 rounded-xl transition-colors shadow-md">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS -->
    <div id="modalDelete" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5 border-[6px] border-red-50/50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Hapus Jabatan?</h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Anda yakin ingin menghapus jabatan <br><span id="deleteName" class="font-bold text-gray-800 text-base"></span>? <br>
                </p>
            </div>
            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex gap-3 justify-center">
                <button type="button" onclick="toggleModal('modalDelete')" class="w-full bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 font-bold py-3 rounded-xl transition-colors shadow-sm">Batal</button>
                <form id="formDelete" method="POST" class="w-full m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition-colors shadow-md">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full px-6 lg:px-8 py-6 mt-auto border-t border-gray-200/60 bg-white">
        <p class="text-[13px] md:text-sm font-bold text-gray-400 text-center lg:text-left">
            © 2026 <span class="text-[#0D3B66]">SPPG Langensari Tarogong Kaler</span>. All Rights Reserved.
        </p>
    </footer>

    <!-- SCRIPT -->
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

        function openEditModal(id, name) {
            document.getElementById('formEdit').action = '/admin/master/jabatan/' + id;
            document.getElementById('edit_name').value = name;
            toggleModal('modalEdit');
        }

        function openDeleteModal(id, name) {
            document.getElementById('formDelete').action = '/admin/master/jabatan/' + id;
            document.getElementById('deleteName').innerText = name;
            toggleModal('modalDelete');
        }
    </script>
</body>
</html>

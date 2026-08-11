<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Jam Kerja - SPPG</title>
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
                <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Data Jam Kerja / Shift</h1>
                <p class="text-gray-500 text-sm font-medium mt-1">Kelola aturan waktu masuk, pulang, dan batas toleransi keterlambatan.</p>
            </div>
            <button onclick="toggleModal('modalTambah')" class="group relative inline-flex items-center justify-center gap-2 bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-[0_4px_15px_rgb(24,104,213,0.3)] hover:shadow-[0_6px_20px_rgb(24,104,213,0.4)] transition-all duration-300 hover:-translate-y-0.5">
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
                            <th class="py-4 px-6 w-16 text-center">No</th>
                            <th class="py-4 px-6">Nama Jam Kerja (Shift)</th>
                            <th class="py-4 px-6 text-center">Jam Masuk</th>
                            <th class="py-4 px-6 text-center">Jam Pulang</th>
                            <th class="py-4 px-6 text-center">Batas Toleransi</th>
                            <th class="py-4 px-6 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($shifts as $index => $item)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="py-4 px-6 text-center font-semibold text-gray-600">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#1868D5] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="font-bold text-gray-900 text-base">{{ $item->nama_shift }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 text-green-700 font-bold text-xs border border-green-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                    {{ substr($item->jam_masuk, 0, 5) }} WIB
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-50 text-red-700 font-bold text-xs border border-red-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ substr($item->jam_pulang, 0, 5) }} WIB
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 font-bold text-xs border border-amber-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    {{ substr($item->toleransi_keterlambatan, 0, 5) }} WIB
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex justify-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->nama_shift) }}', '{{ substr($item->jam_masuk, 0, 5) }}', '{{ substr($item->jam_pulang, 0, 5) }}', '{{ substr($item->toleransi_keterlambatan, 0, 5) }}')" class="p-2 bg-gray-100 hover:bg-yellow-500 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button type="button" onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->nama_shift) }}')" class="p-2 bg-gray-100 hover:bg-red-600 hover:text-white text-gray-600 rounded-lg transition-colors shadow-sm" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400 font-medium text-base">Belum ada data jam kerja.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="modalTambah" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-lg shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="bg-[#0D3B66] p-5 text-center text-white relative shrink-0">
                <h2 class="text-lg font-bold">Tambah Jam Kerja / Shift</h2>
                <button type="button" onclick="toggleModal('modalTambah')" class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 md:p-8">
                <form action="/admin/master/jam-kerja" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Nama Jam Kerja (Shift)</label>
                        <input type="text" name="nama_shift" value="{{ old('name') }}" required placeholder="Contoh: Shift Siang" class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition shadow-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Jam Masuk</label>
                            <input type="time" name="jam_masuk" value="{{ old('start_time') }}" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition shadow-sm">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Jam Pulang</label>
                            <input type="time" name="jam_pulang" value="{{ old('end_time') }}" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Toleransi Keterlambatan</label>
                        <input type="time" name="toleransi_keterlambatan" value="{{ old('toleransi_keterlambatan') }}" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-[#1868D5] outline-none transition shadow-sm">
                        <p class="text-xs text-gray-400 mt-1.5 font-medium">*Melewati jam toleransi ini akan dianggap Alpa/Terlambat.</p>
                    </div>

                    <div class="flex gap-3 justify-end border-t border-gray-100 pt-5">
                        <button type="button" onclick="toggleModal('modalTambah')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="w-full bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors shadow-md">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalEdit" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-lg shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="bg-yellow-500 p-5 text-center text-white relative shrink-0">
                <h2 class="text-lg font-bold text-yellow-950">Edit Jam Kerja / Shift</h2>
                <button type="button" onclick="toggleModal('modalEdit')" class="absolute top-4 right-4 text-yellow-900/70 hover:text-yellow-950 bg-black/10 hover:bg-black/20 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 md:p-8">
                <form id="formEdit" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-5">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Nama Jam Kerja (Shift)</label>
                        <input type="text" id="edit_name" name="nama_shift" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Jam Masuk</label>
                            <input type="time" id="edit_start_time" name="jam_masuk" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Jam Pulang</label>
                            <input type="time" id="edit_end_time" name="jam_pulang" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">Toleransi Keterlambatan</label>
                        <input type="time" id="edit_toleransi_keterlambatan" name="toleransi_keterlambatan" required class="w-full border border-gray-300 p-3 rounded-xl text-sm focus:ring-2 focus:ring-yellow-500 outline-none transition shadow-sm">
                    </div>

                    <div class="flex gap-3 justify-end border-t border-gray-100 pt-5">
                        <button type="button" onclick="toggleModal('modalEdit')" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 rounded-xl transition-colors">Batal</button>
                        <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-yellow-950 font-bold py-3 rounded-xl transition-colors shadow-md">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalDelete" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm justify-center items-center px-4 transition-opacity duration-300 opacity-0 py-10">
        <div class="modal-box bg-white rounded-2xl w-full max-w-md shadow-2xl relative transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5 border-[6px] border-red-50/50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Hapus Jam Kerja?</h2>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Anda yakin ingin menghapus data shift <br><span id="deleteName" class="font-bold text-gray-800 text-base"></span>? <br>
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

    <footer class="w-full px-6 lg:px-8 py-6 mt-auto border-t border-gray-200/60 bg-white">
        <p class="text-[13px] md:text-sm font-bold text-gray-400 text-center lg:text-left">
            © 2026 <span class="text-[#0D3B66]">SPPG Langensari Tarogong Kaler</span>. All Rights Reserved.
        </p>
    </footer>

    <script>
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

        // Variabel penampung nilai di JS juga diubah sesuai ID
        function openEditModal(id, name, start_time, end_time, toleransi_keterlambatan) {
            document.getElementById('formEdit').action = '/admin/master/jam-kerja/' + id; // Ganti URL ini jika salah
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_start_time').value = start_time;
            document.getElementById('edit_end_time').value = end_time;
            document.getElementById('edit_toleransi_keterlambatan').value = toleransi_keterlambatan;
            toggleModal('modalEdit');
        }

        function openDeleteModal(id, name) {
            document.getElementById('formDelete').action = '/admin/master/jam-kerja/' + id; // Ganti URL ini jika salah
            document.getElementById('deleteName').innerText = name;
            toggleModal('modalDelete');
        }
    </script>
</body>
</html>

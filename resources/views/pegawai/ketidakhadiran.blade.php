<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Izin/Cuti - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans text-gray-800 antialiased">

    @include('pegawai.layouts.navbar')

    <main class="grow px-4 sm:px-8 py-10 w-full max-w-3xl mx-auto">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Form Pengajuan Ketidakhadiran</h1>
            <p class="text-gray-500 font-medium mt-2">Silakan isi formulir di bawah ini untuk mengajukan Cuti, Izin, atau Sakit.</p>
        </div>

        <!-- Notifikasi Alert Sukses -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm animate-[pulse_0.5s_ease-in-out]">
                <!-- Ikon Centang -->
                <svg class="w-6 h-6 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-bold text-sm">Berhasil!</h3>
                    <p class="text-sm opacity-90 mt-0.5">{{ session('success') }}</p>
                </div>
                <!-- Tombol Silang untuk Menutup -->
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex h-8 w-8" onclick="this.parentElement.style.display='none';">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
        @endif

        <!-- Notifikasi Alert Error -->
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
            <strong class="font-bold">Terjadi Kesalahan!</strong>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 md:p-8">
            <form action="{{ route('pegawai.ketidakhadiran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Tanggal Izin <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_izin" required min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition bg-gray-50 text-gray-800 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Jenis Ketidakhadiran <span class="text-red-500">*</span></label>
                        <select name="kategori" required class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition bg-white font-medium text-gray-800">
                            <option value="" disabled selected>-- Pilih Keterangan --</option>
                            <option value="Cuti">Cuti</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block font-bold text-gray-700 mb-2">Deskripsi / Alasan <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="4" required placeholder="Tuliskan alasan lengkap Anda di sini..." class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition resize-none bg-gray-50"></textarea>
                </div>

                <div class="mb-8">
                    <label class="block font-bold text-gray-700 mb-2">Surat Keterangan <span class="text-gray-400 font-normal text-sm">(Opsional / Wajib jika Sakit)</span></label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                        <!-- TAMBAHAN: id="file_bukti" -->
                        <input type="file" name="file_bukti" id="file_bukti" accept=".jpg,.jpeg,.png,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>

                        <!-- TAMBAHAN: id="nama_file" -->
                        <p id="nama_file" class="text-sm text-gray-600 font-medium">Klik atau seret file ke sini untuk mengunggah.</p>

                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, atau PDF (Maks. 2MB)</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                    <a href="{{ route('pegawai.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-lg transition-colors">Batal</a>
                    <button type="submit" class="bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors shadow-md">Ajukan Sekarang</button>
                </div>
            </form>
        </div>

    </main>

    <!-- SCRIPT UNTUK MENGUBAH TEKS UPLOAD FILE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file_bukti');
            const fileNameDisplay = document.getElementById('nama_file');

            if (fileInput) {
                fileInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        // Ubah teks jadi nama file berwarna biru
                        fileNameDisplay.innerHTML = `<span class="text-[#1868D5] font-bold">${file.name}</span>`;
                    } else {
                        // Kembali ke default jika batal
                        fileNameDisplay.innerHTML = 'Klik atau seret file ke sini untuk mengunggah.';
                    }
                });
            }
        });
    </script>

</body>
</html>

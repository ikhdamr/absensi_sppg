<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Pengajuan Izin/Cuti - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans text-gray-800 antialiased">

    @include('pegawai.layouts.navbar')

    <main class="grow px-4 sm:px-8 py-10 w-full max-w-3xl mx-auto">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Ubah Pengajuan Ketidakhadiran</h1>
            <p class="text-gray-500 font-medium mt-2">Anda dapat mengubah detail pengajuan sebelum diproses oleh Admin.</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative mb-6">
            <strong class="font-bold">Terjadi Kesalahan!</strong>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 md:p-8">
            <!-- PENTING: Method-nya menggunakan PUT karena ini proses Edit (Update) -->
            <form action="{{ route('pegawai.ketidakhadiran.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Tanggal Izin <span class="text-red-500">*</span></label>
                        <!-- value diisi dengan data lama dari database -->
                        <input type="date" name="tanggal_izin" required value="{{ $pengajuan->tanggal_izin }}" min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-gray-50 text-gray-800 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Jenis Ketidakhadiran <span class="text-red-500">*</span></label>
                        <select name="kategori" required class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white font-medium text-gray-800">
                            <!-- Opsi akan otomatis terpilih sesuai data lama -->
                            <option value="Cuti" {{ $pengajuan->kategori == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="Sakit" {{ $pengajuan->kategori == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Izin" {{ $pengajuan->kategori == 'Izin' ? 'selected' : '' }}>Izin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block font-bold text-gray-700 mb-2">Deskripsi / Alasan <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="4" required class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none bg-gray-50">{{ $pengajuan->deskripsi }}</textarea>
                </div>

                <div class="mb-8">
                    <label class="block font-bold text-gray-700 mb-2">Surat Keterangan <span class="text-gray-400 font-normal text-sm">(Opsional / Wajib jika Sakit)</span></label>

                    @if($pengajuan->file_bukti)
                        <div class="mb-3 p-3 bg-blue-50 text-blue-700 rounded-lg text-sm border border-blue-100 flex items-center justify-between">
                            <span>Anda sudah mengunggah file bukti sebelumnya.</span>
                            <a href="{{ asset('storage/' . $pengajuan->file_bukti) }}" target="_blank" class="font-bold underline">Lihat File</a>
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition cursor-pointer relative">
                        <!-- TAMBAHAN: id="file_bukti" -->
                        <input type="file" name="file_bukti" id="file_bukti" accept=".jpg,.jpeg,.png,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>

                        <!-- TAMBAHAN: id="nama_file" -->
                        <p id="nama_file" class="text-sm text-gray-600 font-medium">Klik atau seret file baru jika ingin mengganti file lama.</p>

                        <p class="text-xs text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah file bukti.</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                    <a href="{{ route('pegawai.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-lg transition-colors">Batal Ubah</a>
                    <button type="submit" class="bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition-colors">Simpan Perubahan</button>
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
                        fileNameDisplay.innerHTML = 'Klik atau seret file baru jika ingin mengganti file lama.';
                    }
                });
            }
        });
    </script>

</body>
</html>

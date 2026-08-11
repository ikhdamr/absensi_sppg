<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans text-gray-800 antialiased">

    @include('pegawai.layouts.navbar')

    <main class="grow px-4 sm:px-8 py-10 w-full max-w-3xl mx-auto">

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-[#0D3B66]">Pengaturan Profil</h1>
            <p class="text-gray-500 font-medium mt-2">Perbarui informasi pribadi dan foto profil Anda di sini.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-start gap-3 shadow-sm animate-[pulse_0.5s_ease-in-out]">
                <svg class="w-6 h-6 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-bold text-sm">Berhasil!</h3>
                    <p class="text-sm opacity-90 mt-0.5">{{ session('success') }}</p>
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-100 inline-flex h-8 w-8" onclick="this.parentElement.style.display='none';"><span class="sr-only">Close</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative mb-6">
                <strong class="font-bold">Gagal menyimpan!</strong>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] overflow-hidden">
            <form action="{{ route('pegawai.profil.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
                @csrf
                @method('PUT')

                <!-- Bagian Upload Foto -->
                <div class="flex flex-col sm:flex-row items-center gap-6 mb-8 pb-8 border-b border-gray-100">
                    <div class="relative shrink-0">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md">
                        @else
                            <div class="w-28 h-28 rounded-full bg-blue-100 flex items-center justify-center border-4 border-white shadow-md text-blue-600 font-bold text-3xl">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <label for="foto" class="absolute bottom-0 right-0 bg-[#1868D5] text-white p-2 rounded-full cursor-pointer hover:bg-blue-700 shadow-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </label>
                        <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png" class="hidden">
                    </div>
                    <div class="text-center sm:text-left">
                        <h3 class="font-bold text-lg text-gray-800">Ubah Foto Profil</h3>
                        <p class="text-sm text-gray-500">Format: JPG, PNG. Maksimal ukuran 2MB.</p>
                    </div>
                </div>

                <!-- Bagian Input Data -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white font-medium text-gray-800">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white font-medium text-gray-800">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white font-medium text-gray-800">
                            <option value="" disabled {{ !$user->jenis_kelamin ? 'selected' : '' }}>-- Pilih --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-2">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 08123456789" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 bg-white font-medium text-gray-800">
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-8 mt-8">
                    <a href="{{ route('pegawai.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-lg transition-colors">Batal</a>
                    <button type="submit" class="bg-[#1868D5] hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition-colors">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

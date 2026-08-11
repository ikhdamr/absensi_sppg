<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Absensi SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .bg-animated-gradient {
            background-size: 200% 200%;
            animation: gradientMove 12s ease infinite;
        }

        @keyframes slowZoom {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .animate-slow-zoom {
            animation: slowZoom 25s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-[#F6F8FC] antialiased text-gray-800 selection:bg-blue-200 selection:text-blue-900 h-screen overflow-hidden">

<div class="h-screen w-full grid grid-cols-1 lg:grid-cols-2">

    <div class="flex flex-col justify-between px-8 py-6 w-full h-full relative bg-white lg:bg-transparent">

        <div class="grow flex flex-col justify-center items-center">

            <div class="w-full max-w-105 mx-auto z-10">

                <div class="animate-fade-in-up flex items-center justify-center gap-4 mb-8">
                    <img src="{{ asset('assets/logos/logo-sppg.png') }}" alt="Logo SPPG" class="w-20 drop-shadow-md hover:scale-105 transition-transform duration-500">
                    <div class="text-[#0D3B66]">
                        <h2 class="font-extrabold text-lg tracking-wide leading-tight">SPPG LANGENSARI</h2>
                        <p class="font-bold text-sm leading-tight">TAROGONG KALER</p>
                        <p class="font-bold text-sm leading-tight">KABUPATEN GARUT</p>
                    </div>
                </div>

                <div class="animate-fade-in-up delay-100 bg-white rounded-3xl shadow-[0_15px_40px_rgba(13,59,102,0.08)] p-8 sm:p-10 transition-all duration-300 hover:shadow-[0_20px_50px_rgba(13,59,102,0.12)] border border-gray-50">
                    <h1 class="text-center text-2xl font-bold text-[#0D3B66] mb-8">
                        Login to your account
                    </h1>

                    <form action="{{ url('/login') }}" method="POST">
                        @csrf
                        @error('username')
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl text-sm font-medium animate-fade-in-up">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="mb-5 group">
                            <label class="block font-semibold text-gray-700 text-sm mb-2 group-focus-within:text-blue-600 transition-colors">
                                Username
                            </label>
                            <input type="text" name="username" placeholder="Masukkan Username" required
                                class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 outline-none transition-all duration-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/15 focus:-translate-y-1 text-gray-700 placeholder-gray-400">
                        </div>

                        <div class="mb-8 group">
                            <label class="block font-semibold text-gray-700 text-sm mb-2 group-focus-within:text-blue-600 transition-colors">
                                Password
                            </label>

                            <!-- BUNGKUSAN PASSWORD DENGAN IKON MATA -->
                            <div class="relative w-full">
                                <input type="password" id="password" name="password" placeholder="Masukkan Password" required
                                    class="peer w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 pr-12 outline-none transition-all duration-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/15 focus:-translate-y-1 text-gray-700 placeholder-gray-400">

                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-600 transition-all duration-300 peer-focus:-translate-y-1 focus:outline-none">
                                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <!-- Default: Mata Terbuka (Password tertutup) -->
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full rounded-xl bg-linear-to-r from-[#1868D5] to-[#1254B0] text-white py-3.5 text-lg font-bold transition-all duration-300 shadow-[0_8px_20px_rgba(24,104,213,0.25)] hover:shadow-[0_12px_25px_rgba(24,104,213,0.35)] hover:scale-[1.02] active:scale-[0.98]">
                            Sign in
                        </button>

                        <div class="text-right mt-5">
                            <a href="{{ url('/lupa-password') }}" class="inline-block text-[#1868D5] font-semibold text-sm hover:text-[#1254B0] hover:underline transition-all hover:-translate-y-0.5">
                                Lupa Password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="w-full text-center animate-fade-in-up delay-200 pb-2">
            <p class="text-xs sm:text-sm font-medium text-gray-400">
                © 2026 SPPG Langensari Tarogong Kaler, Kabupaten Garut. All Rights Reserved.
            </p>
        </footer>

    </div>

    <div class="hidden lg:flex relative flex-col justify-center items-center overflow-hidden h-full">

        <img src="{{ asset('assets/images/login-bg.jpeg') }}" class="absolute inset-0 w-full h-full object-cover animate-slow-zoom">

        <div class="absolute inset-0 bg-linear-to-br from-[#12457E]/95 via-[#1A5296]/80 to-[#0A2E59]/95 mix-blend-multiply bg-animated-gradient"></div>

        <div class="relative z-10 flex flex-col justify-center items-center h-full px-12 xl:px-16 text-white w-full">

            <div class="text-center mb-12 animate-fade-in-up delay-100">
                <h1 class="text-3xl xl:text-4xl font-extrabold leading-tight drop-shadow-xl">
                    Selamat Datang <br>
                    <span class="text-blue-100 font-bold">di Sistem Absensi Pegawai</span>
                </h1>
            </div>

            <div class="w-full max-w-2xl flex flex-col items-center">
                <div class="animate-float bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 grid grid-cols-2 md:grid-cols-4 gap-4 w-full text-center shadow-[0_20px_50px_rgba(0,0,0,0.2)]">

                    <div class="flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-white/20 group pb-4 md:pb-0">
                        <h2 class="text-3xl font-extrabold text-[#74B3FF] drop-shadow-md transition-transform duration-300 group-hover:scale-110">3.500+</h2>
                        <p class="text-xs font-medium mt-1 leading-snug text-gray-200">Porsi Makanan<br>Disalurkan</p>
                    </div>

                    <div class="flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-white/20 group pb-4 md:pb-0">
                        <h2 class="text-3xl font-extrabold text-[#74B3FF] drop-shadow-md transition-transform duration-300 group-hover:scale-110">15+</h2>
                        <p class="text-xs font-medium mt-1 leading-snug text-gray-200">Sekolah<br>Mitra</p>
                    </div>

                    <div class="flex flex-col items-center justify-center border-r-0 md:border-r border-white/20 group">
                        <h2 class="text-3xl font-extrabold text-[#74B3FF] drop-shadow-md transition-transform duration-300 group-hover:scale-110">1.250+</h2>
                        <p class="text-xs font-medium mt-1 leading-snug text-gray-200">Penerima<br>Manfaat</p>
                    </div>

                    <div class="flex flex-col items-center justify-center group">
                        <h2 class="text-3xl font-extrabold text-[#74B3FF] drop-shadow-md transition-transform duration-300 group-hover:scale-110">35+</h2>
                        <p class="text-xs font-medium mt-1 leading-snug text-gray-200">Tenaga<br>Pelaksana</p>
                    </div>

                </div>

                <p class="animate-fade-in-up delay-300 mt-8 text-sm xl:text-base font-medium text-center leading-relaxed text-gray-100 max-w-xl drop-shadow-md">
                    Kelola kehadiran karyawan secara cepat, akurat, dan terintegrasi.
                    Mendukung pencatatan absensi berbasis RFID untuk meningkatkan
                    efisiensi dan kedisiplinan kerja.
                </p>
            </div>
        </div>
    </div>

</div>

<!-- SCRIPT UNTUK FUNGSI IKON MATA -->
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        // Cek tipe input saat ini
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Ubah SVG ikon (Mata Terbuka / Tertutup)
        if (type === 'password') {
            // Ikon Mata Normal
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        } else {
            // Ikon Mata Dicoret
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        }
    });
</script>

</body>
</html>

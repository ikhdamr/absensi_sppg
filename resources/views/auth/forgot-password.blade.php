<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        .animate-float { animation: float 4s ease-in-out infinite; }
        @keyframes slowZoom { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        .animate-slow-zoom { animation: slowZoom 25s ease-in-out infinite; }
        @keyframes gradientMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .bg-animated-gradient { background-size: 200% 200%; animation: gradientMove 12s ease infinite; }
    </style>
</head>

<body class="bg-[#F6F8FC] antialiased text-gray-800 h-screen overflow-hidden">

<div class="h-screen w-full grid grid-cols-1 lg:grid-cols-2">

    <!-- SISI KIRI: FORM DINAMIS -->
    <div class="flex flex-col justify-between px-8 py-6 w-full h-full relative bg-white lg:bg-transparent">
        <div class="grow flex flex-col justify-center items-center">
            <div class="w-full max-w-105 mx-auto z-10">

                <!-- Logo Atas -->
                <div class="animate-fade-in-up flex items-center justify-center gap-4 mb-8">
                    <img src="{{ asset('assets/logos/logo-sppg.png') }}" alt="Logo SPPG" class="w-20 drop-shadow-md">
                    <div class="text-[#0D3B66]">
                        <h2 class="font-extrabold text-lg tracking-wide leading-tight">SPPG LANGENSARI</h2>
                        <p class="font-bold text-sm leading-tight">TAROGONG KALER</p>
                    </div>
                </div>

                <!-- Kotak Form Utama -->
                <div class="animate-fade-in-up delay-100 bg-white rounded-3xl shadow-[0_15px_40px_rgba(13,59,102,0.08)] p-8 sm:p-10 border border-gray-50">

                    <!-- Notifikasi Global -->
                    @if(session('success'))
                        <div class="mb-5 bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-xl text-sm font-semibold animate-fade-in-up">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- ============================================== -->
                    <!-- TAHAP 3: FORM BUAT PASSWORD BARU -->
                    <!-- ============================================== -->
                    @if(session('step') == 'reset')
                        <h1 class="text-center text-2xl font-bold text-[#0D3B66] mb-2">Buat Password Baru</h1>
                        <p class="text-center text-gray-500 text-sm mb-6">Silakan buat password baru untuk akun Anda.</p>

                        <form action="{{ url('/lupa-password/reset') }}" method="POST">
                            @csrf
                            @error('password')
                                <div class="mb-4 text-red-500 text-xs font-bold">{{ $message }}</div>
                            @enderror

                            <!-- PASSWORD BARU + MATA -->
                            <div class="mb-4 group">
                                <label class="block font-semibold text-gray-700 text-sm mb-2 group-focus-within:text-blue-600 transition-colors">Password Baru</label>
                                <div class="relative w-full">
                                    <input type="password" id="new_password" name="password" required
                                        class="peer w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 pr-12 outline-none transition-all duration-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/15 focus:-translate-y-1 text-gray-700">
                                    <button type="button" onclick="toggleVisibility('new_password', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-600 transition-all duration-300 peer-focus:-translate-y-1 focus:outline-none">
                                        <svg id="eyeIcon1" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- KONFIRMASI PASSWORD + MATA -->
                            <div class="mb-6 group">
                                <label class="block font-semibold text-gray-700 text-sm mb-2 group-focus-within:text-blue-600 transition-colors">Konfirmasi Password Baru</label>
                                <div class="relative w-full">
                                    <input type="password" id="confirm_password" name="password_confirmation" required
                                        class="peer w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 pr-12 outline-none transition-all duration-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/15 focus:-translate-y-1 text-gray-700">
                                    <button type="button" onclick="toggleVisibility('confirm_password', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-600 transition-all duration-300 peer-focus:-translate-y-1 focus:outline-none">
                                        <svg id="eyeIcon2" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="w-full rounded-xl bg-linear-to-r from-[#1868D5] to-[#1254B0] text-white py-3.5 text-lg font-bold shadow-[0_8px_20px_rgba(24,104,213,0.25)] hover:-translate-y-0.5 hover:scale-[1.02] transition-all duration-300">
                                Simpan Password Baru
                            </button>
                        </form>

                    <!-- ============================================== -->
                    <!-- TAHAP 2: FORM MASUKKAN OTP -->
                    <!-- ============================================== -->
                    @elseif(session('step') == 'otp')
                        <h1 class="text-center text-2xl font-bold text-[#0D3B66] mb-2">Verifikasi OTP</h1>
                        <p class="text-center text-gray-500 text-sm mb-6">Kami telah mengirim 6 digit kode OTP ke email <span class="font-bold text-gray-800">{{ session('reset_email') }}</span>. Kode berlaku 15 menit.</p>

                        <form action="{{ url('/lupa-password/verify-otp') }}" method="POST">
                            @csrf
                            @error('otp')
                                <div class="mb-4 text-red-500 text-xs font-bold text-center">{{ $message }}</div>
                            @enderror

                            <div class="mb-6 text-center">
                                <input type="text" name="otp" required maxlength="6" autocomplete="off" placeholder="••••••"
                                    class="w-full text-center tracking-[1em] text-2xl font-black rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-4 outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/15 text-gray-800 uppercase">
                            </div>

                            <button type="submit" class="w-full rounded-xl bg-linear-to-r from-[#1868D5] to-[#1254B0] text-white py-3.5 text-lg font-bold shadow-[0_8px_20px_rgba(24,104,213,0.25)] hover:-translate-y-0.5 hover:scale-[1.02] transition-all duration-300">
                                Verifikasi Kode
                            </button>
                        </form>

                        <!-- KIRIM ULANG OTP -->
                        <div class="mt-6 text-center flex items-center justify-center gap-1 text-sm text-gray-500">
                            <span>Tidak menerima kode?</span>
                            <form action="{{ route('lupa-password.resend-otp') }}" method="POST" class="inline m-0 p-0">
                                @csrf
                                <input type="hidden" name="email" value="{{ session('reset_email') }}">
                                <button type="submit" class="text-[#1868D5] font-bold hover:underline bg-transparent border-none p-0 cursor-pointer">
                                    Kirim Ulang OTP
                                </button>
                            </form>
                        </div>

                    <!-- ============================================== -->
                    <!-- TAHAP 1: FORM INPUT EMAIL -->
                    <!-- ============================================== -->
                    @else
                        <h1 class="text-center text-2xl font-bold text-[#0D3B66] mb-2">Lupa Password?</h1>
                        <p class="text-center text-gray-500 text-sm mb-6">Masukkan email yang tertaut dengan akun Anda. Kami akan mengirimkan kode OTP untuk mereset password.</p>

                        <form action="{{ url('/lupa-password/send-otp') }}" method="POST">
                            @csrf
                            @error('email')
                                <div class="mb-4 text-red-500 text-xs font-bold">{{ $message }}</div>
                            @enderror

                            <div class="mb-6">
                                <label class="block font-semibold text-gray-700 text-sm mb-2">Alamat Email</label>
                                <input type="email" name="email" placeholder="contoh: email@anda.com" required
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3.5 outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/15">
                            </div>

                            <button type="submit" class="w-full rounded-xl bg-linear-to-r from-[#1868D5] to-[#1254B0] text-white py-3.5 text-lg font-bold shadow-[0_8px_20px_rgba(24,104,213,0.25)] hover:-translate-y-0.5 hover:scale-[1.02] transition-all duration-300">
                                Kirim Kode OTP
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- SISI KANAN: BACKGROUND SAMA SEPERTI LOGIN -->
    <div class="hidden lg:flex relative flex-col justify-center items-center overflow-hidden h-full">
        <img src="{{ asset('assets/images/login-bg.jpeg') }}" class="absolute inset-0 w-full h-full object-cover animate-slow-zoom">
        <div class="absolute inset-0 bg-linear-to-br from-[#12457E]/95 via-[#1A5296]/80 to-[#0A2E59]/95 mix-blend-multiply bg-animated-gradient"></div>
        <div class="relative z-10 flex flex-col justify-center items-center h-full px-12 xl:px-16 text-white text-center">
            <h1 class="text-3xl xl:text-4xl font-extrabold leading-tight drop-shadow-xl mb-4">
                Keamanan Data <br>
                <span class="text-blue-100 font-bold">Adalah Prioritas Kami</span>
            </h1>
            <p class="text-blue-50 font-medium max-w-md mx-auto">
                Sistem OTP 15 Menit memastikan bahwa hanya Anda yang berhak mengatur ulang kata sandi akun Anda.
            </p>
        </div>
    </div>
</div>

<!-- SCRIPT UNTUK FUNGSI IKON MATA -->
<script>
    function toggleVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);

        if (type === 'password') {
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        } else {
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        }
    }
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesin Presensi - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-linear-to-br from-[#0D3B66] to-[#114B82] min-h-screen flex items-center justify-center p-4 font-sans selection:bg-blue-500 selection:text-white relative overflow-hidden">

    <!-- Efek Latar Belakang -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden relative z-10">

        <!-- Header Mesin -->
        <div class="bg-gray-50 border-b border-gray-100 p-6 text-center">
            <img src="{{ asset('assets/logos/logo-sppg.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-3 object-contain drop-shadow-sm">
            <h1 class="text-2xl font-black text-[#0D3B66] tracking-tight">PORTAL PRESENSI</h1>
            <p class="text-sm font-bold text-gray-400 mt-1 uppercase tracking-wider">Silakan Scan QR Code di bawah</p>
        </div>

        <!-- Area QR Code -->
        <div class="p-8 flex flex-col items-center">
            <div class="relative bg-white p-4 border-4 border-dashed border-gray-200 rounded-3xl transition-all duration-500 hover:border-[#1868D5]/50 group">

                <!-- Loading Spinner (Muter-muter saat memuat QR baru) -->
                <div id="loader" class="absolute inset-0 bg-white/90 flex flex-col items-center justify-center rounded-2xl z-10">
                    <span class="animate-spin h-10 w-10 border-4 border-gray-200 border-t-[#1868D5] rounded-full mb-3"></span>
                    <span class="text-xs font-bold text-gray-500 animate-pulse">Menyiapkan QR...</span>
                </div>

                <!-- Gambar QR Code -->
                <img id="qr-image" src="" alt="QR Code Absensi" class="w-64 h-64 object-contain opacity-0 transition-opacity duration-500 group-hover:scale-105 transform">
            </div>

            <p class="mt-6 text-[13px] font-semibold text-gray-500 text-center px-4 bg-gray-50 py-2 rounded-lg">
                QR Code akan berganti otomatis setiap selesai digunakan.
            </p>
        </div>

        <!-- Notifikasi Sukses Mengambang -->
        <div id="successNotif" class="absolute inset-x-0 bottom-0 translate-y-full bg-green-500 p-5 text-center transition-transform duration-500 ease-out flex flex-col items-center z-20">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-2 shadow-lg">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-white font-black text-lg shadow-sm">Presensi Berhasil!</p>
            <p id="namaPegawai" class="text-green-100 font-semibold text-sm mt-0.5">Memuat...</p>
        </div>
    </div>

    <!-- Script Logika Mesin Presensi -->
    <script>
        let currentToken = '';
        let pollingInterval = null;

        // 1. Fungsi untuk Meminta Token & Membuat Gambar QR Baru
        function generateNewQR() {
            // Tampilkan Loading
            document.getElementById('loader').style.display = 'flex';
            document.getElementById('qr-image').classList.remove('opacity-100');

            // Hentikan polling sementara saat sedang membuat QR
            if (pollingInterval) clearInterval(pollingInterval);

            fetch('/presensi/api/get-token')
                .then(response => response.json())
                .then(data => {
                    currentToken = data.token;

                    // Buat link yang akan dibuka oleh HP Pegawai saat scan
                    let scanUrl = `{{ url('/pegawai/scan-qr') }}/${currentToken}`;

                    // Gunakan API eksternal untuk menggambar QR Code dengan cepat
                    let qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=${encodeURIComponent(scanUrl)}&margin=10`;

                    let imgElement = document.getElementById('qr-image');
                    imgElement.onload = function() {
                        // Sembunyikan loading jika gambar sudah termuat
                        document.getElementById('loader').style.display = 'none';
                        imgElement.classList.add('opacity-100');

                        // Mulai mengecek status QR Code ini setiap 1.5 detik
                        pollingInterval = setInterval(checkTokenStatus, 1500);
                    };
                    imgElement.src = qrImageUrl;
                })
                .catch(error => console.error('Error generating QR:', error));
        }

        // 2. Fungsi untuk Mengecek Apakah QR Sudah Di-Scan
        function checkTokenStatus() {
            if (!currentToken) return;

            fetch(`/presensi/api/check-token/${currentToken}`)
                .then(response => response.json())
                .then(data => {
                    // Jika API mengembalikan status 'scanned'
                    if (data.status === 'scanned') {
                        // Hentikan pengecekan
                        clearInterval(pollingInterval);

                        // Tampilkan Notifikasi Sukses Hijau
                        document.getElementById('namaPegawai').innerText = data.pegawai;
                        const notif = document.getElementById('successNotif');
                        notif.classList.remove('translate-y-full');

                        // Tunggu 3 Detik, Tutup Notifikasi, lalu Buat QR Baru
                        setTimeout(() => {
                            notif.classList.add('translate-y-full');
                            generateNewQR();
                        }, 3000);
                    }
                })
                .catch(error => console.error('Error checking token:', error));
        }

        // Jalankan fungsi pembuatan QR Code pertama kali saat halaman dibuka
        window.onload = generateNewQR;
    </script>
</body>
</html>

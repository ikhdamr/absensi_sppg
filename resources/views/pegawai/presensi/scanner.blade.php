<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Absensi - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Script HTML5 QR Code -->
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="bg-[#0D3B66] text-white text-center py-6 px-4">
            <h1 class="text-2xl font-extrabold tracking-wide mb-1">Scan QR Absensi</h1>
            <p class="text-sm text-blue-200 font-medium">Arahkan kamera HP Anda ke layar absensi</p>
        </div>

        <!-- Area Kamera -->
        <div class="p-5 relative bg-white">
            <!-- Wrapper Layar Kamera -->
            <div class="rounded-xl overflow-hidden bg-black relative shadow-inner aspect-4/5 flex items-center justify-center">
                <!-- Elemen Video Scanner -->
                <div id="reader" class="w-full h-full border-none"></div>

                <!-- Tombol Flip Kamera (Melayang di atas video) -->
                <button type="button" onclick="flipCamera()" class="absolute bottom-4 right-4 z-50 bg-white/20 hover:bg-white/40 backdrop-blur-md text-white p-3 rounded-full shadow-lg transition-all border border-white/30 focus:outline-none focus:ring-2 focus:ring-white/50" title="Balik Kamera">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Informasi Kaki (Footer) -->
        <div class="px-6 pb-6 text-center">
            <p class="text-xs text-gray-400 font-medium tracking-wide">
                Gunakan tombol kembali <br>di HP Anda untuk keluar dari halaman ini.
            </p>
        </div>
    </div>

    <script>
        let html5QrCode;
        // Set default ke kamera belakang (environment)
        let currentFacingMode = "environment";

        document.addEventListener("DOMContentLoaded", function() {
            // Gunakan Html5Qrcode Core (Bukan Scanner biasa) agar UI bisa dicustom total
            html5QrCode = new Html5Qrcode("reader");
            startScanner();
        });

        // Fungsi Memulai Kamera
        function startScanner() {
            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrCode.start(
                { facingMode: currentFacingMode },
                config,
                function(decodedText, decodedResult) {
                    // KETIKA BERHASIL SCAN QR
                    html5QrCode.stop().then(() => {
                        // Jika QR Code berisi link URL lengkap (http://...)
                        if (decodedText.includes('http')) {
                            window.location.href = decodedText;
                        } else {
                            // Jika QR Code hanya berisi token acak
                            window.location.href = "/pegawai/scan-qr/" + decodedText;
                        }
                    }).catch(err => {
                        console.log("Gagal menghentikan kamera", err);
                    });
                },
                function(errorMessage) {
                    // Berjalan saat mencari QR (dibiarkan kosong agar console tidak spam)
                }
            ).catch(err => {
                console.log("Gagal memulai kamera", err);
                alert("Gagal mengakses kamera. Pastikan Anda telah memberikan izin kamera pada browser HP Anda.");
            });
        }

        // Fungsi Memutar (Flip) Kamera
        function flipCamera() {
            if (html5QrCode) {
                // Matikan kamera yang menyala saat ini terlebih dahulu
                html5QrCode.stop().then(() => {
                    // Ganti Mode Kamera (Depan <-> Belakang)
                    currentFacingMode = currentFacingMode === "environment" ? "user" : "environment";
                    // Nyalakan kembali dengan mode baru
                    startScanner();
                }).catch(err => {
                    console.log("Gagal menghentikan kamera untuk diputar", err);
                });
            }
        }
    </script>

    <!-- CSS Tambahan Untuk Memaksa Tampilan Bersih -->
    <style>
        #reader { border: none !important; background: transparent !important; }
        #reader video {
            object-fit: cover !important;
            width: 100% !important;
            height: 100% !important;
            border-radius: 0.75rem;
        }
        /* Menyembunyikan elemen bawaan html5-qrcode jika tiba-tiba muncul */
        #reader__dashboard_section_csr,
        #reader__dashboard_section_swaplink,
        #reader__camera_selection,
        #reader__dashboard_section_csr span {
            display: none !important;
        }
    </style>
</body>
</html>

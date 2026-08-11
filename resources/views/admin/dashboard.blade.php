<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SPPG</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logos/logo-sppg.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }

        /* Custom Scrollbar for better UI */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex flex-col font-sans text-gray-800 antialiased selection:bg-blue-200 selection:text-blue-900">


    @include('admin.layouts.navbar')

    <main class="grow px-6 lg:px-8 py-10 w-full max-w-7xl mx-auto">

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-10 animate-fade-in-up">
            <div class="text-left flex-1">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-transparent bg-clip-text bg-linear-to-r from-[#0D3B66] to-[#1868D5]">
                    Halo, {{ Auth::user()?->name ?? 'Admin' }} 👋
                </h1>
                <p class="text-gray-500 text-sm md:text-base mt-2 font-medium">
                    Pantau aktivitas presensi dan kinerja pegawai Anda hari ini.
                </p>
            </div>

            <div class="shrink-0 w-full lg:w-auto relative group overflow-hidden bg-white px-6 py-4 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                <div class="absolute inset-0 bg-linear-to-r from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10 flex items-center gap-5">
                    <div class="p-3 bg-blue-50 rounded-xl text-[#1868D5]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <div id="clock-digital" class="text-3xl font-black tracking-tight font-mono text-[#0D3B66]">00:00:00</div>
                        <div id="date-today" class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Memuat...</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <div class="relative overflow-hidden bg-white border border-gray-100 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(0,0,0,0.1)] transition-all duration-300 hover:-translate-y-1 animate-fade-in-up delay-100 group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-gray-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center group-hover:bg-gray-800 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pegawai</p>
                        <h3 class="font-black text-2xl text-gray-800 leading-none">{{ $totalPegawai }} <span class="text-sm font-semibold text-gray-400">Orang</span></h3>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white border border-green-50 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(22,163,74,0.15)] transition-all duration-300 hover:-translate-y-1 animate-fade-in-up delay-200 group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-green-100 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-green-600/70 uppercase tracking-wider mb-0.5">Hadir Hari Ini</p>
                        <p class="text-[10px] text-gray-400 font-bold mb-1.5 uppercase tracking-wide">{{ $namaJamKerja }}</p>
                        <h3 class="font-black text-2xl text-gray-800 leading-none">{{ $hadirCount }} <span class="text-sm font-semibold text-gray-400">Orang</span></h3>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white border border-red-50 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(220,38,38,0.15)] transition-all duration-300 hover:-translate-y-1 animate-fade-in-up delay-300 group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-red-100 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-red-600/70 uppercase tracking-wider mb-1">Alpa Hari Ini</p>
                        <h3 class="font-black text-2xl text-gray-800 leading-none">{{ $alpaCount }} <span class="text-sm font-semibold text-gray-400">Orang</span></h3>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden bg-white border border-blue-50 rounded-2xl p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_-4px_rgba(24,104,213,0.15)] transition-all duration-300 hover:-translate-y-1 animate-fade-in-up delay-400 group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500 ease-in-out pointer-events-none"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-blue-100 text-[#1868D5] flex items-center justify-center group-hover:bg-[#1868D5] group-hover:text-white transition-colors duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-blue-600/70 uppercase tracking-wider mb-1">Cuti/Izin/Sakit</p>
                        <h3 class="font-black text-2xl text-gray-800 leading-none">{{ $izinCutiSakitCount }} <span class="text-sm font-semibold text-gray-400">Orang</span></h3>
                    </div>
                </div>
            </div>

        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-6 lg:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] animate-fade-in-up delay-400">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h3 class="font-black text-2xl text-[#0D3B66]">Grafik Bulan {{ $namaBulanTerpilih }}</h3>
                    <p class="text-sm text-gray-500 font-medium mt-1">Rekap data historis kehadiran pegawai.</p>
                </div>

                <form id="formBulan" action="" method="GET" class="w-full sm:w-auto">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 group-hover:text-[#1868D5] transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <select name="bulan" onchange="document.getElementById('formBulan').submit()" class="bg-gray-50 border border-gray-200 text-gray-800 text-sm font-bold rounded-xl focus:ring-[#1868D5] focus:border-[#1868D5] block w-full sm:w-48 pl-10 p-2.5 outline-none cursor-pointer transition hover:bg-gray-100 shadow-sm appearance-none">
                            @foreach($daftarBulan as $num => $name)
                                <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </form>
            </div>

            <div class="w-full h-100">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

    </main>

    <footer class="w-full px-6 lg:px-8 py-6 mt-auto border-t border-gray-200/60 bg-white">
        <p class="text-[13px] md:text-sm font-bold text-gray-400 text-center lg:text-left">
            © 2026 <span class="text-[#0D3B66]">SPPG Langensari Tarogong Kaler</span>. All Rights Reserved.
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');

            // Set default font for chart
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b';

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Hadir',
                            data: {!! json_encode($chartHadir) !!},
                            backgroundColor: '#16A34A', // Hijau
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Izin/Cuti/Sakit',
                            data: {!! json_encode($chartIzin) !!},
                            backgroundColor: '#3B82F6', // Biru
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Alpa',
                            data: {!! json_encode($chartAlpa) !!},
                            backgroundColor: '#EF4444', // Merah
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { weight: 'bold', size: 13 },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            padding: 14,
                            cornerRadius: 10,
                            displayColors: true,
                            boxPadding: 6
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false,
                            },
                            border: { dash: [4, 4] },
                            ticks: {
                                font: { weight: '600' },
                                stepSize: 5,
                                padding: 10
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                font: { weight: '600' },
                                padding: 8
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        });

        // Script Jam Digital Real-time
        function startRealTimeClock() {
            const clockElement = document.getElementById('clock-digital');
            const dateElement = document.getElementById('date-today');

            function updateTime() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                if (clockElement) clockElement.innerText = `${hours}:${minutes}:${seconds}`;

                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                if (dateElement) dateElement.innerText = now.toLocaleDateString('id-ID', options);
            }

            updateTime();
            setInterval(updateTime, 1000);
        }
        startRealTimeClock();
    </script>
</body>
</html>

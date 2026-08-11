<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Harian</title>
    <style>
        /* Desain Modern & Minimalis */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #334155; /* Slate 700 */
        }

        /* Bagian Header tanpa Kop */
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #0D3B66;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20pt;
            color: #0D3B66;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 11pt;
            color: #64748b; /* Slate 500 */
        }

        /* Tabel Presensi */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #cbd5e1; /* Slate 300 */
            padding: 10px 8px;
            text-align: center;
        }
        th {
            background-color: #f8fafc; /* Slate 50 */
            color: #475569; /* Slate 600 */
            font-size: 9pt;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .text-left {
            text-align: left;
        }

        /* Efek selang-seling warna pada baris tabel */
        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Teks Penekanan */
        .fw-bold { font-weight: bold; }
        .text-red { color: #dc2626; }
        .text-green { color: #16a34a; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Rekap Presensi Harian</h1>
        <p>Tanggal Laporan: <span class="fw-bold">{{ $tanggalFormat }}</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%" class="text-left">Nama Pegawai</th>
                <th width="12%">Jam Masuk</th>
                <th width="12%">Jam Keluar</th>
                <th width="12%">Total Jam</th>
                <th width="14%">Terlambat</th>
                <th width="25%">Status / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $index => $data)
                @php
                    // LOGIKA OTOMATIS: Menghitung Durasi Kerja dan Keterlambatan
                    $totalJam = '-';
                    $terlambat = '-';
                    $warnaTerlambat = '';

                    // Jika jam masuk dan jam keluar ada datanya (sudah pulang)
                    if($data->jam_masuk && $data->jam_keluar) {
                        $masuk = \Carbon\Carbon::parse($data->jam_masuk);
                        $keluar = \Carbon\Carbon::parse($data->jam_keluar);
                        $totalJam = $masuk->diff($keluar)->format('%Hj %Im'); // Format: 08j 30m
                    }

                    // Menghitung keterlambatan (Asumsi Jam Masuk Normal adalah 08:00:00)
                    if($data->jam_masuk) {
                        $masuk = \Carbon\Carbon::parse($data->jam_masuk);
                        $batasJamMasuk = \Carbon\Carbon::parse('08:00:00');

                        if($masuk->greaterThan($batasJamMasuk)) {
                            $terlambat = $batasJamMasuk->diff($masuk)->format('%Hj %Im');
                            $warnaTerlambat = 'text-red fw-bold';
                        } else {
                            $terlambat = 'Tepat Waktu';
                            $warnaTerlambat = 'text-green';
                        }
                    }
                @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left fw-bold">{{ $data->user->name ?? 'Data Terhapus' }}</td>
                <td>{{ $data->jam_masuk ?? '-' }}</td>
                <td>{{ $data->jam_keluar ?? '-' }}</td>
                <td>{{ $totalJam }}</td>
                <td class="{{ $warnaTerlambat }}">{{ $terlambat }}</td>
                <td>
                    <span class="fw-bold" style="text-transform: uppercase;">{{ $data->status }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding: 30px; color: #94a3b8; font-size: 11pt;">
                    Tidak ada data presensi pada tanggal ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

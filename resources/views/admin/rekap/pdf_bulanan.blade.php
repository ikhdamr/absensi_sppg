<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi Bulanan</title>
    <style>
        /* Desain Modern & Minimalis sama seperti PDF Harian */
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10pt; color: #334155; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #0D3B66; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 20pt; color: #0D3B66; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 8px 0 0 0; font-size: 11pt; color: #64748b; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px 8px; text-align: center; }
        th { background-color: #f8fafc; color: #475569; font-size: 9pt; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; }

        .text-left { text-align: left; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Rekap Presensi Bulanan</h1>
        <p>Bulan Laporan: <span class="fw-bold">{{ $bulanFormat }}</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%" class="text-left">Nama Pegawai</th>
                <th width="15%">Total Hadir</th>
                <th width="15%">Total Terlambat</th>
                <th width="15%">Izin / Sakit / Cuti</th>
                <th width="15%">Total Alpa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekap as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left fw-bold">{{ $data->name ?? 'Data Terhapus' }}</td>
                <td>{{ $data->total_hadir }}</td>
                <td>{{ $data->total_terlambat }}</td>
                <td>{{ $data->total_izin }}</td>
                <td>{{ $data->total_alpa }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 30px; color: #94a3b8; font-size: 11pt;">
                    Tidak ada data pegawai yang dapat ditampilkan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Jurnal Mingguan</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #333; }
        .text-center { text-align: center; }
        .header { margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 10px; word-wrap: break-word; vertical-align: top; }
        th { background-color: #f0f0f0; font-weight: bold; text-transform: uppercase; font-size: 9pt; }
        .no-data { padding: 40px; text-align: center; color: #999; font-style: italic; }
    </style>
</head>
<body>
    <div class="header text-center">
        <h2 style="margin: 0; letter-spacing: 1px;">{{ $title }}</h2>
        <p style="margin: 5px 0;">Periode: <strong>{{ $periode }}</strong></p>
        <p style="margin: 0;">Guru Pengampu: {{ $nama_guru }} | NIP: {{ $nip }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 100px;">Hari / Tanggal</th>
                <th style="width: 150px;">Materi / Kompetensi Dasar</th>
                <th>Kegiatan Pembelajaran</th>
                <th style="width: 150px;">Penilaian / Evaluasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnals as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d M Y') }}</td>
                <td>{{ $item->materi_kd }}</td>
                <td>{{ $item->kegiatan_pembelajaran }}</td>
                <td>{{ $item->evaluasi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="no-data">Tidak ada rekaman pembelajaran untuk minggu ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; float: right; width: 250px;" class="text-center">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y') }}</p>
        <br><br><br>
        <p><strong>{{ $nama_guru }}</strong><br>NIP. {{ $nip }}</p>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        /* Menggunakan font standar yang aman untuk DomPDF */
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 16px; }
        .header p { margin: 5px 0 0; font-size: 12px; }
        
        .info { margin-bottom: 20px; }
        .info table { width: 100%; border: none; }
        .info td { padding: 2px 0; }
        
        .table-data { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .table-data th, .table-data td { border: 1px solid #000; padding: 6px 8px; text-align: left; }
        .table-data th { background-color: #f2f2f2; text-transform: uppercase; font-size: 10px; font-weight: bold; }
        
        .jurnal-header { background: #f9f9f9; padding: 8px; border-left: 4px solid #333; margin-top: 15px; font-weight: bold; }
        
        .footer { margin-top: 40px; }
        .footer-table { width: 100%; border: none; }
        .badge { font-weight: bold; text-transform: uppercase; font-size: 9px; }
        
        /* Warna untuk status */
        .sakit { color: #b45309; }
        .izin { color: #1d4ed8; }
        .alfa { color: #b91c1c; }

        /* Mencegah tabel terpotong di tengah halaman */
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Ketidakhadiran Siswa</h2>
        <p>Hari/Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="15%">Nama Guru</td>
                <td width="2%">:</td>
                <td><strong>{{ $nama_guru }}</strong></td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $nip }}</td>
            </tr>
        </table>
    </div>

    @foreach($riwayatJurnal as $jurnal)
    <div class="jurnal-header">
        Mata Pelajaran: {{ $jurnal->mata_pelajaran }} | 
        Kelas: {{ $jurnal->kelas }} | 
        Materi: {{ $jurnal->materi_kd }}
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%" style="text-align: center;">No</th>
                <th width="20%">NIS</th>
                <th width="55%">Nama Siswa</th>
                <th width="20%" style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($jurnal->absensi as $abs)
            <tr>
                <td style="text-align: center;">{{ $no++ }}</td>
                <td>{{ $abs->siswa->nis ?? '-' }}</td>
                <td>{{ $abs->siswa->nama ?? 'Siswa Terhapus' }}</td>
                <td style="text-align: center;">
                    <span class="badge {{ strtolower($abs->status) }}">
                        {{ strtoupper($abs->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #059669; font-style: italic;">
                    Nihil (Semua Siswa Hadir)
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endforeach

    <table class="footer-table" style="margin-top: 50px;">
        <tr>
            <td width="70%"></td>
            <td width="30%" style="text-align: center;">
                <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
                <br><br><br><br>
                <p><strong>{{ $nama_guru }}</strong><br>NIP. {{ $nip }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
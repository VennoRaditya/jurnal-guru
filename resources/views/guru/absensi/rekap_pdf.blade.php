<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Jurnal Bulanan - {{ $periode }}</title>
    <style>
        /* Setup Halaman */
        @page { margin: 1cm; }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10pt; 
            color: #333; 
            line-height: 1.5; 
            margin: 0;
            padding: 0;
        }

        .text-center { text-align: center; }
        .text-uppercase { text-transform: uppercase; }
        .font-bold { font-weight: bold; }

        /* Kop Surat / Header */
        .header { 
            margin-bottom: 20px; 
            border-bottom: 3px double #000; 
            padding-bottom: 10px; 
        }

        /* Styling Tabel */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed; /* Penting agar lebar kolom konsisten */
            margin-bottom: 20px;
        }
        
        th, td { 
            border: 1px solid #000; 
            padding: 10px 6px; 
            word-wrap: break-word; 
            vertical-align: top; 
        }

        th { 
            background-color: #f2f2f2; 
            font-size: 9pt; 
            letter-spacing: 0.5px;
        }

        /* Mencegah baris terpotong antar halaman */
        tr { page-break-inside: avoid; }
        thead { display: table-header-group; }

        .no-data { 
            padding: 50px; 
            text-align: center; 
            color: #666; 
            font-style: italic; 
            border: 1px solid #000;
        }

        /* Area Tanda Tangan */
        .footer-section {
            margin-top: 30px;
            width: 100%;
        }

        .ttd-container {
            float: right;
            width: 250px;
            text-align: center;
        }

        .spacer-ttd {
            height: 70px; /* Jarak untuk tanda tangan basah */
        }

        /* Clearfix untuk float */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        <h2 style="margin: 0; letter-spacing: 1px;" class="text-uppercase">Laporan Jurnal Pembelajaran Bulanan</h2>
        <p style="margin: 5px 0; font-size: 11pt;">Periode: <strong>{{ $periode }}</strong></p>
        <p style="margin: 0;">Mata Pelajaran: <strong>{{ $jurnals->first()->mata_pelajaran ?? '-' }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 60px;">Kelas</th> <th style="width: 140px;">Materi / KD</th>
                <th>Kegiatan Pembelajaran</th>
                <th style="width: 130px;">Evaluasi / Hambatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jurnals as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                <td class="text-center">{{ $item->kelas }}</td>
                <td>{{ $item->materi_kd }}</td>
                <td style="text-align: justify;">{{ $item->kegiatan_pembelajaran }}</td>
                <td>{{ $item->evaluasi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="no-data">Tidak ada rekaman pembelajaran untuk bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-section clearfix">
        <div class="ttd-container">
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p style="margin-top: 0;">Guru Pengampu,</p>
            
            <div class="spacer-ttd"></div>
            
            <p style="margin-bottom: 0;"><strong><u>{{ $nama_guru }}</u></strong></p>
            <p style="margin-top: 0;">NIP. {{ $nip }}</p>
        </div>
    </div>
</body>
</html>
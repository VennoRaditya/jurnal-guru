<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Jurnal Bulanan - {{ $nama_guru }}</title>
    <style>
        /* Konfigurasi Halaman Portrait */
        @page { 
            margin: 1.2cm 1.5cm; 
            size: portrait; 
        }

        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; /* Ukuran sedikit diperkecil agar pas di portrait */
            color: #333; 
            line-height: 1.4; 
            margin: 0; 
            padding: 0; 
        }

        /* Kop Surat */
        .kop-surat { 
            border-bottom: 2.5px solid #000; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
            width: 100%; 
            clear: both;
        }
        .logo-box {
            float: left;
            width: 15%;
        }
        .logo { width: 65px; }
        .text-kop { 
            float: left;
            width: 85%;
            text-align: center; 
        }
        .text-kop p { margin: 1px 0; font-size: 9pt; }
        .text-kop h1 { font-size: 15pt; margin: 0; text-transform: uppercase; font-weight: bold; }
        .text-kop .alamat { font-size: 7.5pt; font-style: italic; color: #555; }

        /* Judul */
        .title-report { 
            text-align: center; 
            margin: 20px 0;
            clear: both;
        }
        .title-report h2 { 
            margin: 0; 
            font-size: 13pt; 
            text-transform: uppercase; 
            border-bottom: 1px solid #333;
            display: inline-block;
            padding-bottom: 2px;
        }
        .title-report p { margin-top: 5px; font-weight: bold; font-size: 10pt; color: #444; }

        /* Info Guru */
        .info-container {
            width: 100%;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .info-table { width: 100%; border: none; }
        .info-table td { border: none; padding: 2px 0; }
        .label { width: 100px; color: #666; }
        .dot { width: 10px; }

        /* Tabel Data Utama */
        table.main-table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed; /* Penting untuk Portrait agar tidak meluber */
        }
        table.main-table th { 
            background-color: #f2f2f2; 
            border: 1px solid #000; 
            padding: 8px 4px; 
            font-size: 8pt; 
            text-transform: uppercase;
        }
        table.main-table td { 
            border: 1px solid #000; 
            padding: 8px 6px; 
            vertical-align: top; 
            word-wrap: break-word;
        }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }

        /* Footer Tanda Tangan */
        .footer-ttd { 
            margin-top: 35px; 
            width: 100%; 
            border: none;
            page-break-inside: avoid;
        }
        .footer-ttd td { border: none; text-align: center; }
        .space-ttd { height: 65px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <div class="logo-box">
            @php $logoPath = public_path('images/logo_smkn_43.jpg'); @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" class="logo">
            @endif
        </div>
        <div class="text-kop">
            <p>PEMERINTAH PROVINSI DKI JAKARTA</p>
            <p>DINAS PENDIDIKAN</p>
            <h1>SMK NEGERI 43 JAKARTA</h1>
            <p class="alamat">JL. CIPULIR 1, Cipulir, Kebayoran Lama, Jakarta Selatan</p>
            <p class="alamat">Telepon: (021) 7257532 | Email: info@smkn43jkt.sch.id</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="title-report">
        <h2>Jurnal Pembelajaran Bulanan</h2>
        <p>PERIODE: {{ $date->translatedFormat('F Y') }}</p>
    </div>

    <div class="info-container">
        <table class="info-table">
            <tr>
                <td class="label">Nama Guru</td>
                <td class="dot">:</td>
                <td class="bold">{{ $nama_guru }}</td>
                <td class="label" style="text-align: right;">NIP :</td>
                <td style="padding-left: 5px;">{{ $nip }}</td>
            </tr>
            <tr>
                <td class="label">Tahun Ajaran</td>
                <td class="dot">:</td>
                <td colspan="3">{{ $year }}</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 55px;">Tanggal</th>
                <th style="width: 50px;">Kelas</th>
                <th style="width: 110px;">Materi / KD</th>
                <th>Kegiatan Pembelajaran</th>
                <th style="width: 90px;">Evaluasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatJurnal as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m') }}</td>
                <td class="text-center bold">{{ $item->kelas }}</td>
                <td>{{ $item->materi_kd }}</td>
                <td>{{ $item->kegiatan_pembelajaran }}</td>
                <td>{{ $item->evaluasi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; font-style: italic; color: #888;">
                    Data jurnal tidak tersedia untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-ttd">
        <tr>
            <td width="60%"></td>
            <td>
                <p>Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
                <p>Guru Mata Pelajaran,</p>
                <div class="space-ttd"></div>
                <p><strong><u>{{ $nama_guru }}</u></strong></p>
                <p>NIP. {{ $nip }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
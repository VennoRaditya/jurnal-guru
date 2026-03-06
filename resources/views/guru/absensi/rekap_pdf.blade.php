<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Jurnal Bulanan - {{ $nama_guru }}</title>
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10pt; 
            color: #333; 
            line-height: 1.3; 
            margin: 0; 
            padding: 0; 
        }
        @page { margin: 1cm 1.5cm; }

        .kop-surat { 
            border-bottom: 3px double #000; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
            width: 100%; 
            position: relative;
        }
        .logo { 
            width: 70px; 
            position: absolute; 
            left: 0; 
            top: 0; 
        }
        .text-kop { 
            text-align: center; 
        }
        .text-kop p { margin: 2px 0; font-size: 10pt; }
        .text-kop h1 { font-size: 16pt; margin: 0; text-transform: uppercase; }
        .text-kop .alamat { font-size: 8pt; font-style: italic; }

        .title-report { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .title-report h2 { 
            text-decoration: underline; 
            margin: 0; 
            font-size: 14pt; 
            text-transform: uppercase; 
        }
        .title-report p { margin: 5px 0; font-weight: bold; }

        .info-table { width: 100%; margin-bottom: 15px; border: none; }
        .info-table td { border: none; padding: 2px 0; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 6px 4px; 
            word-wrap: break-word; 
            vertical-align: top; 
            overflow-wrap: break-word;
        }
        th { 
            background-color: #e9e9e9; 
            text-transform: uppercase; 
            font-size: 8pt; 
            text-align: center;
        }

        .footer-ttd { 
            margin-top: 30px; 
            width: 100%; 
            border: none;
        }
        .footer-ttd td { 
            border: none; 
            text-align: center; 
            vertical-align: bottom; 
        }

        .text-center { text-align: center; }
        tr { page-break-inside: avoid; }
        thead { display: table-header-group; }
    </style>
</head>
<body>

    <div class="kop-surat">
        {{-- Gunakan pengecekan file agar tidak error jika gambar tidak ada --}}
        @php $logoPath = public_path('images/logo_smkn_43.jpg'); @endphp
        @if(file_exists($logoPath))
            <img src="{{ $logoPath }}" class="logo">
        @endif
        
        <div class="text-kop">
            <p>PEMERINTAH PROVINSI DKI JAKARTA</p>
            <p>DINAS PENDIDIKAN</p>
            <h1>SMK NEGERI 43 JAKARTA</h1>
            <p class="alamat">JL. CIPULIR 1, Cipulir, Kebayoran Lama, Jakarta Selatan</p>
            <p class="alamat">Telepon: (021) 7257532 | Email: info@smkn43jkt.sch.id</p>
        </div>
    </div>

    <div class="title-report">
        <h2>Jurnal Pembelajaran Bulanan</h2>
        {{-- Gunakan variabel $date yang sudah dikirim dari Controller --}}
        <p>PERIODE: {{ $date->translatedFormat('F Y') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama Guru</td>
            <td width="2%">:</td>
            <td width="40%"><strong>{{ $nama_guru }}</strong></td>
            <td width="10%">NIP</td>
            <td width="2%">:</td>
            <td>{{ $nip }}</td>
        </tr>
        <tr>
            <td>Tahun</td>
            <td>:</td>
            <td colspan="4">{{ $year }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 70px;">Tanggal</th>
                <th style="width: 60px;">Kelas</th>
                <th style="width: 130px;">Materi / KD</th>
                <th>Kegiatan Pembelajaran</th>
                <th style="width: 110px;">Evaluasi / Hambatan</th>
            </tr>
        </thead>
        <tbody>
            {{-- PERBAIKAN: Mengganti $jurnals menjadi $riwayatJurnal --}}
            @forelse($riwayatJurnal as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                <td class="text-center">{{ $item->kelas }}</td>
                <td>{{ $item->materi_kd }}</td>
                <td>{{ $item->kegiatan_pembelajaran }}</td>
                <td>{{ $item->evaluasi }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; font-style: italic;">
                    Tidak ada rekaman pembelajaran untuk periode ini.
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
                <br><br><br><br>
                <p><strong><u>{{ $nama_guru }}</u></strong></p>
                <p>NIP. {{ $nip }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
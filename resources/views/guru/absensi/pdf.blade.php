<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Absensi Ketidakhadiran - {{ $nama_guru }}</title>
    <style>
        @page { 
            margin: 1cm 1.2cm; 
            size: portrait; 
        }

        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 9pt; 
            color: #333; 
            line-height: 1.2; 
            margin: 0; 
            padding: 0; 
        }

        /* Kop Surat */
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px; width: 100%; }
        .kop-table { width: 100%; border: none; }
        .logo { width: 60px; }
        .text-kop { text-align: center; }
        .text-kop p { font-size: 9pt; margin: 0; }
        .text-kop h1 { font-size: 14pt; margin: 2px 0; text-transform: uppercase; }
        .text-kop .alamat { font-size: 7.5pt; font-style: italic; color: #555; }

        /* Judul */
        .title-report { text-align: center; margin-bottom: 15px; }
        .title-report h3 { text-decoration: underline; margin: 0; font-size: 11pt; text-transform: uppercase; }
        .title-report p { margin: 3px 0; font-weight: bold; font-size: 8.5pt; }
        
        .info-table { width: 100%; margin-bottom: 10px; border-collapse: collapse; }
        .info-table td { padding: 1px 0; }
        .label { color: #666; width: 80px; }

        .section-kelas { margin-bottom: 20px; }
        .label-kelas { 
            background-color: #333; 
            color: #fff; 
            padding: 3px 10px; 
            font-weight: bold; 
            border-radius: 2px; 
            display: inline-block; 
            margin-bottom: 8px; 
            font-size: 9pt; 
        }

        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: auto; 
        }
        
        .table-data th, .table-data td { 
            border: 1px solid #000; 
            padding: 5px 3px; 
            word-wrap: break-word; 
        }

        .col-no { width: 25px; }
        .col-nis { width: 70px; }
        .col-stat { width: 30px; }
        .col-total { width: 40px; }

        .table-data th { 
            background-color: #f2f2f2; 
            text-transform: uppercase; 
            font-size: 7.5pt; 
            text-align: center; 
        }
        
        .text-center { text-align: center; }
        .nama-siswa { 
            text-align: left; 
            padding-left: 5px !important; 
            font-size: 8.5pt; 
            white-space: normal; 
            line-height: 1.1;
        }
        
        .font-bold { font-weight: bold; }
        .bg-grey { background-color: #f9f9f9; }

        .footer-ttd { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .footer-ttd td { border: none; text-align: center; }
        .space-ttd { height: 50px; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <table class="kop-table">
            <tr>
                <td width="12%" class="text-center">
                    @php
                        $path = public_path('images/logo_smkn_43.jpg');
                        $base64 = null;
                        if(file_exists($path)){
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $dataImg = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
                        }
                    @endphp
                    @if($base64)
                        <img src="{{ $base64 }}" class="logo">
                    @endif
                </td>
                <td width="88%" class="text-kop">
                    <p>PEMERINTAH PROVINSI DKI JAKARTA</p>
                    <p>DINAS PENDIDIKAN</p>
                    <h1>SMK NEGERI 43 JAKARTA</h1>
                    <p class="alamat">JL. CIPULIR 1, Cipulir, Kebayoran Lama, Jakarta Selatan</p>
                    <p class="alamat">Telepon: (021) 7257532 | Email: info@smkn43jkt.sch.id</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="title-report">
        <h3>REKAPITULASI KETIDAKHADIRAN SISWA</h3>
        <p>PERIODE: {{ strtoupper($periode) }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Guru</td>
            <td width="2%">:</td>
            <td><strong>{{ strtoupper($nama_guru) }}</strong></td>
            <td class="label" style="text-align: right; width: 150px;">NIP : {{ $nip }}</td>
        </tr>
    </table>

    @php
        $dataPerKelas = [];
        foreach($riwayatJurnal as $jurnal) {
            $namaKelas = is_object($jurnal->class) ? $jurnal->class->nama_kelas : ($jurnal->kelas ?: 'Tanpa Kelas');
            
            if($jurnal->absensi) {
                foreach($jurnal->absensi as $abs) {
                    $siswaId = $abs->siswa_id;
                    $status = trim(strtolower($abs->status));

                    // Inisialisasi data siswa (agar SEMUA siswa masuk daftar)
                    if(!isset($dataPerKelas[$namaKelas][$siswaId])) {
                        $dataPerKelas[$namaKelas][$siswaId] = [
                            'nis' => $abs->siswa->nis ?? '-',
                            'nama' => $abs->siswa->nama ?? 'Siswa Terhapus',
                            'sakit' => 0, 'izin' => 0, 'alfa' => 0, 'terlambat' => 0, 'total' => 0
                        ];
                    }

                    // Hanya tambahkan ke hitungan jika statusnya bukan 'hadir'
                    if($status !== 'hadir' && isset($dataPerKelas[$namaKelas][$siswaId][$status])) {
                        $dataPerKelas[$namaKelas][$siswaId][$status]++;
                        $dataPerKelas[$namaKelas][$siswaId]['total']++;
                    }
                }
            }
        }
        ksort($dataPerKelas);
    @endphp

    @forelse($dataPerKelas as $namaKelas => $siswas)
        <div class="section-kelas">
            <div class="label-kelas">KELAS: {{ $namaKelas }}</div>

            <table class="table-data">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-nis">NIS</th>
                        <th>Nama Lengkap Siswa</th>
                        <th class="col-stat">S</th>
                        <th class="col-stat">I</th>
                        <th class="col-stat">A</th>
                        <th class="col-stat">T</th>
                        <th class="col-total" style="background-color: #444; color: #fff;">JML</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach(collect($siswas)->sortBy('nama') as $data)
                        <tr class="{{ $no % 2 == 0 ? 'bg-grey' : '' }}">
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center" style="font-size: 7.5pt;">{{ $data['nis'] }}</td>
                            <td class="nama-siswa">{{ strtoupper($data['nama']) }}</td>
                            <td class="text-center">{{ $data['sakit'] ?: '-' }}</td>
                            <td class="text-center">{{ $data['izin'] ?: '-' }}</td>
                            <td class="text-center">{{ $data['alfa'] ?: '-' }}</td>
                            <td class="text-center">{{ $data['terlambat'] ?: '-' }}</td>
                            <td class="text-center font-bold">{{ $data['total'] ?: 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 20px; border: 1px dashed #999;">
            <p style="color: #666;">Data tidak ditemukan.</p>
        </div>
    @endforelse

    <table class="footer-ttd">
        <tr>
            <td width="65%"></td>
            <td>
                <p>Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Guru Mata Pelajaran,</p>
                <div class="space-ttd"></div>
                <p><strong><u>{{ strtoupper($nama_guru) }}</u></strong></p>
                <p>NIP. {{ $nip }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
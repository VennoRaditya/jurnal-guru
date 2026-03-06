<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 0; }
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; width: 100%; height: 90px; }
        .logo { width: 70px; float: left; margin-top: 5px; }
        .text-kop { text-align: center; margin-right: 70px; }
        .text-kop h1 { font-size: 16px; margin: 0; text-transform: uppercase; }
        .text-kop p { font-size: 10px; margin: 1px 0; }
        
        .title-report { text-align: center; margin-bottom: 20px; clear: both; }
        .title-report h3 { text-decoration: underline; margin: 0; font-size: 13px; text-transform: uppercase; }
        .title-report p { margin: 5px 0; font-weight: bold; font-size: 11px; }
        
        .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 25px; }
        .table-data th, .table-data td { border: 1px solid #000; padding: 6px 4px; }
        .table-data th { background-color: #f2f2f2; text-transform: uppercase; font-size: 9px; font-weight: bold; text-align: center; }
        
        .section-kelas { margin-bottom: 30px; clear: both; }
        .label-kelas { background-color: #333; color: #fff; padding: 4px 10px; font-weight: bold; margin-bottom: 5px; border-radius: 2px; width: fit-content; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        /* Warna Status */
        .sakit { color: #b45309; }
        .izin { color: #1d4ed8; }
        .terlambat { color: #6366f1; }
        .alfa { color: #b91c1c; }
        
        .footer-ttd { margin-top: 30px; width: 100%; border-collapse: collapse; page-break-inside: avoid; }
        .footer-ttd td { text-align: center; }
        @page { margin: 1.5cm 1.5cm; }
    </style>
</head>
<body>
    <div class="kop-surat">
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
        
        <div class="text-kop">
            <p>PEMERINTAH PROVINSI DKI JAKARTA</p>
            <p>DINAS PENDIDIKAN</p>
            <h1>SMK NEGERI 43 JAKARTA</h1>
            <p>JL. CIPULIR 1, Cipulir, Kebayoran Lama, Jakarta Selatan</p>
            <p>Telepon: (021) 7257532 | Email: info@smkn43jkt.sch.id</p>
        </div>
    </div>

    <div class="title-report">
        <h3>{{ $title }}</h3>
        <p>PERIODE: {{ $periode }}</p>
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
    </table>

    @php
        // Filter data untuk hanya mengambil yang TIDAK HADIR
        $allAbsensi = collect();
        foreach($riwayatJurnal as $jurnal) {
            $namaKelas = is_object($jurnal->kelas) ? $jurnal->kelas->nama_kelas : ($jurnal->kelas ?: 'Tanpa Kelas');
            if($jurnal->absensi) {
                foreach($jurnal->absensi as $abs) {
                    // Hanya push jika status bukan 'hadir'
                    if(trim(strtolower($abs->status)) !== 'hadir') {
                        $abs->nama_kelas_label = $namaKelas;
                        $abs->tanggal_jurnal = $jurnal->tanggal;
                        $allAbsensi->push($abs);
                    }
                }
            }
        }
        $groupedByKelas = $allAbsensi->groupBy('nama_kelas_label')->sortKeys();
    @endphp

    @forelse($groupedByKelas as $namaKelas => $absensiKelas)
        <div class="section-kelas">
            <div class="label-kelas">KELAS: {{ $namaKelas }}</div>
            
            <table class="table-data">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th style="width: 80px;">Tanggal</th>
                        <th>Nama Siswa</th>
                        <th style="width: 70px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensiKelas->sortBy('tanggal_jurnal') as $index => $abs)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($abs->tanggal_jurnal)->format('d/m/Y') }}</td>
                            <td>{{ strtoupper($abs->siswa->nama ?? 'Siswa Terhapus') }}</td>
                            <td class="text-center font-bold {{ strtolower($abs->status) }}">
                                {{ strtoupper($abs->status) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="text-center" style="padding: 30px; border: 1px solid #ccc; margin-top: 20px;">
            <p>Tidak ada catatan ketidakhadiran (Semua siswa hadir) pada periode ini.</p>
        </div>
    @endforelse

    <div style="margin-top: 30px;">
        <table class="footer-ttd">
            <tr>
                <td width="60%"></td>
                <td>
                    <p>Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p>Guru Mata Pelajaran,</p>
                    <br><br><br><br>
                    <p><strong>{{ $nama_guru }}</strong></p>
                    <p>NIP. {{ $nip }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Absensi Bulanan - SMKN 43</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 0; padding: 0; }
        
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; width: 100%; position: relative; }
        .logo { width: 70px; position: absolute; left: 0; top: 0; }
        .text-kop { text-align: center; margin-left: 70px; } /* Memberi ruang untuk logo */
        .text-kop h1 { font-size: 16px; margin: 0; text-transform: uppercase; }
        .text-kop h2 { font-size: 14px; margin: 2px 0; }
        .text-kop p { font-size: 10px; margin: 1px 0; }

        .title-report { text-align: center; margin-bottom: 20px; }
        .title-report h3 { text-decoration: underline; margin: 0; font-size: 13px; text-transform: uppercase; }
        .title-report p { margin: 5px 0; font-weight: bold; font-size: 11px; }

        .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        
        .table-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-data th, .table-data td { border: 1px solid #000; padding: 6px 4px; }
        .table-data th { background-color: #f2f2f2; text-transform: uppercase; font-size: 9px; font-weight: bold; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        /* Warna indikator ketidakhadiran */
        .count-box { font-weight: bold; font-size: 11px; }
        .sakit { color: #b45309; }
        .izin { color: #1d4ed8; }
        .alfa { color: #b91c1c; }

        .footer-ttd { margin-top: 40px; width: 100%; border-collapse: collapse; }
        .footer-ttd td { text-align: center; }
        
        @page { margin: 1cm 1.5cm; }
    </style>
</head>
<body>
    <div class="kop-surat">
        {{-- Gunakan base64 atau path yang tepat untuk dompdf --}}
        <img src="{{ public_path('images/logo_smkn_43.jpg') }}" class="logo">
        <div class="text-kop">
            <p>PEMERINTAH PROVINSI DKI JAKARTA</p>
            <p>DINAS PENDIDIKAN</p>
            <h1>SMK NEGERI 43 JAKARTA</h1>
            <p>JL. CIPULIR 1, Cipulir, Kebayoran Lama, Jakarta Selatan</p>
            <p>Telepon: (021) 7257532 | Email: info@smkn43jkt.sch.id</p>
        </div>
    </div>

    <div class="title-report">
        <h3>Rekapitulasi Akumulasi Ketidakhadiran Siswa</h3>
        <p>PERIODE: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</p>
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

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIS</th>
                <th width="35%">Nama Siswa</th>
                <th width="10%">Sakit</th>
                <th width="10%">Izin</th>
                <th width="10%">Alfa</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Mengumpulkan semua absensi dari riwayat jurnal
                $allAbsensi = collect();
                foreach($riwayatJurnal as $jurnal) {
                    foreach($jurnal->absensi as $abs) {
                        $allAbsensi->push($abs);
                    }
                }
                
                // Grouping berdasarkan siswa agar satu nama muncul satu kali dengan total akumulasi
                $groupedBySiswa = $allAbsensi->groupBy('siswa_id')->sortBy(function($item) {
                    return $item->first()->siswa->nama ?? '';
                });
                
                $no = 1;
            @endphp

            @forelse($groupedBySiswa as $siswaId => $absensis)
                @php
                    $siswa = $absensis->first()->siswa;
                    
                    // PERBAIKAN: Menggunakan huruf kecil sesuai penyimpanan di database
                    $sakit = $absensis->where('status', 'sakit')->count();
                    $izin = $absensis->where('status', 'izin')->count();
                    $alfa = $absensis->where('status', 'alfa')->count();
                    
                    $total = $sakit + $izin + $alfa;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ $siswa->nis ?? '-' }}</td>
                    <td>{{ strtoupper($siswa->nama ?? 'Siswa Terhapus') }}</td>
                    <td class="text-center count-box sakit">{{ $sakit > 0 ? $sakit : '-' }}</td>
                    <td class="text-center count-box izin">{{ $izin > 0 ? $izin : '-' }}</td>
                    <td class="text-center count-box alfa">{{ $alfa > 0 ? $alfa : '-' }}</td>
                    <td class="text-center font-bold">{{ $total }} Hari</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #666; font-style: italic;">
                        Tidak ada data ketidakhadiran siswa pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <p style="font-size: 9px; margin-top: 15px;">* Data di atas adalah akumulasi ketidakhadiran (Sakit, Izin, Alfa) dari seluruh pertemuan tatap muka pada bulan tersebut.</p>

    <table class="footer-ttd">
        <tr>
            <td width="65%"></td>
            <td>
                <p>Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p>Guru Mata Pelajaran,</p>
                <br><br><br><br>
                <p><strong>{{ $nama_guru }}</strong></p>
                <p>NIP. {{ $nip }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
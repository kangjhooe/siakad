<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHS {{ $tahunAkademik->tahun }} Semester {{ $tahunAkademik->semester }} - {{ $mahasiswa->nim }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; line-height: 1.4; padding: 20mm; background: white; }
        .header { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 15px; }
        .header-logo { flex-shrink: 0; }
        .header-logo img { max-width: 80px; max-height: 80px; object-fit: contain; }
        .header-content { flex: 1; text-align: center; }
        .header h1 { font-size: 16pt; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .header h2 { font-size: 15pt; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .header h3 { font-size: 13pt; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .header p { font-size: 10pt; color: #333; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin: 20px 0; text-transform: uppercase; letter-spacing: 2px; }
        .subtitle { text-align: center; font-size: 12pt; margin-bottom: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-table .label { width: 150px; }
        .info-table .separator { width: 20px; text-align: center; }
        table.nilai { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.nilai th, table.nilai td { border: 1px solid #000; padding: 6px 8px; }
        table.nilai th { background: #f0f0f0; font-weight: bold; text-align: center; }
        table.nilai td.center { text-align: center; }
        table.nilai td.right { text-align: right; }
        table.nilai tfoot td { font-weight: bold; background: #f9f9f9; }
        .summary { margin-top: 20px; display: flex; gap: 20px; }
        .summary-box { flex: 1; text-align: center; padding: 15px; border: 2px solid #000; }
        .summary-box .value { font-size: 28pt; font-weight: bold; }
        .summary-box .label { font-size: 10pt; color: #666; text-transform: uppercase; }
        .footer { margin-top: 40px; display: flex; justify-content: space-between; }
        .footer .signature { text-align: center; width: 200px; }
        .footer .signature .line { border-top: 1px solid #000; margin-top: 60px; padding-top: 5px; }
        .print-btn { position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; }
        .print-btn:hover { background: #4338ca; }
        @media print {
            body { padding: 10mm; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Cetak / PDF</button>

    @php
        $pt = \App\Models\PerguruanTinggi::getInstance();
        $hasLogo = $pt->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($pt->logo_path);
        $logoUrl = $hasLogo ? \Illuminate\Support\Facades\Storage::url($pt->logo_path) : null;
        
        // Ensure relationships are loaded
        $mahasiswa->loadMissing(['prodi.fakultas', 'prodi.kepalaProdi.user']);
        
        $namaFakultas = $mahasiswa->prodi && $mahasiswa->prodi->fakultas 
            ? strtoupper($mahasiswa->prodi->fakultas->nama) 
            : 'FAKULTAS';
        
        $namaProdi = $mahasiswa->prodi 
            ? strtoupper($mahasiswa->prodi->nama) 
            : 'PROGRAM STUDI';
        
        $kepalaProdi = $mahasiswa->prodi && $mahasiswa->prodi->kepalaProdi && $mahasiswa->prodi->kepalaProdi->user
            ? $mahasiswa->prodi->kepalaProdi->user->name
            : null;
        
        $kepalaProdiNidn = $mahasiswa->prodi && $mahasiswa->prodi->kepalaProdi
            ? $mahasiswa->prodi->kepalaProdi->nidn
            : null;
    @endphp
    <div class="header">
        @if($hasLogo)
        <div class="header-logo">
            <img src="{{ $logoUrl }}" alt="Logo" style="max-width: 80px; max-height: 80px; object-fit: contain;">
        </div>
        @endif
        <div class="header-content">
            <h1>{{ $pt->nama }}</h1>
            <h2>{{ $namaFakultas }}</h2>
            <h3>{{ $namaProdi }}</h3>
            <p style="font-size: 10pt; color: #333; margin-top: 5px;">
                @if($pt->alamat){{ $pt->alamat }}@endif
                @if($pt->kota){{ $pt->alamat ? ', ' : '' }}{{ $pt->kota }}@endif
                @if($pt->provinsi){{ ($pt->alamat || $pt->kota) ? ', ' : '' }}{{ $pt->provinsi }}@endif
                @if($pt->telepon) | Telp: {{ $pt->telepon }}@endif
            </p>
        </div>
        @if($hasLogo)
        <div class="header-logo" style="visibility: hidden; width: 80px;">
            <img src="{{ $logoUrl }}" alt="Logo" style="max-width: 80px; max-height: 80px;">
        </div>
        @endif
    </div>

    <div class="title">Kartu Hasil Studi (KHS)</div>
    <div class="subtitle">Tahun Akademik {{ $tahunAkademik->tahun }} - Semester {{ $tahunAkademik->semester }}</div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="separator">:</td>
            <td><strong>{{ $mahasiswa->user->name }}</strong></td>
            <td class="label">Program Studi</td>
            <td class="separator">:</td>
            <td>{{ $mahasiswa->prodi->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIM</td>
            <td class="separator">:</td>
            <td><strong>{{ $mahasiswa->nim }}</strong></td>
            <td class="label">Fakultas</td>
            <td class="separator">:</td>
            <td>{{ $mahasiswa->prodi->fakultas->nama ?? $mahasiswa->prodi->fakultas->nama_fakultas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Cetak</td>
            <td class="separator">:</td>
            <td>{{ now()->format('d F Y') }}</td>
            <td class="label">Dosen PA</td>
            <td class="separator">:</td>
            <td>{{ $mahasiswa->dosenPa->user->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="nilai">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 80px;">Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th style="width: 50px;">SKS</th>
                <th style="width: 60px;">Nilai Angka</th>
                <th style="width: 60px;">Nilai Huruf</th>
                <th style="width: 60px;">Bobot</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSks = 0; $totalBobot = 0; @endphp
            @forelse($nilaiList as $index => $nilai)
            @php
                $mk = $nilai->kelas->mataKuliah;
                $bobot = match($nilai->nilai_huruf) {
                    'A' => 4.0,
                    'B+' => 3.5,
                    'B' => 3.0,
                    'C+' => 2.5,
                    'C' => 2.0,
                    'D' => 1.0,
                    default => 0
                };
                $nilaiBobot = $bobot * $mk->sks;
                $totalSks += $mk->sks;
                $totalBobot += $nilaiBobot;
            @endphp
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="center">{{ $mk->kode_mk }}</td>
                <td>{{ $mk->nama_mk }}</td>
                <td class="center">{{ $mk->sks }}</td>
                <td class="center">{{ $nilai->nilai_angka ?? '-' }}</td>
                <td class="center"><strong>{{ $nilai->nilai_huruf ?? '-' }}</strong></td>
                <td class="center">{{ number_format($nilaiBobot, 1) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="center">Belum ada nilai</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="right">Total</td>
                <td class="center">{{ $totalSks }}</td>
                <td colspan="2" class="center">IPS</td>
                <td class="center"><strong>{{ number_format($ipsData['ips'], 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div class="summary-box">
            <div class="value">{{ number_format($ipsData['ips'], 2) }}</div>
            <div class="label">IPS Semester Ini</div>
        </div>
        <div class="summary-box">
            <div class="value">{{ number_format($ipkData['ips'], 2) }}</div>
            <div class="label">IPK Kumulatif</div>
        </div>
        <div class="summary-box">
            <div class="value">{{ $ipsData['total_sks'] }}</div>
            <div class="label">SKS Semester Ini</div>
        </div>
        <div class="summary-box">
            <div class="value">{{ $ipkData['total_sks'] }}</div>
            <div class="label">SKS Kumulatif</div>
        </div>
    </div>

    <div class="footer">
        <div class="signature">
            Mengetahui,<br>
            Dosen Pembimbing Akademik
            <div class="line">
                <strong>{{ $mahasiswa->dosenPa->user->name ?? '_______________________' }}</strong><br>
                NIP. ___________________
            </div>
        </div>
        <div class="signature">
            {{ $pt->kota ?? 'Kota Akademik' }}, {{ now()->format('d F Y') }}<br>
            Ketua Program Studi
            <div class="line">
                <strong>{{ $kepalaProdi ?? '_______________________' }}</strong><br>
                NIDN. {{ $kepalaProdiNidn ?? '___________________' }}
            </div>
        </div>
    </div>
</body>
</html>

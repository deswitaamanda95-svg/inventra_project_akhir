<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Perbaikan #{{ str_pad($log->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* --- Fondasi Dokumen --- */
        body { font-family: 'Arial', sans-serif; color: #1e293b; line-height: 1.5; margin: 0; padding: 0; }
        .page-wrapper { padding: 40px; }

        /* --- Header & Kop --- */
        .kop-surat { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 30px; }
        .brand-name { font-size: 22px; font-weight: 800; color: #0f172a; text-transform: uppercase; margin: 0; }
        .brand-tagline { font-size: 11px; color: #64748b; letter-spacing: 2px; margin-top: 5px; }

        /* --- Judul & Subjudul --- */
        .judul-dokumen { text-align: center; margin-bottom: 40px; }
        .judul-dokumen h1 { font-size: 18px; font-weight: 800; text-decoration: underline; margin-bottom: 5px; color: #0f172a; }
        .nomor-surat { font-size: 12px; color: #64748b; font-weight: 600; }

        /* --- Konten Naratif --- */
        .narasi { font-size: 13px; margin-bottom: 25px; text-align: justify; }

        /* --- Tabel Rincian Sistematis --- */
        .tabel-rincian { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .tabel-rincian td { padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; vertical-align: top; }
        .label { width: 35%; font-weight: 700; color: #64748b; text-transform: uppercase; font-size: 11px; }
        .isi { width: 65%; color: #1e293b; font-weight: 600; }

        /* --- Kotak Catatan --- */
        .catatan-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .catatan-box strong { font-size: 11px; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 8px; }
        .catatan-text { font-size: 13px; color: #334155; line-height: 1.6; }

        /* --- Area Tanda Tangan --- */
        .ttd-wrapper { width: 100%; margin-top: 60px; page-break-inside: avoid; }
        .ttd-kolom { width: 45%; display: inline-block; text-align: center; font-size: 13px; }
        .ttd-space { height: 80px; }
        .nama-ttd { font-weight: 800; text-decoration: underline; color: #0f172a; }
        .jabatan-ttd { font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="kop-surat">
            <h1 class="brand-name">{{ $companyName }}</h1>
            <p class="brand-tagline">Sistem Manajemen Aset & Inventaris - INVENTRA Intelligence</p>
        </div>

        <div class="judul-dokumen">
            <h1>BERITA ACARA PEMELIHARAAN ASET</h1>
            <p class="nomor-surat">Ref. No: BA/{{ date('Ymd') }}/{{ str_pad($log->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="narasi">
            Pada hari ini, <strong>{{ date('d F Y') }}</strong>, sehubungan dengan laporan kendala teknis pada aset perusahaan, telah dilaksanakan proses pemeriksaan dan perbaikan dengan rincian operasional sebagai berikut:
        </div>

        <table class="tabel-rincian">
            <tr>
                <td class="label">Nama & Spesifikasi Aset</td>
                <td class="isi">: {{ $log->item->name }}</td>
            </tr>
            <tr>
                <td class="label">Penanggung Jawab (User)</td>
                <td class="isi">: {{ $log->user->name ?? 'Sistem Inventaris' }}</td>
            </tr>
            <tr>
                <td class="label">Teknisi / Pihak Vendor</td>
                <td class="isi">: {{ $log->technician_name }}</td>
            </tr>
            <tr>
                <td class="label">Periode Pengerjaan</td>
                <td class="isi">: {{ $log->start_date->format('d M Y') }} s/d {{ $log->updated_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="label">Total Biaya Pemeliharaan</td>
                <td class="isi">: Rp {{ number_format($log->repair_cost, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="catatan-box">
            <strong>Ringkasan Perbaikan & Kerusakan:</strong>
            <div class="catatan-text">
                {{ $log->damage_note }}
            </div>
        </div>

        <div class="narasi">
            Seluruh proses pemeliharaan telah dinyatakan selesai dan aset tersebut telah dikonfirmasi berfungsi kembali sebagaimana mestinya. Berita acara ini disusun sebagai dokumen pertanggungjawaban administrasi aset perusahaan.
        </div>

        <div class="ttd-wrapper">
            <div class="ttd-kolom">
                Pihak Penanggung Jawab,<br><br>
                <div class="ttd-space"></div>
                <div class="nama-ttd">{{ $log->user->name ?? '( ____________________ )' }}</div>
                <div class="jabatan-ttd">Pengguna Aset</div>
            </div>
            <div class="ttd-kolom" style="float: right;">
                Administrator Inventaris,<br><br>
                <div class="ttd-space"></div>
                <div class="nama-ttd">{{ Auth::user()->name }}</div>
                <div class="jabatan-ttd">Divisi Logistik & Aset</div>
            </div>
        </div>
    </div>
</body>
</html>
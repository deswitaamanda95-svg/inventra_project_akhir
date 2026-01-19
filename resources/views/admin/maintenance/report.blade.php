<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemeliharaan - {{ $companyName }}</title>
    <style>
        /* --- Fondasi & Tipografi --- */
        @page { margin: 1cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1e293b; 
            line-height: 1.5; 
            margin: 0; 
            padding: 0; 
        }

        /* --- Header & Kop Surat Modern --- */
        .kop-header { 
            border-bottom: 2px solid #0f172a; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand-title { font-size: 24px; font-weight: bold; color: #0f172a; text-transform: uppercase; margin: 0; }
        .brand-subtitle { font-size: 10px; color: #64748b; letter-spacing: 1px; margin-top: 5px; text-transform: uppercase; }
        
        /* --- Judul Laporan --- */
        .judul-laporan { text-align: center; margin-bottom: 30px; }
        .judul-laporan h1 { font-size: 18px; font-weight: 800; color: #0f172a; margin: 0; text-decoration: underline; }
        .judul-laporan p { font-size: 12px; color: #64748b; margin-top: 5px; }

        /* --- Desain Tabel Sistematis --- */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; }
        th { 
            background-color: #f8fafc; 
            color: #475569; 
            font-size: 10px; 
            font-weight: 800; 
            text-transform: uppercase; 
            padding: 12px 8px; 
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        td { 
            padding: 10px 8px; 
            font-size: 11px; 
            border: 1px solid #e2e8f0; 
            vertical-align: top;
            word-wrap: break-word;
        }
        tr:nth-child(even) { background-color: #fcfdfe; }

        /* --- Komponen Status & Angka --- */
        .status-tag { 
            font-size: 9px; 
            font-weight: bold; 
            padding: 3px 6px; 
            border-radius: 4px; 
            text-align: center;
            display: inline-block;
        }
        .status-paid { background-color: #dcfce7; color: #16a34a; }
        .status-pending { background-color: #fef2f2; color: #dc2626; }
        
        .biaya { text-align: right; font-family: 'Courier', monospace; font-weight: bold; }

        /* --- Footer --- */
        .footer { 
            margin-top: 50px; 
            font-size: 10px; 
            color: #94a3b8; 
            width: 100%;
        }
        .signature-area { 
            float: right; 
            width: 200px; 
            text-align: center; 
            color: #1e293b;
            margin-top: 20px;
        }
        .signature-space { height: 60px; }
    </style>
</head>
<body>

    <div class="kop-header">
        <div>
            <h1 class="brand-title">{{ $companyName }}</h1>
            <p class="brand-subtitle">Inventra Intelligence - Asset Management System</p>
        </div>
    </div>

    <div class="judul-laporan">
        <h1>REKAPITULASI PEMELIHARAAN ASET</h1>
        <p>Periode Laporan: {{ date('d F Y') }}</p>
    </div>

    

    <table>
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th style="width: 120px;">Deskripsi Aset</th>
                <th>Detail Kendala</th>
                <th style="width: 100px;">Vendor/Teknisi</th>
                <th style="width: 100px;">Penanggung Jawab</th>
                <th style="width: 90px; text-align: right;">Investasi (IDR)</th>
                <th style="width: 70px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalInvestasi = 0; @endphp
            @foreach($logs as $log)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td><strong>{{ $item->name ?? $log->item->name }}</strong></td>
                <td>{{ $log->damage_note }}</td>
                <td>{{ $log->technician_name }}</td>
                <td>{{ $log->user->name ?? 'SISTEM' }}</td>
                <td class="biaya">{{ number_format($log->repair_cost, 0, ',', '.') }}</td>
                <td style="text-align: center;">
                    <span class="status-tag {{ $log->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ strtoupper($log->payment_status == 'paid' ? 'Lunas' : 'Tertunda') }}
                    </span>
                </td>
            </tr>
            @php $totalInvestasi += $log->repair_cost; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: 800; background: #f8fafc;">TOTAL SELURUHNYA</td>
                <td class="biaya" style="background: #f8fafc; color: #2563eb;">{{ number_format($totalInvestasi, 0, ',', '.') }}</td>
                <td style="background: #f8fafc;"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div style="float: left;">
            <p>ID Dokumen: <strong>INV/{{ date('Ymd') }}/{{ strtoupper(Str::random(5)) }}</strong></p>
            <p>Dicetak otomatis oleh Sistem Inventra pada: {{ date('d/m/Y H:i') }}</p>
        </div>
        
        <div class="signature-area">
            <p>Otoritas Pengelola Aset,</p>
            <div class="signature-space"></div>
            <p><strong>__________________________</strong></p>
            <p>Logistik & Pemeliharaan</p>
        </div>
    </div>

</body>
</html>
@extends('layouts.admin')

@section('content')
<style>
    .page-header { margin-bottom: 28px; }
    .page-title { font-size: 24px; font-weight: 800; color: #1e293b; letter-spacing: -0.02em; }
    .page-subtitle { color: #94a3b8; font-size: 14px; margin-top: 4px; }

    .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px; }
    .card-pro { background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; position: relative; transition: all 0.2s ease; cursor: pointer; text-decoration: none; display: block; }
    .card-pro:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
    .card-pro .label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
    .card-pro .value { font-size: 28px; font-weight: 800; color: #1e293b; display: block; margin: 6px 0; }
    .status-pill { font-size: 9px; font-weight: 700; padding: 3px 8px; border-radius: 6px; display: inline-block; }

    /* Gaya Grafik */
    .chart-container { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 32px; }

    .feed-container { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; }
    .feed-item { display: flex; align-items: center; gap: 15px; padding: 16px 0; border-bottom: 1px solid #f1f5f9; }
    .avatar-mini { width: 38px; height: 38px; border-radius: 10px; background: #f8fafc; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #64748b; border: 1px solid #e2e8f0; flex-shrink: 0; }
</style>

<div class="page-header">
    <h2 class="page-title">Analisis Operasional</h2>
    <p class="page-subtitle">Pantau sirkulasi aset dan efektivitas manajemen inventaris secara real-time.</p>
</div>

{{-- GRID STATISTIK --}}
<div class="stat-grid">
    <a href="{{ route('admin.loans.index', ['status' => 'pending']) }}" class="card-pro">
        <div style="height:4px; background:#f59e0b; position:absolute; top:0; left:0; width:100%; border-radius:12px 12px 0 0;"></div>
        <span class="label">Antrean Persetujuan</span>
        <span class="value">{{ $pendingRequests }}</span>
        <span class="status-pill" style="background:#fff7ed; color:#c2410c;">Butuh Tindakan</span>
    </a>

    <a href="{{ route('admin.loans.index', ['filter' => 'overdue']) }}" class="card-pro">
        <div style="height:4px; background:#ef4444; position:absolute; top:0; left:0; width:100%; border-radius:12px 12px 0 0;"></div>
        <span class="label">Peminjaman Terlambat</span>
        <span class="value">{{ $overdueLoans }}</span>
        <span class="status-pill" style="background:#fef2f2; color:#dc2626;">Perlu Perhatian</span>
    </a>

    <a href="{{ route('admin.items.index', ['condition' => 'Good']) }}" class="card-pro">
        <div style="height:4px; background:#10b981; position:absolute; top:0; left:0; width:100%; border-radius:12px 12px 0 0;"></div>
        <span class="label">Aset Siap Pakai</span>
        <span class="value">{{ $availableItems }}</span>
        <span class="status-pill" style="background:#f0fdf4; color:#16a34a;">Kondisi Prima</span>
    </a>

    <a href="{{ route('admin.items.index', ['condition' => 'Repair']) }}" class="card-pro">
        <div style="height:4px; background:#3b82f6; position:absolute; top:0; left:0; width:100%; border-radius:12px 12px 0 0;"></div>
        <span class="label">Sedang Diperbaiki</span>
        <span class="value">{{ $needRepair }}</span>
        <span class="status-pill" style="background:#eff6ff; color:#1d4ed8;">Proses Servis</span>
    </a>
</div>

{{-- ANALISIS VISUAL: GRAFIK --}}
<div class="chart-container">
    <h3 style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <svg style="width:18px; color:#2563eb" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Tren Aktivitas Peminjaman (7 Hari Terakhir)
    </h3>
    <canvas id="activityChart" height="100"></canvas>
</div>

{{-- AKTIVITAS TERBARU --}}
<div class="feed-container">
    <h3 style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <svg style="width:16px; color:#2563eb" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Log Aktivitas Terbaru
    </h3>

    @forelse($recentActivities as $activity)
    <div class="feed-item">
        <div class="avatar-mini">{{ strtoupper(substr($activity->user->name, 0, 1)) }}</div>
        <div style="flex: 1;">
            <div style="font-size: 13px; font-weight: 700; color: #1e293b;">
                {{ $activity->user->name }} 
                <span style="font-weight: 400; color: #94a3b8;">mengajukan peminjaman</span> 
                {{ $activity->item->name }}
            </div>
        </div>
        @php
            $statusIndo = [
                'pending' => 'MENUNGGU',
                'borrowed' => 'AKTIF',
                'returned' => 'KEMBALI',
            ][$activity->status] ?? strtoupper($activity->status);
        @endphp
        <span style="font-size: 10px; font-weight: 800; padding: 4px 10px; border-radius: 6px; text-transform: uppercase; background: #eff6ff; color: #2563eb; border: 1px solid #2563eb20;">{{ $statusIndo }}</span>
    </div>
    @empty
    <p style="text-align: center; color: #94a3b8; font-size: 12px; padding: 20px;">Belum ada aktivitas sirkulasi yang tercatat.</p>
    @endforelse
</div>

{{-- SKRIP GRAFIK --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Volume Peminjaman',
                data: [12, 19, 3, 5, 2, 3, 7], 
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.05)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#2563eb'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection
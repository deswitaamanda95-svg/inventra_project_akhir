@extends('layouts.admin')

@section('content')
<style>
    /* --- Tata Letak & Area Scroll --- */
    .maintenance-wrapper { 
        display: grid; 
        grid-template-columns: 1fr 380px; 
        gap: 32px; 
        height: calc(100vh - 180px); 
        align-items: start;
    }
    
    .log-container {
        overflow-y: auto;
        padding-right: 15px;
        height: 100%;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .log-container::-webkit-scrollbar { width: 5px; }
    .log-container::-webkit-scrollbar-track { background: transparent; }
    .log-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    /* --- Desain Kartu Pemeliharaan --- */
    .log-card {
        background: #ffffff; border-radius: 16px; border: 1px solid #f1f5f9;
        padding: 24px; margin-bottom: 20px; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .log-card:hover { border-color: #3b82f6; box-shadow: 0 10px 20px -5px rgba(0,0,0,0.04); }

    .text-title { font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; }
    .text-desc { font-size: 14px; color: #64748b; margin: 6px 0 16px 0; line-height: 1.6; }
    
    .meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; padding-top: 15px; border-top: 1px solid #f8fafc; }
    .meta-item { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
    .meta-item b { color: #475569; display: block; font-size: 12px; margin-top: 2px; }

    /* --- Label Status Premium --- */
    .status-pill {
        padding: 5px 12px; border-radius: 8px; font-size: 10px; font-weight: 800;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .pill-ongoing { background: #fffbeb; color: #92400e; }
    .pill-payment { background: #fef2f2; color: #b91c1c; }
    .pill-fixed { background: #f0fdf4; color: #15803d; }

    /* --- Tombol Sistematis --- */
    .btn-sys {
        padding: 8px 14px; border-radius: 8px; font-size: 11px; font-weight: 700;
        cursor: pointer; border: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-dark-mini { background: #0f172a; color: white; }
    .btn-success-mini { background: #10b981; color: white; }
    .btn-outline-mini { 
        background: white; border: 1px solid #e2e8f0; color: #64748b; 
        text-decoration: none;
    }
    .btn-outline-mini:hover { border-color: #3b82f6; color: #3b82f6; background: #f0f7ff; }

    /* --- Sidebar Input --- */
    .sidebar-form {
        background: #ffffff; border-radius: 16px; border: 1px solid #f1f5f9;
        padding: 30px; position: sticky; top: 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .input-sys {
        width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0;
        font-size: 13px; background: #f8fafc; outline: none; transition: 0.2s;
    }
    .input-sys:focus { border-color: #3b82f6; background: white; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
</style>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
    <div>
        <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin: 0;">Log Pemeliharaan</h2>
        <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Pantau status perbaikan dan administrasi biaya aset secara real-time.</p>
    </div>

    <form action="{{ route('admin.maintenance.export') }}" method="GET" style="display: flex; gap: 10px;">
        <select name="user_id" class="input-sys" style="width: 220px;" onchange="window.location.href='/admin/maintenance?user_id=' + this.value">
            <option value="">-- Cari Penanggung Jawab --</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-sys btn-dark-mini" style="padding: 0 20px;">CETAK LAPORAN</button>
    </form>
</div>

<div class="maintenance-wrapper">
    {{-- AREA KARTU (SCROLLABLE) --}}
    <div class="log-container">
        @forelse($logs as $log)
        <div class="log-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div style="display: flex; gap: 20px;">
                    <div style="width: 50px; height: 50px; background: #f8fafc; border-radius: 14px; display: flex; align-items: center; justify-content: center; border: 1px solid #f1f5f9; flex-shrink: 0;">
                        <svg width="22" height="22" fill="none" stroke="#64748b" stroke-width="2.5" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-title">{{ $log->item->name }}</h4>
                        <p class="text-desc">{{ $log->damage_note }}</p>
                        
                        <a href="{{ route('admin.maintenance.print', $log->id) }}" target="_blank" class="btn-sys btn-outline-mini">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Detail
                        </a>
                    </div>
                </div>

                <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                    @if ($log->status == 'ongoing')
                        <span class="status-pill pill-ongoing">SEDANG DIPROSES</span>
                    @elseif ($log->status == 'fixed' && $log->payment_status == 'pending')
                        <span class="status-pill pill-payment">MENUNGGU PEMBAYARAN</span>
                        <form action="{{ route('admin.maintenance.pay', $log->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-sys btn-success-mini">KONFIRMASI LUNAS</button>
                        </form>
                    @else
                        <span class="status-pill pill-fixed">PERBAIKAN SELESAI</span>
                    @endif
                </div>
            </div>

            <div class="meta-grid">
                <div class="meta-item">Penanggung Jawab: <b>{{ $log->user->name ?? 'SISTEM' }}</b></div>
                <div class="meta-item">Vendor/Teknisi: <b>{{ $log->technician_name ?? 'Internal' }}</b></div>
            </div>

            @if($log->status != 'fixed')
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px dashed #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 11px; color: #94a3b8; font-weight: 800;">EST. SELESAI: <span style="color: #475569;">{{ $log->estimated_finish ? $log->estimated_finish->format('d M Y') : '-' }}</span></span>
                <form action="{{ route('admin.maintenance.finish', $log->id) }}" method="POST" style="display: flex; gap: 10px;">
                    @csrf
                    <input type="text" name="repair_cost" placeholder="Rp 0" required class="input-sys" style="width: 130px; padding: 8px;" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <button type="submit" class="btn-sys btn-dark-mini">SELESAIKAN</button>
                </form>
            </div>
            @else
            <div style="margin-top: 15px; text-align: right; font-size: 15px; font-weight: 800; color: #1e293b; border-top: 1px solid #f8fafc; padding-top: 15px;">
                Total Biaya: Rp {{ number_format($log->repair_cost, 0, ',', '.') }}
            </div>
            @endif
        </div>
        @empty
        <div style="text-align: center; padding: 80px 40px; color: #94a3b8; background: white; border-radius: 16px; border: 2px dashed #f1f5f9;">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 16px; opacity: 0.5;"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p style="font-size: 14px; font-weight: 700;">Belum ada catatan pemeliharaan.</p>
        </div>
        @endforelse
    </div>

    {{-- SIDEBAR FORM (STICKY) --}}
    <aside class="sidebar-form">
        <h4 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 25px;">Catat Log Baru</h4>
        <form action="{{ route('admin.maintenance.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="font-size: 10px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 6px; letter-spacing: 0.05em;">PILIH ASET</label>
                <select name="item_id" required class="input-sys">
                    <option value="">-- Pilih Barang --</option>
                    @foreach($itemsInRepair as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 10px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 6px; letter-spacing: 0.05em;">KENDALA / KERUSAKAN</label>
                <textarea name="damage_note" required class="input-sys" style="height: 80px; resize: none;" placeholder="Jelaskan detail masalah aset..."></textarea>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="font-size: 10px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 6px; letter-spacing: 0.05em;">NAMA VENDOR / TEKNISI</label>
                <input type="text" name="technician_name" required class="input-sys" placeholder="Nama bengkel atau teknisi">
            </div>
            <div style="margin-bottom: 25px;">
                <label style="font-size: 10px; font-weight: 800; color: #94a3b8; display: block; margin-bottom: 6px; letter-spacing: 0.05em;">TANGGAL MASUK</label>
                <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="input-sys">
            </div>
            <button type="submit" class="btn-sys btn-dark-mini" style="width: 100%; padding: 14px; justify-content: center; font-size: 13px;">SIMPAN LOG SERVIS</button>
        </form>
    </aside>
</div>
@endsection
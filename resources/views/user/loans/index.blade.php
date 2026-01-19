@extends('layouts.admin') {{-- Pastikan layout sudah sesuai --}}

@section('content')
<style>
    /* --- 1. Header Hero --- */
    .page-hero {
        background: white; padding: 40px; border-radius: 24px; border: 1px solid #f1f5f9;
        margin-bottom: 35px; position: relative; overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .page-hero::after {
        content: ""; position: absolute; top: -30px; right: -30px; width: 150px; height: 150px;
        background: #f8fafc; border-radius: 50%; z-index: 0;
    }
    .hero-content { position: relative; z-index: 1; }
    .page-title { font-size: 28px; font-weight: 800; color: #0f172a; letter-spacing: -0.03em; margin: 0; }
    .page-subtitle { color: #64748b; font-size: 15px; margin-top: 5px; font-weight: 500; }

    /* --- 2. Arsitektur Tabel --- */
    .table-card {
        background: white; border-radius: 24px; border: 1px solid #f1f5f9;
        overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .modern-table { width: 100%; border-collapse: collapse; text-align: left; }
    .modern-table th {
        background: #f8fafc; padding: 18px 24px; font-size: 10px; font-weight: 800;
        color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; border-bottom: 1px solid #f1f5f9;
    }
    .modern-table td { padding: 20px 24px; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
    .modern-table tr:last-child td { border-bottom: none; }
    .modern-table tr:hover { background: #fcfdfe; }

    /* --- 3. Identitas Aset --- */
    .asset-info { display: flex; align-items: center; gap: 15px; }
    .asset-thumb {
        width: 48px; height: 48px; border-radius: 12px; background: #f8fafc;
        display: flex; align-items: center; justify-content: center; 
        color: #cbd5e1; border: 1px solid #f1f5f9; overflow: hidden; flex-shrink: 0;
    }
    .asset-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .asset-name { font-size: 15px; font-weight: 700; color: #1e293b; display: block; }
    .asset-id { font-size: 11px; color: #94a3b8; font-weight: 600; font-family: 'JetBrains Mono', monospace; }

    /* --- 4. Badge Status --- */
    .status-pill {
        display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px;
        border-radius: 10px; font-size: 10px; font-weight: 800; letter-spacing: 0.02em;
    }
    .status-pending { background: #fffbeb; color: #b45309; }
    .status-active { background: #eff6ff; color: #2563eb; }
    .status-returned { background: #f0fdf4; color: #16a34a; }
    .status-rejected { background: #fef2f2; color: #b91c1c; }
    .status-verifying { background: #faf5ff; color: #9333ea; }

    /* --- 5. Tombol Aksi --- */
    .btn-action {
        padding: 10px 18px; border-radius: 10px; font-size: 11px; font-weight: 700;
        cursor: pointer; transition: 0.3s; display: flex; align-items: center; 
        gap: 8px; text-decoration: none; border: none;
    }
    .btn-return { background: #0f172a; color: white; }
    .btn-return:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3); }
    
    .btn-cancel { background: #f1f5f9; color: #64748b; }
    .btn-cancel:hover { background: #fef2f2; color: #ef4444; }
</style>

<div style="margin-bottom: 30px;">
    <a href="{{ route('user.items.index') }}" style="text-decoration: none; color: #94a3b8; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; transition: 0.2s;">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
        KEMBALI KE KATALOG
    </a>
</div>

<div class="page-hero">
    <div class="hero-content">
        <h2 class="page-title">Pinjaman Saya</h2>
        <p class="page-subtitle">Pantau status pengajuan, tenggat waktu, dan riwayat sirkulasi aset Anda.</p>
    </div>
</div>

<div class="table-card">
    <table class="modern-table">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center;">No</th>
                <th>Aset / Barang</th>
                <th>Tanggal Pinjam</th>
                <th>Batas Pengembalian</th>
                <th>Status Berjalan</th>
                <th style="text-align: center;">Opsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
            <tr>
                <td style="text-align: center; color: #cbd5e1; font-weight: 800;">{{ $loop->iteration }}</td>
                <td>
                    <div class="asset-info">
                        <div class="asset-thumb">
                            @if($loan->item->image)
                                <img src="{{ asset('storage/' . $loan->item->image) }}" alt="Aset">
                            @else
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            @endif
                        </div>
                        <div>
                            <span class="asset-name">{{ $loan->item->name }}</span>
                            <span class="asset-id">#ID-{{ str_pad($loan->item->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </td>
                <td style="font-size: 14px; color: #475569; font-weight: 600;">
                    {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                </td>
                <td style="font-size: 14px; font-weight: 700;">
                    @if($loan->due_date)
                        @php $isOverdue = \Carbon\Carbon::parse($loan->due_date)->isPast() && $loan->status == 'borrowed'; @endphp
                        <span style="{{ $isOverdue ? 'color: #ef4444;' : 'color: #1e293b;' }}">
                            {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                            @if($isOverdue) <small style="display: block; font-size: 9px; text-transform: uppercase; font-weight: 800;">⚠️ Terlambat</small> @endif
                        </span>
                    @else
                        <span style="color: #cbd5e1; font-style: italic; font-weight: 500;">Belum ditentukan</span>
                    @endif
                </td>
                <td>
                    @php
                        $statusClass = [
                            'pending'        => 'status-pending',
                            'borrowed'       => 'status-active',
                            'returned'       => 'status-returned',
                            'rejected'       => 'status-rejected',
                            'pending_return' => 'status-verifying',
                        ][$loan->status] ?? '';

                        $statusText = [
                            'pending'        => 'MENUNGGU PERSETUJUAN',
                            'borrowed'       => 'DALAM PENGGUNAAN',
                            'returned'       => 'TELAH DIKEMBALIKAN',
                            'rejected'       => 'PENGAJUAN DITOLAK',
                            'pending_return' => 'PROSES VERIFIKASI',
                        ][$loan->status] ?? strtoupper($loan->status);
                    @endphp
                    <span class="status-pill {{ $statusClass }}">
                        <div style="width: 5px; height: 5px; border-radius: 50%; background: currentColor;"></div>
                        {{ $statusText }}
                    </span>
                </td>
                <td style="text-align: center;">
                    @if($loan->status == 'pending')
                        <form action="{{ route('user.loans.cancel', $loan->id) }}" method="POST" class="cancel-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-action btn-cancel btn-cancel-trigger">
                                BATALKAN
                            </button>
                        </form>
                    
                    @elseif($loan->status == 'borrowed')
                        <form action="{{ route('user.loans.return', $loan->id) }}" method="POST" class="return-form" style="display: inline-flex;">
                            @csrf
                            <button type="button" class="btn-action btn-return btn-return-trigger">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M16 15l-4 4m0 0l-4-4m4 4V9"></path></svg>
                                Kembalikan
                            </button>
                        </form>
                    
                    @elseif($loan->status == 'pending_return')
                        <span style="color: #9333ea; font-size: 11px; font-weight: 800; text-transform: uppercase;">Menunggu Admin</span>
                    @else
                        <span style="color: #cbd5e1; font-weight: 800;">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding: 100px 24px; text-align: center;">
                    <div style="color: #f1f5f9; margin-bottom: 15px;">
                        <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"></path></svg>
                    </div>
                    <div style="font-size: 16px; font-weight: 800; color: #94a3b8;">Belum Ada Riwayat Pinjam</div>
                    <p style="color: #cbd5e1; font-size: 13px; margin-top: 5px; font-weight: 500;">Mulai pinjam aset kantor melalui katalog barang.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('click', function (e) {
        // Konfirmasi Pengembalian
        const returnBtn = e.target.closest('.btn-return-trigger');
        if (returnBtn) {
            e.preventDefault();
            const form = returnBtn.closest('.return-form');
            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                text: "Apakah Anda yakin ingin mengembalikan aset ini sekarang?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }

        // Konfirmasi Pembatalan
        const cancelBtn = e.target.closest('.btn-cancel-trigger');
        if (cancelBtn) {
            e.preventDefault();
            const form = cancelBtn.closest('.cancel-form');
            Swal.fire({
                title: 'Batalkan Pengajuan?',
                text: "Permintaan peminjaman ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tutup',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }
    });
</script>
@endsection
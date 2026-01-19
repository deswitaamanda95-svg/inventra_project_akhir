@extends('layouts.admin')

@section('content')
    <style>
        .header-halaman { display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; }
        .judul-halaman { font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
        .wadah-tabel { background: white; border-radius: 16px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); }
        .tabel-modern { width: 100%; border-collapse: collapse; }
        .tabel-modern th { background: #f8fafc; padding: 16px 24px; text-align: left; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #f1f5f9; }
        .tabel-modern td { padding: 16px 24px; border-bottom: 1px solid #f8fafc; font-size: 14px; vertical-align: middle; color: #475569; }
        .pill-status { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 100px; font-size: 10px; font-weight: 800; }
        .s-aktif { background: #eff6ff; color: #2563eb; }
        .s-menunggu { background: #fffbeb; color: #d97706; }
        .s-selesai { background: #f0fdf4; color: #16a34a; }
        .s-terlambat { background: #fef2f2; color: #ef4444; }
        .btn-aksi-garis { padding: 7px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; border: 1.5px solid #e2e8f0; color: #64748b; background: white; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
        .btn-utama-sm { background: #0f172a; color: white !important; border: none; cursor: pointer; }
        .input-modern { width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 14px; font-weight: 600; outline: none; background: #f8fafc; }
        .label-form-kecil { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 8px; }
    </style>

    <div class="header-halaman">
        <div>
            <h2 class="judul-halaman">Riwayat Distribusi Aset</h2>
            <p style="color: #64748b;">Kelola otorisasi peminjaman dan konfirmasi pengembalian aset.</p>
        </div>
    </div>

    <div class="wadah-tabel">
        <table class="tabel-modern">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th style="width: 25%;">Aset / Barang</th>
                    <th style="width: 20%;">Peminjam</th>
                    <th style="width: 20%;">Jadwal</th>
                    <th>Status Berjalan</th>
                    <th style="text-align: right;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="{{ $loan->item->image ? asset('storage/' . $loan->item->image) : asset('images/placeholder.png') }}"
                                    style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                <span style="font-weight: 700;">{{ $loan->item->name }}</span>
                            </div>
                        </td>
                        <td>{{ $loan->user->name }}</td>
                        <td><small>Batas: {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</small></td>
                        <td>
                            @php
                                $isOverdue = $loan->status == 'borrowed' && \Carbon\Carbon::parse($loan->due_date)->isPast();
                                $statusMap = [
                                    'pending' => ['label' => 'MENUNGGU IZIN', 'class' => 's-menunggu'],
                                    'borrowed' => [
                                        'label' => $isOverdue ? 'TERLAMBAT' : 'DIPINJAM',
                                        'class' => $isOverdue ? 's-terlambat' : 's-aktif',
                                    ],
                                    'pending_return' => ['label' => 'PROSES KEMBALI', 'class' => 's-menunggu'],
                                    'returned' => ['label' => 'DIKEMBALIKAN', 'class' => 's-selesai'],
                                ];
                                $curr = $statusMap[$loan->status] ?? ['label' => strtoupper($loan->status), 'class' => 's-menunggu'];
                            @endphp
                            <span class="pill-status {{ $curr['class'] }}">{{ $curr['label'] }}</span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                @if ($loan->status == 'pending')
                                    <button onclick="openApproveModal({{ $loan->id }}, '{{ $loan->user->name }}', '{{ $loan->item->name }}')"
                                        class="btn-aksi-garis btn-utama-sm">SETUJUI</button>
                                @elseif($loan->status == 'pending_return')
                                    <button onclick="openReturnModal({{ $loan->id }}, '{{ $loan->user->name }}', '{{ $loan->item->name }}')"
                                        class="btn-aksi-garis btn-utama-sm" style="background: #2563eb;">KONFIRMASI SELESAI</button>
                                @elseif($loan->status == 'borrowed')
                                    <button onclick="openReturnModal({{ $loan->id }}, '{{ $loan->user->name }}', '{{ $loan->item->name }}')"
                                        class="btn-aksi-garis">PAKSA KEMBALI</button>
                                @else
                                    <span style="color: #10b981; font-weight: 800; font-size: 11px;">✔ TERARSIP</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align: center; padding: 50px; color: #94a3b8;">Belum ada data distribusi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL PENGEMBALIAN --}}
    <div id="returnModal" style="display:none; position:fixed; inset:0; background:rgba(15, 23, 42, 0.5); backdrop-filter:blur(10px); z-index:999; align-items:center; justify-content:center; padding: 20px;">
        {{-- KOTAK PUTIH --}}
        <div style="background:white; width: 100%; max-width: 480px; border-radius: 32px; padding: 40px; box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25); border: 1px solid rgba(226, 232, 240, 0.8);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h4 style="margin: 0; font-size: 22px; font-weight: 800; color: #0f172a;">Validasi Kondisi Aset</h4>
                <p id="returnInfo" style="font-size: 14px; color: #64748b; margin-top: 10px; line-height: 1.6;"></p>
            </div>

            <form id="returnForm" method="POST">
                @csrf
                <div style="margin-bottom: 25px;">
                    <label class="label-form-kecil">Kondisi Barang</label>
                    <select name="return_condition" id="returnConditionSelect" onchange="toggleRepairInput()" class="input-modern" style="appearance: auto; background: white;">
                        <option value="Good">🟢 Normal (Siap digunakan kembali)</option>
                        <option value="Repair">🟡 Perlu Servis (Kerusakan Minor)</option>
                        <option value="Broken">🔴 Rusak Total (Tidak bisa digunakan)</option>
                    </select>
                </div>

                {{-- SECTION INPUT KERUSAKAN --}}
                <div id="repairInputSection" style="display: none; background: #f8fafc; padding: 25px; border-radius: 24px; border: 2px dashed #cbd5e1; margin-bottom: 30px;">
                    <div style="margin-bottom: 18px;">
                        <label class="label-form-kecil" style="color: #ef4444;">Estimasi Biaya Perbaikan (Rp)</label>
                        <input type="number" name="repair_cost" id="repairCostInput" class="input-modern" value="0">
                    </div>
                    <div style="margin-bottom: 18px;">
                        <label class="label-form-kecil">Vendor / Teknisi</label>
                        <input type="text" name="technician_name" id="technicianNameInput" class="input-modern" placeholder="Nama bengkel atau teknisi">
                    </div>
                    <div>
                        <label class="label-form-kecil">Catatan Detail Kerusakan</label>
                        <textarea name="damage_note" id="damageNoteInput" class="input-modern" rows="3" style="resize: none;" placeholder="Jelaskan kendala barang..."></textarea>
                    </div>
                </div>

                {{-- TATA LETAK TOMBOL SEJAJAR --}}
                <div style="display: flex; gap: 15px;">
                    <button type="button" onclick="closeReturnModal()" class="btn-aksi-garis" style="flex: 1; justify-content: center; height: 52px; border-radius: 16px; font-weight: 700;">Batal</button>
                    <button type="submit" class="btn-utama-sm" style="flex: 1.5; justify-content: center; height: 52px; border-radius: 16px; font-weight: 700;">Konfirmasi Selesai</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PERSETUJUAN --}}
    <div id="approveModal" style="display:none; position:fixed; inset:0; background:rgba(15, 23, 42, 0.4); backdrop-filter:blur(6px); z-index:999; align-items:center; justify-content:center; padding: 20px;">
        <div style="background:white; width: 100%; max-width: 400px; border-radius: 24px; padding: 35px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3);">
            <h4 style="font-weight: 800;">Otorisasi Peminjaman</h4>
            <p id="approveInfo" style="font-size: 14px; margin-bottom: 20px;"></p>
            <form id="approveForm" method="POST">
                @csrf
                <label class="label-form-kecil">BATAS KEMBALI</label>
                <input type="date" name="due_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="input-modern" style="margin-bottom: 20px;">
                <div style="display: flex; gap: 12px;">
                    <button type="button" onclick="closeApproveModal()" class="btn-aksi-garis" style="flex: 1; justify-content: center; height: 45px; border-radius: 12px;">Batal</button>
                    <button type="submit" class="btn-utama-sm" style="flex: 1; border-radius: 12px; background: #2563eb; justify-content: center; height: 45px;">Setujui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id, user, item) {
            document.getElementById('approveInfo').innerHTML = `Setujui peminjaman <strong>${item}</strong> untuk <strong>${user}</strong>?`;
            document.getElementById('approveForm').action = `/admin/loans/${id}/approve`;
            document.getElementById('approveModal').style.display = 'flex';
        }

        function openReturnModal(id, user, item) {
            document.getElementById('returnInfo').innerHTML = `Tentukan kondisi aset <strong>${item}</strong> yang dikembalikan oleh <strong>${user}</strong>.`;
            document.getElementById('returnForm').action = `/admin/loans/${id}/confirm-return`;
            document.getElementById('returnModal').style.display = 'flex';
            document.getElementById('returnConditionSelect').value = "Good";
            toggleRepairInput();
        }

        function toggleRepairInput() {
            const condition = document.getElementById('returnConditionSelect').value;
            const section = document.getElementById('repairInputSection');
            const noteInput = document.getElementById('damageNoteInput');
            const costInput = document.getElementById('repairCostInput');
            const technicianInput = document.getElementById('technicianNameInput');

            if (condition === 'Repair' || condition === 'Broken') {
                section.style.display = 'block';
                // Aktifkan input & validasi
                noteInput.required = true;
                noteInput.disabled = false;
                costInput.required = true;
                costInput.disabled = false;
                technicianInput.disabled = false;
            } else {
                section.style.display = 'none';
                // Nonaktifkan total agar tidak memblokir tombol (Solusi "Normal" gak bisa diklik)
                noteInput.required = false;
                noteInput.disabled = true; 
                costInput.required = false;
                costInput.disabled = true;
                technicianInput.disabled = true;
                // Reset nilai
                noteInput.value = "";
                technicianInput.value = "";
                costInput.value = 0;
            }
        }

        function closeApproveModal() { document.getElementById('approveModal').style.display = 'none'; }
        function closeReturnModal() { document.getElementById('returnModal').style.display = 'none'; }
    </script>
@endsection
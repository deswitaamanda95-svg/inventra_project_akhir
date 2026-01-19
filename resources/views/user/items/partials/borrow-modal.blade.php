{{-- MODAL PEMINJAMAN --}}
<div id="borrowModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); z-index:9999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:white; width:100%; max-width:450px; border-radius:24px; padding:40px; position:relative; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        
        <h3 id="modalItemName" style="font-size:22px; font-weight:800; color:#0f172a; margin:0 0 8px 0;">Request Loan</h3>
        <p style="color:#64748b; font-size:14px; margin-bottom:30px;">Tentukan jumlah unit dan tanggal pengembalian aset ini.</p>

        <form id="borrowForm" method="POST">
            @csrf
            {{-- Input Jumlah --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; margin-bottom:8px;">Quantity to Borrow</label>
                <input type="number" name="quantity_borrowed" id="modalMaxQty" class="modal-input" value="1" min="1" required 
                       style="width:100%; padding:12px; border-radius:12px; border:1px solid #e2e8f0; font-size: 14px; outline: none;">
                <small id="qtyHint" style="color:#94a3b8; font-size:11px; margin-top:6px; display:block;"></small>
            </div>

            {{-- Input Tanggal Kembali --}}
            <div style="margin-bottom:30px;">
                <label style="display:block; font-size:11px; font-weight:800; color:#94a3b8; text-transform:uppercase; margin-bottom:8px;">Return Due Date</label>
                <input type="date" name="due_date" class="modal-input" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       style="width:100%; padding:12px; border-radius:12px; border:1px solid #e2e8f0; font-size: 14px; outline: none;">
                <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Pastikan mengembalikan barang tepat waktu sesuai aturan.</small>
            </div>

            <div style="display:flex; gap:12px;">
                <button type="button" onclick="closeBorrowModal()" style="flex:1; background:#f1f5f9; color:#64748b; border:none; padding:14px; border-radius:12px; font-weight:700; cursor:pointer; transition: 0.2s;">Cancel</button>
                <button type="submit" style="flex:2; background:#2563eb; color:white; border:none; padding:14px; border-radius:12px; font-weight:700; cursor:pointer; transition: 0.2s;">Confirm Request</button>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Membuka modal dan menyesuaikan data item secara dinamis
     */
    function openBorrowModal(id, name, maxQty) {
        const modal = document.getElementById('borrowModal');
        const form = document.getElementById('borrowForm');
        
        document.getElementById('modalItemName').innerText = "Borrowing: " + name;
        document.getElementById('modalMaxQty').max = maxQty;
        document.getElementById('qtyHint').innerText = "Max available units: " + maxQty + " PCS";
        
        // Sesuaikan endpoint form dengan ID item yang diklik
        form.action = `/user/items/${id}/borrow`; 
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; 
    }

    function closeBorrowModal() {
        document.getElementById('borrowModal').style.display = 'none';
        document.body.style.overflow = 'auto'; 
    }

    // Menutup modal saat area luar card diklik
    window.onclick = function(event) {
        const modal = document.getElementById('borrowModal');
        if (event.target == modal) closeBorrowModal();
    }
</script>
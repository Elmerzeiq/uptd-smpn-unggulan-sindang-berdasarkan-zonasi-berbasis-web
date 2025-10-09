<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Pilih aksi yang akan diterapkan pada semua data yang Anda pilih.</p>
                <div class="mb-3">
                    <label for="bulkActionSelect" class="form-label">Aksi</label>
                    <select id="bulkActionSelect" class="form-select">
                        <option value="">Pilih Aksi...</option>
                        <option value="verify_documents">Verifikasi Dokumen (Set ke Lengkap)</option>
                        <option value="verify_payment">Verifikasi Pembayaran (Set ke Lunas)</option>
                    </select>
                </div>
                <div id="bulkActionWarning" class="alert alert-warning d-none">
                    <strong>Perhatian!</strong> Aksi ini tidak dapat dibatalkan dan akan menimpa status yang ada.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="applyBulkAction">Terapkan</button>
            </div>
        </div>
    </div>
</div>

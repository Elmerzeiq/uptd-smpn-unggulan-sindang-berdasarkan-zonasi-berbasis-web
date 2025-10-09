<script>
    document.addEventListener('DOMContentLoaded', function() {
    const selectAllHeader = document.getElementById('selectAllHeader');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    if (selectAllHeader) {
        selectAllHeader.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    const applyBulkActionButton = document.getElementById('applyBulkAction');
    if(applyBulkActionButton) {
        applyBulkActionButton.addEventListener('click', function() {
            const selectedAction = document.getElementById('bulkActionSelect').value;
            const selectedIds = Array.from(rowCheckboxes)
                                   .filter(checkbox => checkbox.checked)
                                   .map(checkbox => checkbox.value);

            if (!selectedAction) {
                alert('Silakan pilih aksi terlebih dahulu.');
                return;
            }
            if (selectedIds.length === 0) {
                alert('Silakan pilih setidaknya satu data.');
                return;
            }

            if (confirm(`Anda yakin ingin menerapkan aksi "${selectedAction}" pada ${selectedIds.length} data terpilih?`)) {
                fetch('{{ route("admin.daftar-ulang.bulk-action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: selectedAction,
                        ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi saat melakukan aksi massal.');
                });
            }
        });
    }
});
</script>

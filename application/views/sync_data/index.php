<div class="container mt-4">
    <h3 class="text-center mb-4">
        <i class="fas fa-sync-alt text-primary"></i> Sinkronisasi Data Server
    </h3>

    <div class="row g-3">

        <!-- 1. Sinkronisasi SEMUA -->
        <div class="col-md-6">
            <button class="btn btn-danger w-100" onclick="sync('ambil_full')">
                🔄 Sinkron Seluruh Tabel (FULL)
            </button>
        </div>

        <!-- 2. Data POS Kasir -->
        <div class="col-md-6">
            <button class="btn btn-primary w-100" onclick="sync('ambil_semua')">
                💳 Sinkron Data POS (Kasir)
            </button>
        </div>

        <!-- 3. Produk -->
        <div class="col-md-6">
            <button class="btn btn-warning w-100" onclick="sync('ambil_produk')">
                🛍️ Sinkron Produk & Paket
            </button>
        </div>

        <!-- 4. Promo & Poin -->
        <div class="col-md-6">
            <button class="btn btn-success w-100" onclick="sync('ambil_promo')">
                🎁 Sinkron Promo, Poin & Voucher
            </button>
        </div>

        <!-- 5. Data Absensi -->
        <div class="col-md-6">
            <button class="btn btn-secondary w-100" onclick="sync('ambil_absen')">
                🧑‍💼 Sinkron Data Pegawai & Absensi
            </button>
        </div>

        <!-- 6. Data Belanja -->
        <div class="col-md-6">
            <button class="btn btn-dark w-100" onclick="sync('ambil_belanja')">
                🛒 Sinkron Data Belanja & Gudang
            </button>
        </div>

        <!-- 7. File Uploads via HTTP -->
        <div class="col-md-6">
            <button class="btn btn-info w-100" onclick="sync('sync_file_uploads')">
                🌐 Sinkron File Uploads (via Web)
            </button>
        </div>

        <!-- 8. File Uploads via SSH (rsync) -->
        <div class="col-md-6">
            <button class="btn btn-outline-secondary w-100" onclick="sync('sync_file_uploads_direct')">
                ⚙️ Sinkron File Uploads (via SSH)
            </button>
        </div>
    </div>

    <div class="mt-4">
        <h5 class="mb-2">Log Output</h5>
        <pre id="sync-result"
            style="background: #f8f9fa; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"></pre>
    </div>
</div>

<script>
function sync(action) {
    if (!confirm("Lanjutkan proses sinkron: " + action + "?")) return;

    const resultBox = document.getElementById('sync-result');
    resultBox.textContent = '⏳ Memproses sinkronisasi...';

    fetch('<?= site_url("sync_data/") ?>' + action)
        .then(res => res.json())
        .then(data => {
            resultBox.textContent = JSON.stringify(data, null, 2);
        })
        .catch(err => {
            resultBox.textContent = '❌ Error: ' + err;
        });
}
</script>
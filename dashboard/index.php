<?php
include "../config/database.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location:../auth/login.php");
    exit();
}

include "query.php";
if (isset($_SESSION['page_catat'])) {
    unset($_SESSION['page_catat']);
    $_SESSION['page_dashboard'] = true;
} else {
    $_SESSION['page_dashboard'] = true;
}


?>

<?php include "../templates/header.php" ?>


<?php include "../templates/navbar.php" ?>
<div class="container mt-4">


    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0 small text-white-50 uppercase">Sisa Uang Anda</h6>
                        <span class="h4 fw-bold">Rp <?= number_format($sisa_uang, 0, '.', ',') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success-subtle text-success p-3 rounded-3">
                        <i class="bi bi-arrow-down-left-circle-fill fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small uppercase">Pemasukan Bulan Ini</h6>
                        <span class="h4 fw-bold text-success">Rp <?= number_format($pemasukan, 0, '.', ',') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-danger-subtle text-danger p-3 rounded-3">
                        <i class="bi bi-arrow-up-right-circle-fill fs-3"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-0 small uppercase">Pengeluaran Bulan Ini</h6>
                        <span class="h4 fw-bold text-danger">Rp <?= number_format($pengeluaran, 0, '.', ',') ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <h5 class="fw-bold mb-3">Riwayat Transaksi Terakhir</h5>

    <div class="container mt-4">
        <div class="row g-4">

            <div class="col-md-6">
                <h5 class="fw-bold mb-3 text-success">
                    <i class="bi bi-arrow-down-left-circle me-2"></i>Riwayat Pemasukan
                </h5>

                <div class="d-flex flex-column gap-3">
                    <?php
                    $ada_pemasukan = false;
                    $limit_pemasukan = 0; // 1. Buat penghitung

                    foreach ($riwayat_transaksi as $row):
                        if ($row['tr_type'] === 'income'):
                            $ada_pemasukan = true;
                            $limit_pemasukan++; // 2. Tambah 1 setiap ketemu pemasukan

                            // 3. Cek apakah yang ditampilkan masih <= 3
                            if ($limit_pemasukan <= 3):
                    ?>
                                <div class="card border-0 border-start border-success border-4 shadow-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="fw-bold mb-0"><?= htmlspecialchars($row['nama_transaksi']) ?></h6>
                                            </div>
                                            <div class="col-auto text-end">
                                                <div class="fw-bold text-success">+ Rp <?= number_format($row['nominal_transaksi'], 0, ',', '.') ?></div>
                                                <div class="text-muted small"><?= $row['tanggal_transaksi'] ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            endif; // Tutup kondisi limit
                        endif;
                    endforeach;

                    if (!$ada_pemasukan):
                        ?>
                        <div class="text-muted fst-italic small">Belum ada catatan pemasukan.</div>
                    <?php endif; ?>
                </div>
            </div>


            <div class="col-md-6">
                <h5 class="fw-bold mb-3 text-danger">
                    <i class="bi bi-arrow-up-right-circle me-2"></i>Riwayat Pengeluaran
                </h5>

                <div class="d-flex flex-column gap-3">
                    <?php
                    $ada_pengeluaran = false;
                    $limit_pengeluaran = 0; // 1. Buat penghitung

                    foreach ($riwayat_transaksi as $row):
                        if ($row['tr_type'] === 'expense'):
                            $ada_pengeluaran = true;
                            $limit_pengeluaran++; // 2. Tambah 1 setiap ketemu pengeluaran

                            // 3. Cek apakah yang ditampilkan masih <= 3
                            if ($limit_pengeluaran <= 3):
                    ?>
                                <div class="card border-0 border-start border-danger border-4 shadow-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="fw-bold mb-0"><?= htmlspecialchars($row['nama_transaksi']) ?></h6>
                                            </div>
                                            <div class="col-auto text-end">
                                                <div class="fw-bold text-danger">- Rp <?= number_format($row['nominal_transaksi'], 0, ',', '.') ?></div>
                                                <div class="text-muted small"><?= $row['tanggal_transaksi'] ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            endif; // Tutup kondisi limit
                        endif;
                    endforeach;

                    if (!$ada_pengeluaran):
                        ?>
                        <div class="text-muted fst-italic small">Belum ada catatan pengeluaran.</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <div class="row mb-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-0">Visualisasi Arus Kas</h5>
                            <small class="text-muted">Perbandingan Tren Pemasukan & Pengeluaran</small>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-secondary active" onclick="updateChart('harian', this)">Harian</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="updateChart('bulanan', this)">Bulanan</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="updateChart('tahunan', this)">Tahunan</button>
                        </div>
                    </div>

                    <div style="height: 400px; width: 100%;">
                        <canvas id="mainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../assets/chart.js"></script>
<script>
    // Tampung data dari PHP ke dalam Object Javascript global
    const chartData = {
        harian: <?= json_encode($data_harian) ?>,
        bulanan: <?= json_encode($data_bulanan) ?>,
        tahunan: <?= json_encode($data_tahunan) ?>
    };
</script>

<script src="script.js"></script>
<?php include "../templates/footer.php" ?>
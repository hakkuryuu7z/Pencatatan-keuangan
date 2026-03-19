<?php
include "../config/database.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location:../auth/login.php");
    exit();
}

$userid = $_SESSION['user_id'];
$sql = "SELECT 
    SUM(CASE WHEN tr_type = 'income' THEN tr_nominal ELSE -tr_nominal END) as sisa_uang_total,
    SUM(CASE WHEN tr_type = 'income' 
        AND tr_date >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01') THEN tr_nominal ELSE 0 END) as masuk_bulan_ini,
        (CASE WHEN tr_type = 'expense' 
        AND tr_date >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01') THEN tr_nominal ELSE 0 END) as keluar_bulan_ini

FROM tbtr_transactions
WHERE tr_user_id = :userid ;
    ";

$stmt = $pdo->prepare($sql);
$stmt->execute(['userid' => $userid]);

$ringkasan = $stmt->fetch(PDO::FETCH_ASSOC);

$pengeluaran = $ringkasan['keluar_bulan_ini'];
$pemasukan = $ringkasan['masuk_bulan_ini'];
$sisa_uang = $ringkasan['sisa_uang_total'];

$sql_riwayat = "SELECT 
    tr_type, 
    tr_name AS nama_transaksi, 
    tr_nominal AS nominal_transaksi, 
    DATE_FORMAT(tr_date, '%d %b %Y') AS tanggal_transaksi
FROM tbtr_transactions
WHERE tr_user_id = :userid
ORDER BY tr_date DESC
    ";

$stmt2 = $pdo->prepare($sql_riwayat);
$stmt2->execute(['userid' => $userid]);
$riwayat_transaksi = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// ==========================================
// 3. AMBIL DATA UNTUK CHART (Harian, Bulanan, Tahunan)
// ==========================================

// Fungsi kecil agar tidak capek nulis array berulang-ulang
function getChartData($pdo, $sql, $userid)
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userid' => $userid]);
    $data = ['labels' => [], 'masuk' => [], 'keluar' => []];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $data['labels'][] = $row['label'];
        $data['masuk'][]  = $row['total_masuk'];
        $data['keluar'][] = $row['total_keluar'];
    }
    return $data;
}

// A. DATA HARIAN (Khusus Bulan Ini saja agar grafik tidak kepanjangan)
$sql_harian = "SELECT DATE_FORMAT(tr_date, '%d %b') AS label,
    SUM(CASE WHEN tr_type = 'income' THEN tr_nominal ELSE 0 END) AS total_masuk,
    SUM(CASE WHEN tr_type = 'expense' THEN tr_nominal ELSE 0 END) AS total_keluar
    FROM tbtr_transactions WHERE tr_user_id = :userid 
    AND MONTH(tr_date) = MONTH(CURRENT_DATE()) AND YEAR(tr_date) = YEAR(CURRENT_DATE())
    GROUP BY DATE(tr_date) ORDER BY DATE(tr_date) ASC";
$data_harian = getChartData($pdo, $sql_harian, $userid);

// B. DATA BULANAN (Semua Waktu)
$sql_bulanan = "SELECT DATE_FORMAT(tr_date, '%b %Y') AS label,
    SUM(CASE WHEN tr_type = 'income' THEN tr_nominal ELSE 0 END) AS total_masuk,
    SUM(CASE WHEN tr_type = 'expense' THEN tr_nominal ELSE 0 END) AS total_keluar
    FROM tbtr_transactions WHERE tr_user_id = :userid
    GROUP BY DATE_FORMAT(tr_date, '%Y-%m') ORDER BY DATE_FORMAT(tr_date, '%Y-%m') ASC";
$data_bulanan = getChartData($pdo, $sql_bulanan, $userid);

// C. DATA TAHUNAN (Semua Waktu)
$sql_tahunan = "SELECT DATE_FORMAT(tr_date, '%Y') AS label,
    SUM(CASE WHEN tr_type = 'income' THEN tr_nominal ELSE 0 END) AS total_masuk,
    SUM(CASE WHEN tr_type = 'expense' THEN tr_nominal ELSE 0 END) AS total_keluar
    FROM tbtr_transactions WHERE tr_user_id = :userid
    GROUP BY YEAR(tr_date) ORDER BY YEAR(tr_date) ASC";
$data_tahunan = getChartData($pdo, $sql_tahunan, $userid);

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
                        <h5 class="fw-bold mb-0">Grafik Arus Kas</h5>

                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary" onclick="updateChart('harian', this)">Harian</button>
                            <button type="button" class="btn btn-secondary active" onclick="updateChart('bulanan', this)">Bulanan</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="updateChart('tahunan', this)">Tahunan</button>
                        </div>
                    </div>

                    <div style="height: 350px; width: 100%;">
                        <canvas id="myChart"></canvas>
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
<?php

$userid = $_SESSION['user_id'];
$sql = "SELECT 
    SUM(CASE WHEN tr_type = 'income' THEN tr_nominal ELSE -tr_nominal END) as sisa_uang_total,
    SUM(CASE WHEN tr_type = 'income' 
        AND tr_date >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01') THEN tr_nominal ELSE 0 END) as masuk_bulan_ini,
    SUM(CASE WHEN tr_type = 'expense' 
        AND tr_date >= DATE_FORMAT(CURRENT_DATE, '%Y-%m-01') THEN tr_nominal ELSE 0 END) as keluar_bulan_ini
FROM tbtr_transactions
WHERE tr_user_id = :userid 
    ";

$stmt = $pdo->prepare($sql);
$stmt->execute(['userid' => $userid]);

$ringkasan = $stmt->fetch(PDO::FETCH_ASSOC);

$pengeluaran = $ringkasan['keluar_bulan_ini'] ?? 0;
$pemasukan   = $ringkasan['masuk_bulan_ini'] ?? 0;
$sisa_uang   = $ringkasan['sisa_uang_total'] ?? 0;

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
    GROUP BY DATE_FORMAT(tr_date, '%d %b') 
    ORDER BY MIN(tr_date) ASC";
$data_harian = getChartData($pdo, $sql_harian, $userid);

// B. DATA BULANAN (Semua Waktu)
$sql_bulanan = "SELECT DATE_FORMAT(tr_date, '%b %Y') AS label,
    SUM(CASE WHEN tr_type = 'income' THEN tr_nominal ELSE 0 END) AS total_masuk,
    SUM(CASE WHEN tr_type = 'expense' THEN tr_nominal ELSE 0 END) AS total_keluar
    FROM tbtr_transactions WHERE tr_user_id = :userid
    GROUP BY DATE_FORMAT(tr_date, '%b %Y') 
    ORDER BY MIN(tr_date) ASC";
$data_bulanan = getChartData($pdo, $sql_bulanan, $userid);

// C. DATA TAHUNAN (Semua Waktu)
$sql_tahunan = "SELECT DATE_FORMAT(tr_date, '%Y') AS label,
    SUM(CASE WHEN tr_type = 'income' THEN tr_nominal ELSE 0 END) AS total_masuk,
    SUM(CASE WHEN tr_type = 'expense' THEN tr_nominal ELSE 0 END) AS total_keluar
    FROM tbtr_transactions WHERE tr_user_id = :userid
    GROUP BY DATE_FORMAT(tr_date, '%Y') 
    ORDER BY MIN(tr_date) ASC";
$data_tahunan = getChartData($pdo, $sql_tahunan, $userid);

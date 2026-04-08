<?php
// Pastikan variabel koneksi ($pdo) dan session sudah ada sebelum file ini dipanggil

$user_id = $_SESSION['user_id'];

// 1. Cek apakah ada request filter tanggal. Kalau kosong, pakai tanggal hari ini.
$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date   = !empty($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// 2. Siapkan SQL dengan filter BETWEEN dan ambil tr_id (buat tombol Edit/Hapus nanti)
$sql = "SELECT tr_id, tr_type, tr_name, tr_nominal, tr_date, tr_note 
        FROM tbtr_transactions 
        WHERE tr_user_id = :userid 
        AND DATE(tr_date) BETWEEN :start_date AND :end_date 
        ORDER BY tr_date DESC, tr_id DESC"; // Urutkan dari yang terbaru

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':userid'     => $user_id,
    ':start_date' => $start_date,
    ':end_date'   => $end_date
]);

$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. Siapkan wadah (Array) untuk misahin Pemasukan dan Pengeluaran
$incomes  = [];
$expenses = [];

// Siapkan variabel untuk menghitung Total
$total_income  = 0;
$total_expense = 0;

// 4. Looping data dan pisahkan berdasarkan tipenya
foreach ($transactions as $tr) {
    if ($tr['tr_type'] === 'income') {
        $incomes[] = $tr; // Masukkan ke array income
        $total_income += $tr['tr_nominal']; // Tambahkan ke total
    } elseif ($tr['tr_type'] === 'expense') {
        $expenses[] = $tr; // Masukkan ke array expense
        $total_expense += $tr['tr_nominal']; // Tambahkan ke total
    }
}

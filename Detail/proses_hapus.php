<?php
include "../config/database.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location:../auth/login.php");
    exit();
}
$userid = $_SESSION['user_id'];
$tr_id = $_GET['id'];

$sqlcatatan = "SELECT tr_id, tr_user_id, tr_categori_id, tr_type, tr_name , tr_nominal , tr_date, tr_note 
                FROM tbtr_transactions WHERE tr_id = :tr_id ";

$stmt2 = $pdo->prepare($sqlcatatan);
$stmt2->execute(['tr_id' => $tr_id]);
$transactions = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($userid != $transactions['tr_user_id']) {
    $_SESSION['alert_icon']  = 'error';
    $_SESSION['alert_title'] = 'Wrong Transaction';
    $_SESSION['alert_text']  = 'Sistem bilang: Bukan Punya si USER';

    header("Location: index.php");
    exit();
} else {
    try {
        $sqldelete = "DELETE FROM tbtr_transactions WHERE tr_id = :tr_id ";
        $stmt = $pdo->prepare($sqldelete);
        $eksekusi = $stmt->execute(['tr_id' => $tr_id]);

        if ($eksekusi) {

            $_SESSION['alert_icon']  = 'success';
            $_SESSION['alert_title'] = 'DONE';
            $_SESSION['alert_text']  = 'Hapus Transaction Berhasil!';


            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {

        $_SESSION['alert_icon']  = 'error';
        $_SESSION['alert_title'] = 'Waduh Gagal!';
        $_SESSION['alert_text']  = 'Sistem bilang: ' . $e->getMessage();


        header("Location: index.php");
        exit();
    }
}

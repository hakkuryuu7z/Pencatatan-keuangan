<?php

$userid = $_SESSION['user_id'];

$sqlkategori = "SELECT cat_id, 
                cat_name , 
                cat_type from 
                tbmaster_catergories WHERE cat_userid = :userid ";

$stmt = $pdo->prepare($sqlkategori);
$stmt->execute(['userid' => $userid]);
$kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
}

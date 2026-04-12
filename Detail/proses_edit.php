<?php
include "../config/database.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location:../auth/login.php");
    exit();
}

// var_dump($_POST);


if (isset($_POST['simpan'])) {
    $tr_user_id    = htmlspecialchars($_POST['tr_user_id']);
    $tr_nama       = htmlspecialchars($_POST['nama_transaksi']);
    $tr_type       = htmlspecialchars($_POST['tr_type']);
    $tr_tanggal    = htmlspecialchars($_POST['tanggal_transaksi']);
    $tr_cat        = htmlspecialchars($_POST['tr_cat']);
    $tr_nominal    = htmlspecialchars($_POST['nominal_transaksi']);
    $tr_keterangan = htmlspecialchars($_POST['keterangan']);
    $tr_id         = htmlspecialchars($_POST['id_tr']);

    if ($tr_nominal < 0) {
        $_SESSION['alert_icon']  = 'error';
        $_SESSION['alert_title'] = 'nominal tidak boleh -(minus) !';
        $_SESSION['alert_text']  = 'nominal harus >= 0';


        header("Location: index.php");
        exit();
    }
    $sql_insert = "UPDATE `tbtr_transactions` SET 
    `tr_categori_id`=:cat_id,
    `tr_type`=:type ,`tr_name`=:nama,
    `tr_nominal`=:nominal ,`tr_date`=:tanggal,
    `tr_note`=:keterangan WHERE tr_user_id = :user_id and tr_id = :id_tr ";

    try {

        $stmt = $pdo->prepare($sql_insert);


        $eksekusi = $stmt->execute([
            ':user_id'    => $tr_user_id,
            ':cat_id'     => $tr_cat,
            ':type'       => $tr_type,
            ':nama'       => $tr_nama,
            ':nominal'    => $tr_nominal,
            ':tanggal'    => $tr_tanggal,
            ':keterangan' => $tr_keterangan,
            ':id_tr' => $tr_id
        ]);


        if ($eksekusi) {

            $_SESSION['alert_icon']  = 'success';
            $_SESSION['alert_title'] = 'Mantap!';
            $_SESSION['alert_text']  = 'Edit Transaction Berhasil!';


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

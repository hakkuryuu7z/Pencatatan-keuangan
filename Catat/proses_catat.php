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

    $sql_insert = "INSERT INTO TBTR_TRANSACTIONS
                    (tr_user_id, tr_categori_id, tr_type, tr_name, tr_nominal, tr_date, tr_note) 
                   VALUES 
                    (:user_id, :cat_id, :type, :nama, :nominal, :tanggal, :keterangan)";

    try {

        $stmt = $pdo->prepare($sql_insert);


        $eksekusi = $stmt->execute([
            ':user_id'    => $tr_user_id,
            ':cat_id'     => $tr_cat,
            ':type'       => $tr_type,
            ':nama'       => $tr_nama,
            ':nominal'    => $tr_nominal,
            ':tanggal'    => $tr_tanggal,
            ':keterangan' => $tr_keterangan
        ]);


        if ($eksekusi) {

            $_SESSION['alert_icon']  = 'success';
            $_SESSION['alert_title'] = 'Mantap!';
            $_SESSION['alert_text']  = 'Catatan berhasil disimpan cuy!';


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

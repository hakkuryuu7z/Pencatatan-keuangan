<?php

include "../config/database.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location:../auth/login.php");
    exit();
}


if (isset($_POST['simpan'])) {
    $cat_user_id = htmlspecialchars($_POST['cat_user_id']);
    $cat_nama = htmlspecialchars($_POST['cat_nama']);
    $cat_type = htmlspecialchars($_POST['cat_type']);

    $sql_insert = "INSERT INTO tbmaster_catergories (cat_userid, cat_name,
                    cat_type) VALUES
                    (:user_id, :cat_name, :cat_type)";

    try {
        $stmt = $pdo->prepare($sql_insert);

        $eksekusi = $stmt->execute([
            ':user_id' => $cat_user_id,
            ':cat_name' => $cat_nama,
            ':cat_type' => $cat_type
        ]);

        if ($eksekusi) {

            $_SESSION['alert_icon']  = 'success';
            $_SESSION['alert_title'] = 'Mantap!';
            $_SESSION['alert_text']  = 'Categorie berhasil disimpan cuy!';


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

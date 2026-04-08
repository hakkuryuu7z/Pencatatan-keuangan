<?php

$userid = $_SESSION['user_id'];

$sqlkategori = "SELECT cat_id, 
                cat_name , 
                cat_type from 
                tbmaster_catergories WHERE cat_userid = :userid ";

$stmt = $pdo->prepare($sqlkategori);
$stmt->execute(['userid' => $userid]);
$kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);

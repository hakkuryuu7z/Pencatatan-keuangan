<?php
session_start();
if (isset($_SESSION['username_login'])) {
    echo "anda sudah login " . $_SESSION['username_login'];
} else {
    header("Location:../auth/login.php");
    exit();
}

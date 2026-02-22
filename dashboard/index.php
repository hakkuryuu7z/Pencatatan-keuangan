<?php
session_start();
if(isset($_SESSION['login'])){
    echo "anda sudah login";
}else{
    header("Location:../auth/login.php");
    exit();
}

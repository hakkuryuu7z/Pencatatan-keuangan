<?php
require_once("../config/database.php");
session_start();

if (isset($_SESSION['login'])) {
    header("Location: ../dashboard/index.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}

<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location:../auth/login.php");
    exit();
}

?>

<?php include "../templates/header.php" ?>

<body class="bg-light">
    <?php include "../templates/navbar.php" ?>


    <?php include "../templates/footer.php" ?>
<?php
require_once("../config/database.php");
session_start();
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sqli = "SELECT user_username , user_password FROM tbmaster_users WHERE user_username = :username";
    $stmtn = $pdo->prepare($sqli);

    $stmtn->bindParam(":username", $username);

    $stmtn->execute();
    $user = $stmtn->fetch(PDO::FETCH_ASSOC);

    if ($username == $user['user_username'] && password_verify($password, $user['user_password'])) {
        $_SESSION['username_login'] = $username;
        header("Location: ../dashboard/index.php");
    } else {
        echo "<script>alert('password atau kata sandi salah'); window.location='login.php'</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card text-center">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row mb-3">
                                <label for="username" class="col-sm-4 col-form-label">User</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="username" name="username">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-4 col-form-label">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" name="login">Sign in</button>
                        </form>
                    </div>
                    <div class="card-footer text-body-secondary">
                        <p><a class="link-underline-warning link-opacity-50-hover" href="register.php">Tidak punya akun?</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
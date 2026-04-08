<?php
require_once("../config/database.php");
session_start();

if (isset($_SESSION['login'])) {
    header("Location: ../dashboard/index.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = strtoupper($_POST['username']);
    $password = $_POST['password'];

    $sqli = "SELECT user_username , user_password, user_id, user_role FROM tbmaster_users WHERE user_username = :username";
    $stmtn = $pdo->prepare($sqli);

    $stmtn->bindParam(":username", $username);

    $stmtn->execute();
    $user = $stmtn->fetch(PDO::FETCH_ASSOC);

    if ($username == strtoupper($user['user_username']) && password_verify($password, $user['user_password'])) {
        $_SESSION['login'] = 'true';
        $_SESSION['username_login'] = $username;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_role'] = $user['user_role'];
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
    <link rel="stylesheet" href="../assets/bootstrap-icons-1.13.1/bootstrap-icons.min.css">
    <style>
        body {
            background: #f8f9fa;
            /* Warna background abu-abu sangat muda */
        }

        .card {
            transition: transform 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        /* Membuat input text-uppercase tetap terlihat rapi */
        .form-control.text-uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5">

                        <div class="text-center mb-4">
                            <div class="bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-fill fs-3"></i>
                            </div>
                            <h4 class="fw-bold">Selamat Datang</h4>
                            <p class="text-muted small">Silakan masuk ke akun Anda</p>
                        </div>

                        <form action="" method="post">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control text-uppercase" id="username" name="username" placeholder="Username" required>
                                <label for="username text-muted">Username</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password text-muted">Password</label>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold" name="login">
                                    SIGN IN
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">Tidak punya akun?</p>
                            <a class="text-decoration-none fw-bold" href="register.php">Daftar Sekarang</a>
                        </div>

                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="../index.php" class="text-muted small text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
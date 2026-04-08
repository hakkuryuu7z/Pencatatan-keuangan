<?php
require_once "../config/database.php";
session_start();
if (isset($_SESSION['login'])) {
    header("Location: ../dashboard/index.php");
    exit();
}

if (isset($_POST['regist'])) {


    $username = $_POST['username'];
    $password = $_POST['password'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $password2 = $_POST['password2'];

    try {
        $sqli = "SELECT user_username FROM tbmaster_users where user_username = :username";
        $stmtn = $pdo->prepare($sqli);

        $stmtn->bindParam(':username', $username);
        $stmtn->execute();

        if ($stmtn->rowCount() > 0) {
            echo "<script>alert('username telah digunakan'); window.location='register.php'</script>";
        } else if ($password !== $password2) {
            echo "<script>alert('Konfirmasi password salah'); window.location='register.php'</script>'";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            try {
                $sql = "INSERT iNTO tbmaster_users (user_username,user_email,user_password,user_phone)
                VALUES (:username, :email, :user_password, :user_phone)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':user_password', $password_hash);
                $stmt->bindParam(':user_phone', $phone);

                if ($stmt->execute()) {
                    echo "<script>alert('data berhail di simpan'); window.location='login.php'</script>";
                } else {
                    echo "gagal mengirim data";
                }
            } catch (PDOException $e) {
                echo "Gagal menyimpan data" . $e->getMessage();
            }
        }
    } catch (PDOException $e) {
        echo "halo hai error manis";
        echo "<script>console.log(" . $e->getMessage() . ")</script>";
    }
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <div class="bg-success bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-person-plus-fill fs-3"></i>
                            </div>
                            <h4 class="fw-bold">Buat Akun Baru</h4>
                            <p class="text-muted small">Lengkapi data di bawah untuk bergabung</p>
                        </div>

                        <form action="" method="post">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control text-uppercase" id="username" name="username" placeholder="Username" required>
                                <label for="username">Username</label>
                            </div>

                            <div class="row g-2">
                                <div class="col-md">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="Email" name="email" placeholder="name@example.com" required>
                                        <label for="Email">Email</label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="phone" name="phone" placeholder="0812..." required>
                                        <label for="phone">No. HP</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                        <label for="password">Password</label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi" required>
                                        <label for="password2">Konfirmasi</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold" name="regist">
                                    DAFTAR SEKARANG
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">Sudah punya akun?</p>
                            <a class="text-decoration-none fw-bold text-success" href="login.php">Masuk di sini</a>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 py-3 text-center rounded-bottom-4">
                        <a class="text-muted small text-decoration-none" href="#">Lupa akun? (Beta)</a>
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
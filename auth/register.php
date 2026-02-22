<?php
require_once "../config/database.php";
if (isset($_POST['regist'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];

    try {
        $sql = "INSERT iNTO tbmaster_users (user_username,user_email,user_password,user_phone)
                VALUE (:username, :email, :user_password, :user_phone)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':user_password', $password);
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



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card text-center">
                    <div class="card-header">
                        Register
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row mb-3">
                                <label for="username" class="col-sm-4 col-form-label">Username</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-sm-4 col-form-label">Password</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="Email" class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="Email" name="email" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="phone" class="col-sm-4 col-form-label">phone</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" value="submit" name="regist">Register</button>
                        </form>
                    </div>

                    <div class="card-footer text-body-secondary">
                        <div class="d-flex justify-content-center gap-4">
                            <p><a class="link-underline-warning link-opacity-50-hover" href="register.php">Lupa akun?(beta)</a></p>
                            <p><a class="link-underline-warning link-opacity-50-hover" href="login.php">Sign in</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
include "../config/database.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location:../auth/login.php");
    exit();
}


?>

<?php include "../templates/header.php" ?>

<?php include "../templates/navbar.php" ?>

<div class="container mt-3 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success bg-gradient text-white rounded-3 p-3 me-3">
                            <i class="bi bi-folder-plus fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Categories</h4>

                        </div>
                    </div>

                    <form action="proses_cat.php" method="post">
                        <input type="hidden" name="cat_user_id" value="<?= $_SESSION['user_id'] ?>">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cat_nama" name="cat_nama" placeholder="Contoh: Gaji Bulanan / Beli Bakso" required>
                            <label for="cat_nama">Nama Categorie</label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <select class="form-select" id="cat_type" name="cat_type" aria-label="Jenis Categorie" required>
                                        <option value="" selected disabled>Pilih Jenis</option>
                                        <option value="income" class="text-success fw-bold">Pemasukan (+)</option>
                                        <option value="expense" class="text-danger fw-bold">Pengeluaran (-)</option>
                                    </select>
                                    <label for="cat_type">Tipe Kategori</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <a href="../Catat/" class="btn btn-light btn-lg w-100 rounded-pill fw-bold text-muted">KEMBALI</a>
                            </div>
                            <div class="col-6">
                                <button type="submit" name="simpan" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold">
                                    SIMPAN <i class="bi bi-check-lg ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include "../templates/footer.php" ?>
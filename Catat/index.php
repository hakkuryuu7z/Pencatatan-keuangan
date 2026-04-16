<?php
include "../config/database.php";
session_start();
if (!isset($_SESSION['login'])) {
    header("Location:../auth/login.php");
    exit();
}

include "query_main.php";

if (isset($_SESSION['page_dashboard'])) {
    unset($_SESSION['page_dashboard']);
    $_SESSION['page_catat'] = true;
} else if (isset($_SESSION['page_detail'])) {
    unset($_SESSION['page_detail']);
    $_SESSION['page_catat'] = true;
} else {
    $_SESSION['page_catat'] = true;
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
                        <div class="bg-primary bg-gradient text-white rounded-3 p-3 me-3">
                            <i class="bi bi-pencil-square fs-3"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Tambah Catatan</h4>
                            <p class="text-muted small mb-0">Catat pemasukan atau pengeluaran hari ini</p>
                        </div>
                    </div>

                    <form action="proses_catat.php" method="post">
                        <input type="hidden" name="tr_user_id" value="<?= $_SESSION['user_id'] ?>">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nama_transaksi" name="nama_transaksi" placeholder="Contoh: Gaji Bulanan / Beli Bakso" required>
                            <label for="nama_transaksi">Nama Transaksi</label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="tr_type" name="tr_type" aria-label="Jenis Transaksi" required>
                                        <option value="" selected disabled>Pilih Jenis</option>
                                        <option value="income" class="text-success fw-bold">Pemasukan (+)</option>
                                        <option value="expense" class="text-danger fw-bold">Pengeluaran (-)</option>
                                    </select>
                                    <label for="tr_type">Tipe Arus Kas</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="tanggal" name="tanggal_transaksi" value="<?= date('Y-m-d') ?>" required>
                                    <label for="tanggal">Tanggal</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select" id="tr_cat" name="tr_cat" aria-label="Jenis Transaksi" required>
                                    <option value="" selected disabled>Pilih Kategori</option>
                                    <?php foreach ($kategori as $kat): ?>
                                        <option value="<?= $kat['cat_id'] ?>" class=" fw-bold"><?= $kat['cat_name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <label for="tr_cat">Tipe Kategori</label>
                            </div>
                            <div class="d-flex justify-content-end mt-1">
                                <a href="../categories/" class="text-decoration-none small fw-bold">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Kategori
                                </a>
                            </div>
                        </div>
                        <label class="form-label small text-muted ms-1">Nominal (Jumlah Uang)</label>
                        <div class="input-group input-group-lg mb-4">
                            <span class="input-group-text bg-light border-end-0 text-muted">Rp</span>
                            <input type="number" class="form-control border-start-0 ps-0 fw-bold" id="nominal" name="nominal_transaksi" placeholder="0" min="0" oninput="if(this.value < 0) this.value = 0;" required>
                        </div>

                        <div class="form-floating mb-4">
                            <textarea class="form-control" placeholder="Catatan tambahan..." id="keterangan" name="keterangan" style="height: 100px"></textarea>
                            <label for="keterangan">Keterangan (Opsional)</label>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <a href="index.php" class="btn btn-light btn-lg w-100 rounded-pill fw-bold text-muted">BATAL</a>
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
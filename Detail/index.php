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

    $_SESSION['page_detail'] = true;
} else if (isset($_SESSION['page_catat'])) {
    unset($_SESSION['page_catat']);
    $_SESSION['page_detail'] = true;
} else {
    $_SESSION['page_detail'] = true;
}
?>

<?php include "../templates/header.php" ?>

<?php include "../templates/navbar.php" ?>

<div class="container mt-4 mb-5">

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-funnel-fill text-primary me-2"></i>Filter Transaksi</h5>

            <form action="" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label small text-muted fw-bold">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label small text-muted fw-bold">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-search me-2"></i>Terapkan
                    </button>
                    <a href="../Detail/" class="btn btn-light fw-bold text-muted ms-2">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-xl-6">
            <div class="card border-0 border-top border-success border-4 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-success mb-0">
                            <i class="bi bi-arrow-down-left-circle-fill me-2"></i>Pemasukan
                        </h5>
                        <span class="badge bg-success-subtle text-success rounded-pill">Total: Rp <?= number_format($total_income, 0, ',', '.') ?></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                            </thead>
                            <tbody>
                                <?php if (empty($incomes)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted fst-italic">Belum ada pemasukan di tanggal ini.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($incomes as $masuk): ?>
                                        <tr>
                                            <td class="small text-muted"><?= date('d M Y', strtotime($masuk['tr_date'])) ?></td>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($masuk['tr_name']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($masuk['tr_note']) ?></div>
                                            </td>
                                            <td class="fw-bold text-success">+ Rp <?= number_format($masuk['tr_nominal'], 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                        <li><a class="dropdown-item" href="edit.php?id=<?= $masuk['tr_id'] ?>"><i class="bi bi-pencil-square text-primary me-2"></i>Edit</a></li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="KonfirmasiHapustr(<?= $masuk['tr_id'] ?>,'<?= $masuk['tr_name'] ?>')"><i class="bi bi-trash text-danger me-2"></i>Hapus</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card border-0 border-top border-danger border-4 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-danger mb-0">
                            <i class="bi bi-arrow-up-right-circle-fill me-2"></i>Pengeluaran
                        </h5>
                        <span class="badge bg-danger-subtle text-danger rounded-pill">Total: Rp <?= number_format($total_expense, 0, ',', '.') ?></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                            </thead>
                            <tbody>
                                <?php if (empty($expenses)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted fst-italic">Belum ada pengeluaran di tanggal ini.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($expenses as $keluar): ?>
                                        <tr>
                                            <td class="small text-muted"><?= date('d M Y', strtotime($keluar['tr_date'])) ?></td>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($keluar['tr_name']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($keluar['tr_note']) ?></div>
                                            </td>
                                            <td class="fw-bold text-danger">- Rp <?= number_format($keluar['tr_nominal'], 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                        <li><a class="dropdown-item" href="edit.php?id=<?= $keluar['tr_id'] ?>"><i class="bi bi-pencil-square text-primary me-2"></i>Edit</a></li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="KonfirmasiHapustr(<?= $keluar['tr_id'] ?>,'<?= $masuk['tr_name'] ?>')"><i class="bi bi-trash text-danger me-2"></i>Hapus</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<?php include "../templates/footer.php" ?>
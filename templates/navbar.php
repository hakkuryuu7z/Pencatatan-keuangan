<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom shadow-sm py-3" style="background-color: #1a1d20 !important;">
    <div class="container"> <a class="navbar-brand fw-bold fs-4" href="#">
            <i class="bi bi-wallet2 me-2"></i>Catat Uang
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 text-light">
                    Halo, <span class="fw-bold text-info"><?= $_SESSION['username_login'] ?>!</span>
                </span>
                <a href="javascript:void(0)" onclick="konfirmasilogout()" class="btn btn-sm btn-outline-danger px-3 rounded-pill">
                    LOGOUT
                </a>
            </div>
        </div>
    </div>
</nav>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/sweetalert/sweetalert2@11.js"></script>
<script src="../templates/alertlogout.js"></script>
<script src="../templates/KonfirmasiHapustr.js"></script>

<?php if (isset($_SESSION['alert_icon']) && isset($_SESSION['alert_title'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['alert_icon'] ?>',
            title: '<?= $_SESSION['alert_title'] ?>',
            text: '<?= $_SESSION['alert_text'] ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
    <?php
    unset($_SESSION['alert_icon']);
    unset($_SESSION['alert_title']);
    unset($_SESSION['alert_text']);
    ?>
<?php endif; ?>

</body>

</html>
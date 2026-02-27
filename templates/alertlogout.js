function konfirmasilogout(){
    Swal.fire({
        title: 'Yakin ingin Keluar ?',
        text: "Sesi Anda akan berakhir setelah ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', // Warna merah untuk konfirmasi
        cancelButtonColor: '#6c757d', // Warna abu-abu untuk batal
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        background: '#212529',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika user klik "Ya", arahkan ke file logout
            window.location.href = "../auth/logout.php";
        }
    })
}
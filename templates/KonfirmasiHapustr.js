function KonfirmasiHapustr(id,name){
    Swal.fire({
        title: 'Anda akan Menghapus ?',
        text: "Transaksi '" + name + "' akan terhapus",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', // Warna merah untuk konfirmasi
        cancelButtonColor: '#6c757d', // Warna abu-abu untuk batal
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        background: '#212529',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika user klik "Ya", arahkan ke file logout
            window.location.href = "../Detail/proses_hapus.php?id="+id;
        }
    })
}
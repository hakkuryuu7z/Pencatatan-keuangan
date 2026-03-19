// 1. Ambil elemen canvas dan context
const canvas = document.getElementById('myChart');
const ctx = canvas.getContext('2d');

// 2. Buat Efek Gradient untuk Pemasukan (Hijau)
// createLinearGradient(x0, y0, x1, y1)
let gradientPemasukan = ctx.createLinearGradient(0, 0, 0, 400);
gradientPemasukan.addColorStop(0, 'rgba(25, 135, 84, 0.4)'); // Lebih pekat di atas
gradientPemasukan.addColorStop(1, 'rgba(25, 135, 84, 0.0)'); // Transparan di bawah

// 3. Buat Efek Gradient untuk Pengeluaran (Merah)
let gradientPengeluaran = ctx.createLinearGradient(0, 0, 0, 400);
gradientPengeluaran.addColorStop(0, 'rgba(220, 53, 69, 0.4)');
gradientPengeluaran.addColorStop(1, 'rgba(220, 53, 69, 0.0)');

// 4. Settingan visual untuk Pemasukan
const stylePemasukan = {
    label: 'Pemasukan',
    borderColor: '#198754', 
    backgroundColor: gradientPemasukan, // Pakai gradient di sini
    borderWidth: 3, // Garis ditebalkan
    pointBackgroundColor: '#ffffff', // Tengah titik warna putih
    pointBorderColor: '#198754', // Pinggiran titik warna hijau
    pointBorderWidth: 2,
    pointRadius: 5,
    pointHoverRadius: 7, // Membesar saat di-hover
    tension: 0.4, // Melengkung halus
    fill: true,
    spanGaps: true // Menghubungkan titik jika ada data yang kosong di tengah
};

// 5. Settingan visual untuk Pengeluaran
const stylePengeluaran = {
    label: 'Pengeluaran',
    borderColor: '#dc3545',
    backgroundColor: gradientPengeluaran, // Pakai gradient di sini
    borderWidth: 3, 
    pointBackgroundColor: '#ffffff',
    pointBorderColor: '#dc3545',
    pointBorderWidth: 2,
    pointRadius: 5,
    pointHoverRadius: 7,
    tension: 0.4,
    fill: true,
    spanGaps: true
};

// 6. Buat Chart dengan data default (Bulanan)
let myChart = new Chart(ctx, {
    type: 'line', 
    data: {
        labels: chartData.bulanan.labels,
        datasets: [
            { ...stylePemasukan, data: chartData.bulanan.masuk },
            { ...stylePengeluaran, data: chartData.bulanan.keluar }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Penting agar chart bisa diresize dengan bebas
        interaction: {
            mode: 'index', 
            intersect: false,
        },
        plugins: {
            legend: { 
                position: 'bottom', // Pindahkan legend ke bawah
                labels: {
                    usePointStyle: true, // Ubah kotak legend jadi bulat (lebih modern)
                    boxWidth: 8,
                    padding: 20,
                    font: {
                        family: "'Inter', 'Segoe UI', sans-serif",
                        size: 13
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(33, 37, 41, 0.9)', // Warna background tooltip (gelap modern)
                titleFont: { size: 13, family: "'Inter', sans-serif" },
                bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                padding: 12,
                cornerRadius: 8,
                displayColors: true, // Tampilkan warna dot di dalam tooltip
                callbacks: {
                    // Update format angka tooltip
                    label: function(context) {
                        // Gunakan toLocaleString('id-ID') untuk format Rupiah (titik sebagai pemisah ribuan)
                        let formattedValue = Number(context.raw).toLocaleString('id-ID');
                        return context.dataset.label + ': Rp ' + formattedValue;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false }, 
                ticks: {
                    font: { family: "'Inter', sans-serif", size: 12 },
                    color: '#6c757d'
                }
            },
            y: {
                beginAtZero: true,
                border: { display: false }, // Hilangkan garis tebal sumbu Y (kiri)
                grid: { 
                    color: '#e9ecef', // Warna grid abu-abu sangat muda
                    drawTicks: false // Hilangkan coretan kecil di sumbu Y
                }, 
                ticks: {
                    padding: 10,
                    font: { family: "'Inter', sans-serif", size: 12 },
                    color: '#6c757d',
                    callback: function(value) {
                        if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                        if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                        return 'Rp ' + value;
                    }
                }
            }
        }
    }
});

// 7. Fungsi Update saat tombol diklik (Tetap sama, termasuk perbaikan class active)
function updateChart(period, btnElement) {
    myChart.data.labels = chartData[period].labels;
    myChart.data.datasets[0].data = chartData[period].masuk;
    myChart.data.datasets[1].data = chartData[period].keluar;
    myChart.update(); 

    let buttons = btnElement.parentElement.children;
    for (let btn of buttons) {
        btn.classList.remove('btn-secondary', 'active');
        btn.classList.add('btn-outline-secondary');
    }
    btnElement.classList.remove('btn-outline-secondary');
    btnElement.classList.add('btn-secondary', 'active');
}
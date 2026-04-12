// 1. Ambil elemen canvas dan context
// PASTIKAN ID DI BAWAH INI ADALAH 'mainChart' (Sesuai dengan HTML kamu)
const canvasElement = document.getElementById('mainChart');
const ctx = canvasElement.getContext('2d');

// Fungsi pembantu untuk format Rupiah di sumbu Y
const formatRupiah = (value) => {
    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
    return 'Rp ' + value;
};

// 2. Buat Efek Gradient untuk Pemasukan (Hijau)
let gradientPemasukan = ctx.createLinearGradient(0, 0, 0, 400);
gradientPemasukan.addColorStop(0, 'rgba(25, 135, 84, 0.4)');
gradientPemasukan.addColorStop(1, 'rgba(25, 135, 84, 0.0)');

// 3. Buat Efek Gradient untuk Pengeluaran (Merah)
let gradientPengeluaran = ctx.createLinearGradient(0, 0, 0, 400);
gradientPengeluaran.addColorStop(0, 'rgba(220, 53, 69, 0.4)');
gradientPengeluaran.addColorStop(1, 'rgba(220, 53, 69, 0.0)');

// 4. Settingan visual untuk Pemasukan
const stylePemasukan = {
    label: 'Pemasukan',
    yAxisID: 'y', // Berkiblat ke sumbu kiri
    borderColor: '#198754', 
    backgroundColor: gradientPemasukan, 
    borderWidth: 3, 
    pointBackgroundColor: '#ffffff',
    pointBorderColor: '#198754',
    pointBorderWidth: 2,
    pointRadius: 5,
    tension: 0.4, 
    fill: true
};

// 5. Settingan visual untuk Pengeluaran
const stylePengeluaran = {
    label: 'Pengeluaran',
    yAxisID: 'y1', // Berkiblat ke sumbu kanan
    borderColor: '#dc3545',
    backgroundColor: gradientPengeluaran,
    borderWidth: 3, 
    pointBackgroundColor: '#ffffff',
    pointBorderColor: '#dc3545',
    pointBorderWidth: 2,
    pointRadius: 5,
    tension: 0.4,
    fill: true
};

// 6. Buat Chart
let myChart = new Chart(ctx, {
    type: 'line', 
    data: {
        // Kita pakai pengaman (?.) biar nggak error kalau datanya kosong
        labels: chartData?.harian?.labels || [],
        datasets: [
            { ...stylePemasukan, data: chartData?.harian?.masuk || [] },
            { ...stylePengeluaran, data: chartData?.harian?.keluar || [] }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, 
        interaction: {
            mode: 'index', 
            intersect: false,
        },
        plugins: {
            legend: { 
                position: 'top', 
                labels: {
                    usePointStyle: true, 
                    boxWidth: 8,
                    padding: 20,
                    font: {
                        family: "'Inter', 'Segoe UI', sans-serif",
                        size: 13
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(33, 37, 41, 0.9)', 
                titleFont: { size: 13, family: "'Inter', sans-serif" },
                bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                padding: 12,
                cornerRadius: 8,
                displayColors: true, 
                callbacks: {
                    label: function(context) {
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
            y: { // SUMBU KIRI (Pemasukan)
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                border: { display: false },
                grid: { color: '#e9ecef', drawTicks: false }, 
                ticks: {
                    color: '#198754', 
                    callback: (value) => formatRupiah(value)
                }
            },
            y1: { // SUMBU KANAN (Pengeluaran)
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                border: { display: false },
                grid: { drawOnChartArea: false }, 
                ticks: {
                    color: '#dc3545', 
                    callback: (value) => formatRupiah(value)
                }
            }
        }
    }
});

// 7. Fungsi Update saat tombol diklik
function updateChart(period, btnElement) {
    // Ambil data sesuai periode, kalau kosong pakai object kosong
    const data = chartData[period] || {};

    // Update data grafik (dengan pengaman kalau datanya undefined)
    myChart.data.labels = data.labels || [];
    myChart.data.datasets[0].data = data.masuk || [];
    myChart.data.datasets[1].data = data.keluar || [];
    myChart.update(); 

    // Update Class Aktif di Tombol
    let buttons = btnElement.parentElement.children;
    for (let btn of buttons) {
        btn.classList.remove('btn-secondary', 'active');
        btn.classList.add('btn-outline-secondary');
    }
    btnElement.classList.remove('btn-outline-secondary');
    btnElement.classList.add('btn-secondary', 'active');
}
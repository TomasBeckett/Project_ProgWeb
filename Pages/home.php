<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="../Assets/css/web.css?v=<?= time(); ?>" />
<title>Lowongan Pekerjaan</title>
</head>
<body>

<?php include 'header.php'; ?>

<main>
    <!-- Form Pencarian -->
    <form id="search-form" onsubmit="return false;" class="section"> <!-- prevent default submit -->
        <div class="search-section">
            <label for="search">Cari Lowongan:</label>
            <div class="search-container">
                <input type="text" name="search" placeholder="Nama Perusahaan, Lokasi, dll" />
                <!-- Tombol submit bisa dihilangkan atau tetap ada tapi tanpa aksi reload -->
                <!--<button class="search-btn" type="submit">🔍</button>-->
            </div>
        </div>
        <table class="center">
            <tr class="search-section2">
                <td>
                    <label for="company">Perusahaan:</label>
                    <input type="text" name="company" placeholder="Nama Perusahaan" />
                </td>
                <td>
                    <label for="category">Kategori:</label>
                    <input type="text" name="category" placeholder="Kategori Pekerjaan" />
                </td>
                <td>
                    <label for="location">Lokasi:</label>
                    <select name="location">
                        <option value="">Pilih</option>
                        <option value="Jakarta">Jakarta</option>
                        <option value="Tangerang">Tangerang</option>
                        <option value="Bandung">Bandung</option>
                        <option value="Riau">Riau</option>
                    </select>
                </td>
                <td>
                    <label for="job_type">Jenis:</label>
                    <select name="job_type">
                        <option value="">Pilih</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Remote">Remote</option>
                        <option value="Freelance">Freelance</option>
                    </select>
                </td>
                <td>
                    <label for="salary_range">Gaji:</label>
                    <select name="salary_range" id="salary_range">
                        <option value="">Pilih</option>
                        <option value="1000000-2000000">1 - 2 Jt</option>
                        <option value="3000000-5000000">2 - 5 Jt</option>
                        <option value="6000000-10000000">5 - 10 Jt</option>
                        <option value="11000000-20000000">10 - 20 Jt</option>
                    </select>
                </td>
            </tr>
        </table>
    </form>

    <div class="content-wrapper">
        <div class="sidebar">
            <div>
                <h3>Filter Pekerjaan</h3>
                <label for="kategori">Kategori</label>
                <input type="text" id="kategori" placeholder="Kategori Pekerjaan" />
                <label for="jenis">Jenis Pekerjaan</label>
                <select id="jenis">
                    <option value="">Pilih Jenis</option>
                    <option>Full-time</option>
                    <option>Part-time</option>
                    <option>Remote</option>
                    <option>Freelance</option>
                </select>
                <label for="gaji">Rentang Gaji</label>
                <input type="text" id="gaji" inputmode="numeric" placeholder="Contoh: 5.000.000 - 10.000.000" />
                <label for="lokasi">Lokasi</label>
                <select id="lokasi">
                    <option value="">Pilih Lokasi</option>
                    <option>Jakarta</option>
                    <option>Tangerang</option>
                    <option>Bandung</option>
                    <option>Riau</option>
                </select>
                <button class="filter-btn" id="applySidebarFilter" type="button">Terapkan Filter</button>
            </div>
        </div>

        <div class="job-list" id="job-results">
            <!-- Hasil lowongan muncul via AJAX -->
            <p style="text-align:center;">Silakan masukkan filter pencarian.</p>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script>
// Fungsi fetch lowongan berdasarkan form utama dan sidebar
function fetchLowongan() {
    const form = document.getElementById('search-form');
    const formData = new FormData(form);

    // Tambahkan data filter sidebar ke formData
    const kategori = document.getElementById('kategori').value.trim();
    if (kategori) formData.set('category', kategori);

    const jenis = document.getElementById('jenis').value;
    if (jenis && jenis !== '') formData.set('job_type', jenis);

    const lokasiSidebar = document.getElementById('lokasi').value;
    if (lokasiSidebar && lokasiSidebar !== '') formData.set('location', lokasiSidebar);

    const gaji = document.getElementById('gaji').value.trim();
    if (gaji) {
        const gajiParts = gaji.replace(/\./g, '').split("-");
        if (gajiParts.length === 2) {
            const minSalary = parseInt(gajiParts[0]);
            const maxSalary = parseInt(gajiParts[1]);
            if (!isNaN(minSalary) && !isNaN(maxSalary)) {
                formData.set('salary_range', `${minSalary}-${maxSalary}`);
            }
        }
    }
    
    const params = new URLSearchParams(formData).toString();

    fetch('fetch_jobs.php?' + params)
        .then(response => response.text())
        .then(html => {
            document.getElementById('job-results').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('job-results').innerHTML = '<p style="color:red;">Terjadi kesalahan saat memuat data.</p>';
            console.error(error);
        });
}

// Event listener input pada form utama
const mainInputs = document.querySelectorAll('#search-form input, #search-form select');
mainInputs.forEach(el => el.addEventListener('input', debounce(fetchLowongan, 300)));

// Event listener klik tombol filter sidebar
document.getElementById('applySidebarFilter').addEventListener('click', fetchLowongan);

// Fungsi debounce agar fetch tidak terlalu sering
function debounce(func, delay) {
    let timeout;
    return function() {
        clearTimeout(timeout);
        timeout = setTimeout(func, delay);
    };
}

// Formatting input gaji dengan titik ribuan dan tanda '-'
document.getElementById("gaji").addEventListener("input", function (e) {
    let input = e.target.value;
    let parts = input.split("-").map(part => part.replace(/\D/g, ''));

    let formatted = parts.map(p => {
        if (p.length === 0) return "";
        return p.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });

    if (formatted.length > 1) {
        e.target.value = formatted[0] + " - " + formatted[1];
    } else {
        e.target.value = formatted[0];
    }
});

// Panggil fetchLowongan pertama kali saat halaman dimuat (tampilkan semua)
window.addEventListener('load', fetchLowongan);
</script>

</body>
</html>

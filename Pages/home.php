<?php
session_start();
require 'db.php'; 
$query = "SELECT * FROM lowongan ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/css/web.css?v=<?= time(); ?>">
    <title>Lowongan Pekerjaan</title>
</head>
<body>
<?php include 'header.php'; ?>

    <main>
        <section class="section">
            <div class="search-section">
                <label for="search">Cari Lowongan:</label>
                <div class="search-container">
                    <input type="text" id="search" placeholder="Nama Perusahaan, Lokasi, dll">
                    <button class="search-btn">🔍</button>
                </div>
            </div>            
            <table class="center">
                <tr class="search-section2">
                    <td>
                        <label for="company">Perusahaan:</label>
                        <input type="text" id="company" placeholder="Nama Perusahaan"">
                    </td>
                    <td>
                        <label for="category">Kategori:</label>
                        <input type="text" id="category" placeholder="Kategori Pekerjaan">
                    </td>
                    <td>
                        <label for="location">Lokasi:</label>
                        <select name="location" id="location">
                            <option value="Pilih">Pilih</option>
                            <option value="Jakarta">Jakarta</option>
                            <option value="Tangerang">Tangerang</option>
                            <option value="Bandung">Bandung</option>
                            <option value="Riau">Riau</option>
                        </select>
                    </td>
                    <td>
                        <label for="job-type">Jenis:</label>
                        <select name="job-type" id="job-type">
                            <option value="Pilih">Pilih</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Remote">Remote</option>
                            <option value="Freelance">Freelance</option>
                        </select>
                    </td>
                    <td>
                        <label for="salary-range">Gaji:</label>
                        <select name="salary-range" id="salary-range">
                            <option value="Pilih">Pilih</option>
                            <option value="2 Jt">2 Jt</option>
                            <option value="4 Jt">4 Jt</option>
                            <option value="6 Jt">6 Jt</option>
                            <option value="8 Jt">8 Jt</option>
                            <option value="10 Jt">10 Jt</option>
                            <option value="15 Jt">15 Jt</option>
                            <option value="20 Jt">20 Jt</option>
                        </select>
                    </td>
                </tr>
            </table>
        </section>

        <div class="content-wrapper">
            <div class="sidebar">
                <div>
                    <h3>Filter Pekerjaan</h3>
    
                <label for="kategori">Kategori</label>
                <select id="kategori">
                    <option>Pilih Kategori</option>
                    <option>IT</option>
                    <option>Administrasi</option>
                    <option>Desain Grafis</option>
                    <option>Pemasaran</option>
                    <option>Pertanian</option>
                </select>
    
                <label for="jenis">Jenis Pekerjaan</label>
                <select id="jenis">
                    <option>Pilih Jenis</option>
                    <option>Full-time</option>
                    <option>Part-time</option>
                    <option>Remote</option>
                    <option>Freelance</option>
                </select>
    
                <label for="gaji">Rentang Gaji</label>
                <input type="text" id="gaji" placeholder="Contoh: 5.000.000 - 10.000.000">
    
                <label for="lokasi">Lokasi</label>
                <select id="lokasi">
                    <option value="Pilih Lokasi">Pilih Lokasi</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Tangerang">Tangerang</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Riau">Riau</option>
                </select>
    
                <button class="filter-btn">Terapkan Filter</button>
                </div>
        </div>
        <div class="job-list">
        <table class="table-job">
            <?php
            $count = 0;
            while ($row = $result->fetch_assoc()) {
                if ($count % 2 == 0) echo "<tr>"; 
            ?>
                <td class="job-listing">
                    <img src="<?= $row['logo_img'] ?>" alt="Logo Perusahaan">
                    <div class="content">
                        <h2><a href="detail.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h2>
                        <p><strong>Perusahaan:</strong> <?= htmlspecialchars($row['perusahaan']) ?></p>
                        <p><strong>Kategori:</strong> <?= htmlspecialchars($row['bidang']) ?></p>
                        <p><strong>Jenis Pekerjaan:</strong> <?= htmlspecialchars($row['tipe']) ?></p>
                        <p><strong>Gaji:</strong> Rp<?= htmlspecialchars($row['gaji']) ?></p>
                        <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
                        <a href="detail.php?id=<?= $row['id'] ?>">Lihat Detail</a>
                    </div>
                </td>
            <?php
                $count++;
                if ($count % 2 == 0) echo "</tr>"; 
            }
            if ($count % 2 != 0) echo "<td></td></tr>"; 
            ?>
        </table>
    </div>
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>
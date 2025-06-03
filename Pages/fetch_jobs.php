<?php
require 'db.php';

$where = [];
$params = [];
$types = "";

// Filter selain salary sama seperti sebelumnya...

if (!empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $where[] = "(perusahaan LIKE ? OR lokasi LIKE ? OR bidang LIKE ? OR tipe LIKE ?)";
    $params = array_merge($params, [$search, $search, $search, $search]);
    $types .= str_repeat("s", 4);
}

if (!empty($_GET['company'])) {
    $where[] = "perusahaan LIKE ?";
    $params[] = "%" . $_GET['company'] . "%";
    $types .= "s";
}

if (!empty($_GET['category'])) {
    $where[] = "bidang LIKE ?";
    $params[] = "%" . $_GET['category'] . "%";
    $types .= "s";
}

if (!empty($_GET['location'])) {
    $where[] = "lokasi = ?";
    $params[] = $_GET['location'];
    $types .= "s";
}

if (!empty($_GET['job_type'])) {
    $where[] = "tipe = ?";
    $params[] = $_GET['job_type'];
    $types .= "s";
}

// Query tanpa filter salary dulu
$sql = "SELECT * FROM lowongan";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$count = 0;

// Ambil rentang salary dari GET untuk filter PHP
$filterMin = 0;
$filterMax = PHP_INT_MAX;
if (!empty($_GET['salary_range'])) {
    $rangeParts = explode('-', $_GET['salary_range']);
    if (count($rangeParts) == 2) {
        $filterMin = (int)trim($rangeParts[0]);
        $filterMax = (int)trim($rangeParts[1]);
    }
}

echo '<table class="table-job">';
while ($row = $result->fetch_assoc()) {
    // Parse gaji dari string misal "2000000-4000000"
    $gajiMin = 0;
    $gajiMax = 0;
    if (strpos($row['gaji'], '-') !== false) {
        list($min, $max) = explode('-', $row['gaji']);
        $gajiMin = (int) filter_var($min, FILTER_SANITIZE_NUMBER_INT);
        $gajiMax = (int) filter_var($max, FILTER_SANITIZE_NUMBER_INT);
    } else {
        $gajiMin = $gajiMax = (int) filter_var($row['gaji'], FILTER_SANITIZE_NUMBER_INT);
    }

    // Filter berdasarkan salary range yang dipilih
    // Jika gaji minimal atau maksimal overlap dengan rentang filter maka tampilkan
    if (($gajiMax >= $filterMin) && ($gajiMin <= $filterMax)) {
        if ($count % 2 == 0) echo "<tr>";
        ?>
        <td class="job-listing">
            <img src="<?= htmlspecialchars($row['logo']) ?>" alt="Logo Perusahaan">
            <div class="content">
                <h2><a href="detail.php?id=<?= (int)$row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h2>
                <p><strong>Perusahaan:</strong> <?= htmlspecialchars($row['perusahaan']) ?></p>
                <p><strong>Kategori:</strong> <?= htmlspecialchars($row['bidang']) ?></p>
                <p><strong>Jenis Pekerjaan:</strong> <?= htmlspecialchars($row['tipe']) ?></p>
                <?php
                $gajiFormatted = '';
                if (strpos($row['gaji'], '-') !== false) {
                    list($min, $max) = explode('-', $row['gaji']);
                    $gajiFormatted = 'Rp ' . number_format((float)str_replace(['.', ','], '', $min), 0, ',', '.') . ' - Rp ' . number_format((float)str_replace(['.', ','], '', $max), 0, ',', '.');
                } else {
                    $gajiFormatted = 'Rp ' . number_format((float)str_replace(['.', ','], '', $row['gaji']), 0, ',', '.');
                }
                ?>
                <p><strong>Gaji:</strong> <?= $gajiFormatted ?></p>
                <p><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></p>
                <a href="detail.php?id=<?= (int)$row['id'] ?>">Lihat Detail</a>
            </div>
        </td>
        <?php
        $count++;
        if ($count % 2 == 0) echo "</tr>";
    }
}
if ($count % 2 != 0) echo "<td></td></tr>";
echo '</table>';
?>

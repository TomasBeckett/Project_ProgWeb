<?php
// lihat_pdf.php
$fileName = $_GET['file'] ?? '';
$file = __DIR__ . '/uploads/' . basename($fileName);

if ($fileName && file_exists($file)) {
    header('Content-Type: application/pdf');
    header('Content-Length: ' . filesize($file));
    // Tidak pakai Content-Disposition: attachment, jadi PDF terbuka di browser
    readfile($file);
    exit;
} else {
    echo "File tidak ditemukan.";
}
?>

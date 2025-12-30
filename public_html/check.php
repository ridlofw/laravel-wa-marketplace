<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo "<h1>Diagnosa Server</h1>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";
$autoloadPath = __DIR__ . '/vendor/autoload.php';
$bootstrapPath = __DIR__ . '/bootstrap/app.php';
echo "<h2>Cek File Penting</h2>";
if (file_exists($autoloadPath)) {
    echo "<span style='color:green'>[OK]</span> vendor/autoload.php ditemukan.<br>";
} else {
    echo "<span style='color:red'>[ERROR]</span> vendor/autoload.php TIDAK ditemukan!<br>";
    echo "Path yang dicari: $autoloadPath<br>";
    echo "Pastikan folder 'vendor' sudah diupload dan sejajar dengan file ini.<br>";
}
if (file_exists($bootstrapPath)) {
    echo "<span style='color:green'>[OK]</span> bootstrap/app.php ditemukan.<br>";
} else {
    echo "<span style='color:red'>[ERROR]</span> bootstrap/app.php TIDAK ditemukan!<br>";
}
echo "<h2>Cek Folder Permission</h2>";
$storagePath = __DIR__ . '/storage';
if (is_writable($storagePath)) {
    echo "<span style='color:green'>[OK]</span> Folder storage bisa ditulis (Writable).<br>";
} else {
    echo "<span style='color:red'>[ERROR]</span> Folder storage TIDAK bisa ditulis!<br>"; 
    echo "Mohon ubah permission folder storage menjadi 775 atau 777.<br>";
}
?>
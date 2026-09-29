<?php
// 1. Muat konfigurasi database
require_once 'config/database.php';

// 2. Sekarang kita ubah halaman DEFAULT-nya menjadi 'landing'
$page = isset($_GET['page']) ? $_GET['page'] : 'landing';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magang Verify</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php 
    // 3. Panggil Navbar (Otomatis muncul di semua halaman)
    include 'components/Navbar.php'; 
    ?>

    <main class="container">
        <?php
        // 4. Sistem Routing Dinamis
        switch ($page) {
            case 'landing':
                // Memanggil file landing page Anda di pages/index.php
                include 'pages/index.php'; 
                break;

            case 'mahasiswa':
                // Memanggil halaman data mahasiswa
                include 'pages/mahasiswa/index.php';
                break;

            default:
                echo "<h2 style='text-align:center; margin-top:50px;'>404 - Halaman Tidak Ditemukan</h2>";
                break;
        }
        ?>
    </main>

    <?php 
    // 5. Panggil Footer (Otomatis muncul di semua halaman)
    include 'components/Footer.php'; 
    ?>

    <script src="assets/js/script.js"></script>
</body>
</html>

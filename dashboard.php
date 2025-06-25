<?php

session_start();

// Periksa apakah user sudah login dan apakah role-nya admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    // Jika belum login, atau bukan admin, redirect ke halaman login
    header("Location: login.php");
    exit();
}
// koneksi.php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="images/Logo_CuciKain.png" type="image/x-icon">
    <title>Dashboard Aplikasi</title>
    <style>
        /* CSS Umum untuk Layout Dashboard */
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            /* Menggunakan flexbox untuk layout sidebar dan konten */
            min-height: 100vh;
            /* Pastikan tinggi minimal 100% viewport */
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            background-color: #28a7e2;
            /* Warna biru sidebar sesuai gambar */
            color: white;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar-logo img {
            width: 100px;
            /* Sesuaikan ukuran logo */
            height: auto;
            border-radius: 8px;
            /* Jika logo berupa kotak/gambar */
            background-color: #fff;
            /* Latar belakang untuk logo seperti di gambar */
            padding: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 5px;
        }

        .sidebar ul li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #1e87bb;
            /* Warna hover/active lebih gelap */
        }

        .main-content {
            flex-grow: 1;
            /* Mengisi sisa ruang */
            padding: 20px;
            background-color: #f9f9f9;
        }

        /* Styling untuk konten yang dimuat (mirip dengan main.php sebelumnya) */
        .content-area {
            /* Lebar konten utama */
            margin: 0 auto;
            /* Tengah */
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content-area h2 {
            text-align: left;
            /* Sesuaikan dengan gambar */
            color: #333;
            margin-bottom: 20px;
        }

        /* Styling untuk tabel dan form di dalam content-area */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            margin: 5px 0;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .btn-add {
            background-color: #4CAF50;
            color: white;
        }

        .btn-edit {
            background-color: #008CBA;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        form {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="images/Logo_CuciKain.png" alt="Logo">
        </div>
        <ul>
            <li><a href="dashboard.php?page=informasi" <?php if (!isset($_GET['page']) || $_GET['page'] == 'informasi') echo 'class="active"'; ?>>Informasi</a></li>
            <li><a href="dashboard.php?page=user" <?php if (isset($_GET['page']) && $_GET['page'] == 'user') echo 'class="active"'; ?>>User</a></li>
            <li><a href="dashboard.php?page=detail_pesanan" <?php if (isset($_GET['page']) && $_GET['page'] == 'detail_pesanan') echo 'class="active"'; ?>>Detail Pesanan</a></li>
            <li><a href="dashboard.php?page=layanan" <?php if (isset($_GET['page']) && $_GET['page'] == 'layanan') echo 'class="active"'; ?>>Layanan</a></li>
            <li><a href="dashboard.php?page=pesanan" <?php if (isset($_GET['page']) && $_GET['page'] == 'pesanan') echo 'class="active"'; ?>>Pesanan</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content-area">
            <?php
            // Logika untuk include file berdasarkan parameter 'page'
            $page = isset($_GET['page']) ? $_GET['page'] : 'informasi'; // Default ke informasi jika tidak ada parameter

            switch ($page) {
                case 'user':
                    include 'dashboard_user.php';
                    break;
                case 'detail_pesanan':
                    include 'dashboard_detail_pesanan.php';
                    break;
                case 'layanan':
                    include 'dashboard_layanan.php';
                    break;
                case 'pesanan':
                    include 'dashboard_pesanan.php';
                    break;
                case 'informasi':
                default: // Default atau jika ada parameter yang tidak dikenal
                    echo "<h2>Selamat Datang di Dashboard!</h2><p>Pilih menu dari sidebar untuk melihat konten.</p>";
                    break;
            }
            ?>
        </div>
    </div>
    <?php mysqli_close($koneksi); ?>
</body>

</html>
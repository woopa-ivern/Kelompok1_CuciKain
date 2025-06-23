<?php
// koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cuci_kain";
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Aplikasi</title>
    <style>
        /* CSS Umum untuk Layout Dashboard */
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
            max-width: 850px;
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
            text-align: left;
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
            <li><a href="dashboard.php?page=detail_pesanan" <?php if (isset($_GET['page']) && $_GET['page'] == 'detail_pesanan') echo 'class="active"'; ?>>Detail Pesanan</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="content-area">
            <?php
            include 'dashboard_detail_pesanan.php';
            ?>
        </div>
    </div>
    <?php mysqli_close($koneksi); ?>
</body>

</html>
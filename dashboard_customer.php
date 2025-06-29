<?php
session_start(); // Mulai sesi

// Include file koneksi database Anda
include 'koneksi.php';

// Cek apakah user sudah login dan levelnya sesuai (misal: 'pelanggan' atau 'user')
if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    // Jika tidak ada ID user di sesi atau ID user kosong, redirect ke halaman login
    header("Location: login.php"); // Ganti login.php dengan halaman login Anda
    exit();
}

$id_pelanggan_login = $_SESSION['id_user']; // Ambil ID pelanggan dari sesi

// Logika Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();   // Hapus semua variabel sesi
    session_destroy(); // Hancurkan sesi
    header("Location: login.php"); // Redirect ke halaman login setelah logout
    exit();
}

// --- Ambil data status pesanan untuk pelanggan yang sedang login ---
// Perbaikan: Menghitung SUM dari subtotal di detail_pesanan
$query_status_pesanan = "
    SELECT
        p.id_pesanan,
        u.nama AS nama_pelanggan,
        p.tanggal,
        p.status AS status_pesanan, -- Menggunakan 'status' dari tabel pesanan
        SUM(dp.subtotal) AS total_harga_dihitung
    FROM
        pesanan p
    JOIN
        user u ON p.id_user = u.id_user
    LEFT JOIN
        detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
    WHERE
        p.id_user = '$id_pelanggan_login'
    GROUP BY
        p.id_pesanan, u.nama, p.tanggal, p.status
    ORDER BY
        p.tanggal DESC, p.id_pesanan DESC;
";
$result_status_pesanan = mysqli_query($koneksi, $query_status_pesanan);

// Ambil nama pelanggan yang login untuk ditampilkan di dashboard
$query_nama_pelanggan = "SELECT nama FROM user WHERE id_user = '$id_pelanggan_login'";
$result_nama_pelanggan = mysqli_query($koneksi, $query_nama_pelanggan);
$data_pelanggan = mysqli_fetch_assoc($result_nama_pelanggan);
$nama_pelanggan_login = $data_pelanggan['nama'] ?? 'Pengguna'; // Default jika nama tidak ditemukan

// Tutup koneksi database setelah semua operasi selesai
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="images/Logo_CuciKain.png" type="image/x-icon">
    <title>Dashboard Pelanggan</title>
    <style>
        /* CSS Umum untuk Layout Dashboard */
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            background-color: #28a7e2;
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
            height: auto;
            border-radius: 8px;
            background-color: #fff;
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
            background-color: #28a7e2;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .content-area {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content-area h2 {
            text-align: left;
            color: #333;
            margin-bottom: 20px;
        }

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

        .logout-button {
            display: block;
            padding: 15px 20px;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 0;
            margin-top: auto;
            transition: background-color 0.3s ease;
        }

        .logout-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="images/Logo_CuciKain.png" alt="Logo CuciKain">
            <h3>CuciKain</h3>
        </div>
        <ul>
            <li><a href="#" class="active">Dashboard Pelanggan</a></li>
        </ul>
        <a href="?action=logout" class="logout-button" onclick="return confirm('Yakin ingin logout?');">Logout</a>
    </div>

    <div class="main-content">
        <div class="content-area">
            <h2>Selamat Datang, <?php echo htmlspecialchars($nama_pelanggan_login); ?>!</h2>
            <p>Berikut adalah status pesanan Anda:</p>

            <?php if (mysqli_num_rows($result_status_pesanan) > 0) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal Pesanan</th>
                            <th>Total Harga</th>
                            <th>Status Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_status_pesanan)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id_pesanan']); ?></td>
                                <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                <td>Rp. <?php echo number_format($row['total_harga_dihitung'] ?? 0, 2, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['status_pesanan']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>Anda belum memiliki pesanan yang tercatat.</p>
            <?php } ?>
        </div>
    </div>

</body>

</html>
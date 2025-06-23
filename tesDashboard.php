<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - <?php echo htmlspecialchars($current_username); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f2f5;
    display: flex; /* Untuk menempatkan sidebar dan konten */
    min-height: 100vh; /* Agar mengisi seluruh tinggi viewport */
}

.container {
    display: flex;
    width: 100%;
}

/* Sidebar */
.sidebar {
    width: 250px; /* Lebar sidebar */
    background-color: #2c3e50; /* Warna biru gelap */
    color: white;
    padding: 20px 0;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    text-align: center;
    margin-bottom: 30px;
}

.logo-icon {
    width: 80px; /* Ukuran icon */
    height: 80px;
    background-color: #3498db; /* Warna biru muda */
    border-radius: 10px; /* Sedikit rounded */
    display: inline-flex; /* Agar bisa align center */
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8em; /* Ukuran teks 'Logo' */
    font-weight: bold;
    /* Ganti background-color dan border-radius jika ingin mengikuti gaya mesin cuci */
}

.sidebar-nav {
    flex-grow: 1; /* Agar nav item memenuhi sisa ruang */
}

.nav-item {
    display: block;
    padding: 15px 25px;
    color: white;
    text-decoration: none;
    font-size: 1.1em;
    transition: background-color 0.3s ease;
}

.nav-item:hover,
.nav-item.active {
    background-color: #34495e; /* Warna sedikit lebih terang saat hover/aktif */
}

/* Konten Kanan */
.content {
    flex-grow: 1; /* Mengambil sisa lebar */
    padding: 30px;
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

.content-section {
    display: none; /* Sembunyikan semua bagian konten secara default */
}

.content-section.active {
    display: block; /* Tampilkan hanya bagian yang aktif */
}

h2 {
    color: #333;
    margin-bottom: 20px;
}

/* Styling untuk form dan tabel di bagian "Customer" */
form {
    margin-top: 20px;
    padding: 20px;
    border: 1px solid #eee;
    border-radius: 8px;
    background-color: #f9f9f9;
}

form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

form input[type="text"],
form input[type="password"],
form textarea,
form select {
    width: calc(100% - 22px); /* Kurangi padding dan border */
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box; /* Pastikan padding termasuk dalam lebar */
}

form input[type="submit"] {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    width: auto; /* Sesuaikan lebar dengan konten */
}

form input[type="submit"]:hover {
    background-color: #0056b3;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

button {
    padding: 8px 12px;
    margin-right: 5px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    background-color: #4CAF50; /* Green */
    color: white;
}

button:hover {
    opacity: 0.9;
}

button:nth-of-type(2) { /* Tombol Hapus */
    background-color: #f44336; /* Red */
}
</style>
<body>

    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="https://via.placeholder.com/60x60.png?text=Logo" alt="Logo" class="logo-icon">
                </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item" data-target="info">Informasi</a>
                <a href="#" class="nav-item active" data-target="customer">Customer</a>
                <a href="#" class="nav-item" data-target="detail-pesanan">Detail Pesanan</a>
                <a href="#" class="nav-item" data-target="layanan">Layanan</a>
                <a href="#" class="nav-item" data-target="pesanan">Pesanan</a>
            </nav>
        </div>

        <div class="content">
            <div id="customer" class="content-section active">
                <h2>Edit Data Seluruh User</h2>
                <p>Di sini Anda dapat mengelola data pengguna yang terdaftar.</p>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users_data as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['nama']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <button onclick="alert('Edit User ID: <?php echo $user['id']; ?>')">Edit</button>
                                <button onclick="alert('Hapus User ID: <?php echo $user['id']; ?>')">Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h3>Tambah User Baru</h3>
                <form action="tambah_user_action.php" method="post">
                    <label for="nama">Nama:</label><br>
                    <input type="text" id="nama" name="nama" required><br><br>

                    <label for="alamat">Alamat:</label><br>
                    <textarea id="alamat" name="alamat"></textarea><br><br>

                    <label for="no_hp">No. HP:</label><br>
                    <input type="text" id="no_hp" name="no_hp"><br><br>

                    <label for="username">Username:</label><br>
                    <input type="text" id="username" name="username" required><br><br>

                    <label for="password">Password:</label><br>
                    <input type="password" id="password" name="password" required><br><br>

                    <label for="role">Role:</label><br>
                    <select id="role" name="role" required>
                        <option value="user">User Biasa</option>
                        <option value="editor">Editor Konten</option>
                        <option value="admin">Administrator</option>
                    </select><br><br>

                    <input type="submit" value="Tambah Pengguna">
                </form>

                <p>Untuk fungsi "Tambah Pengguna", Anda perlu membuat file `tambah_user_action.php` yang berisi logika PHP untuk menyimpan data ke database (seperti yang kita bahas sebelumnya, dengan hashing password dan prepared statement).</p>

            </div>

            <div id="detail-pesanan" class="content-section">
                <h2>Detail Pesanan</h2>
                <p>Ini adalah area untuk menampilkan detail pesanan.</p>
                <p>Data pesanan akan dimuat di sini.</p>
                </div>

            <div id="info" class="content-section">
                <h2>Informasi Umum</h2>
                <p>Ini adalah halaman informasi.</p>
            </div>
            <div id="layanan" class="content-section">
                <h2>Layanan</h2>
                <p>Detail layanan yang tersedia.</p>
            </div>
            <div id="pesanan" class="content-section">
                <h2>Pesanan</h2>
                <p>Daftar pesanan aktif.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            const contentSections = document.querySelectorAll('.content-section');

            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah link berpindah halaman

                    // Hapus kelas 'active' dari semua nav item
                    navItems.forEach(nav => nav.classList.remove('active'));
                    // Tambahkan kelas 'active' ke nav item yang diklik
                    this.classList.add('active');

                    // Sembunyikan semua content sections
                    contentSections.forEach(section => section.classList.remove('active'));

                    // Tampilkan content section yang sesuai
                    const targetId = this.dataset.target;
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        targetSection.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
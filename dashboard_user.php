<?php
include 'koneksi.php';

// Ambil semua data dari tabel users
$query = "SELECT * FROM user ORDER BY id_user DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Sederhana</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color: #f4f4f4;
}

.container {
    max-width: 900px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
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

.btn {
    display: inline-block;
    padding: 8px 15px;
    margin: 5px 0;
    text-decoration: none;
    border-radius: 5px;
    cursor: pointer;
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
    <div class="container">
        <h2>Data Pengguna</h2>
        <a href="#form-tambah" class="btn btn-add">Tambah Pengguna Baru</a>
        <br><br>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) { ?>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id_user']; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['alamat']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['no_hp']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $row['id_user']; ?>" class="btn btn-edit">Edit</a>
                                <a href="hapus.php?id=<?php echo $row['id_user']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="8">Tidak ada data.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <hr>

        <h2 id="form-tambah">Tambah Pengguna</h2>
        <form action="tambah_user.php" method="POST">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="alamat">Alamat:</label>
            <input type="text" id="alamat" name="alamat" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="no_hp">No. HP:</label>
            <input type="text" id="no_hp" name="no_hp" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>

            <button type="submit">Tambah</button>
        </form>
    </div>
</body>
</html>
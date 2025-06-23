<?php
include 'koneksi.php';

if (isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];
    $query = "SELECT * FROM user WHERE id_user=$id_user";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
} else {
    header('Location: index.php'); // Kembali ke index jika tidak ada ID
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Pengguna</h2>
        <form action="update_user.php" method="POST">
            <input type="hidden" name="id_user" value="<?php echo $data['id_user']; ?>">

            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo $data['nama']; ?>" required>

            <label for="alamat">Alamat:</label>
            <input type="text" id="alamat" name="alamat" value="<?php echo $data['alamat']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>" required>

            <label for="no_hp">No. HP:</label>
            <input type="text" id="no_hp" name="no_hp" value="<?php echo $data['no_hp']; ?>" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $data['username']; ?>" required>

            <label for="password">Password (kosongkan jika tidak ingin diubah):</label>
            <input type="password" id="password" name="password">

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin" <?php if($data['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                <option value="user" <?php if($data['role'] == 'user') echo 'selected'; ?>>User</option>
            </select>

            <button type="submit">Update</button>
            <a href="index.php" class="btn" style="background-color: #6c757d; color: white;">Batal</a>
        </form>
    </div>
</body>
</html>
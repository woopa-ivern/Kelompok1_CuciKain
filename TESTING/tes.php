<?php
// Pengaturan Koneksi Database - GANTI DENGAN PUNYA ANDA!
$servername = "localhost";
$username = "root";     // Nama pengguna database Anda
$password = "";         // Kata sandi database Anda
$dbname = "cuci_kain"; // Nama database Anda

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$message = ""; // Pesan untuk menampilkan status

// Tangani Pengiriman Form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form secara langsung
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Kata sandi mentah (TIDAK AMAN!)
    $role = $_POST['role'];

    // Lakukan INSERT ke database (Sangat RENTAN SQL INJECTION dan TIDAK HASH PASSWORD)
    $sql = "INSERT INTO users (nama, alamat, no_hp, username, password, role)
            VALUES ('$nama', '$alamat', '$no_hp', '$username', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        $message = "Pengguna baru berhasil ditambahkan!";
        // Kosongkan $_POST agar form reset setelah berhasil
        $_POST = array();
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Tutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna Baru</title>
</head>
<body>
    <h2>Tambah Pengguna Baru</h2>

    <?php
    // Tampilkan pesan
    if (!empty($message)) {
        echo "<p>" . $message . "</p>";
    }
    ?>

    <form action="" method="post">
        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama" value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>"><br><br>

        <label for="alamat">Alamat:</label><br>
        <textarea id="alamat" name="alamat"><?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?></textarea><br><br>

        <label for="no_hp">No. HP:</label><br>
        <input type="text" id="no_hp" name="no_hp" value="<?php echo isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : ''; ?>"><br><br>

        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>

        <label for="role">Role:</label><br>
        <input type="text" id="role" name="role" value="<?php echo isset($_POST['role']) ? htmlspecialchars($_POST['role']) : ''; ?>"><br><br>

        <input type="submit" value="Tambah Pengguna">
    </form>
</body>
</html>
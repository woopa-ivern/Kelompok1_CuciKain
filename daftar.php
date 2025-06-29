<?php
// Bagian 1: Konfigurasi Database
include 'koneksi.php';

// Pastikan koneksi database berhasil dibuat di koneksi.php
// Jika koneksi gagal, pastikan koneksi.php sudah menangani ini dengan 'die()'
// Atau Anda bisa tambahkan cek di sini jika 'koneksi.php' tidak langsung 'die'
if (!isset($koneksi) || !$koneksi) {
    die("Error: Variabel koneksi \$koneksi tidak tersedia atau koneksi gagal.");
}


// Bagian 2: Proses Data Form (jika form disubmit)
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // !!! PENTING: Gunakan prepared statements untuk keamanan !!!
    // Contoh sederhana untuk debugging, masih rentan SQL injection tanpa prepared statement
    $query_insert = "INSERT INTO user (nama, alamat, no_hp, username, password, role)
                     VALUES ('$nama', '$alamat', '$no_hp', '$username', '$password', '$role')";

    if (mysqli_query($koneksi, $query_insert)) {
        header("Location: login.php");
        exit(); // Penting: selalu panggil exit() setelah header()
    } else {
        // TAMPILKAN ERROR NYATA DARI MYSQL UNTUK DEBUGGING
        echo "Gagal mendaftar! Error: " . mysqli_error($koneksi);
    }
}

?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/kontak.css">
<main>
    <article>
        <section>
            <div class="contents content-5 container">
                <div class="wrapper">
                    <form action="" method="post">
                        <div class="login-container">
                            <h2>Daftar Customer Baru</h2>
                            <p>Dengan mendaftar kamu dapat melihat proses pesananan kamu</p>

                            <input type="text" placeholder="Nama Lengkap" name="nama" required>
                            <input type="text" placeholder="Alamat" name="alamat" required>
                            <input type="text" placeholder="No HP" name="no_hp" required>
                            <input type="text" placeholder="Username" name="username" required>
                            <input type="password" placeholder="Password" name="password" required>
                            <input type="text" placeholder="Role" name="role" hidden value="user">

                            <div class="paket">
                                <a href="paket.php">Masih bingung?</a>
                            </div>

                            <button type="submit" name="submit" class="login-btn">Daftar</button>
                            <div class="galeri">
                                Sudah punya akun? <a href="login.php">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </article>
</main>
<?php include 'footer.php'; ?>  
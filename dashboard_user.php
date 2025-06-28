<?php
// Pastikan koneksi database sudah ada atau include di sini jika dashboard.php tidak meng-include-nya
// Jika dashboard.php sudah meng-include koneksi, Anda bisa menghapus bagian ini.
include 'koneksi.php';


// =====================================================================================================
// Bagian Logika CRUD (PHP)
// =====================================================================================================

$pesan = ''; // Variabel untuk menyimpan pesan notifikasi

// --- Tambah Data (Create) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $no_hp    = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $role     = $_POST['role'];

    $query_tambah = "INSERT INTO user (nama, alamat, no_hp, username, password, role) VALUES ('$nama', '$alamat', '$no_hp', '$username', '$password', '$role')";

    if (mysqli_query($koneksi, $query_tambah)) {
        $pesan = "<script>alert('Data berhasil ditambahkan!');</script>"; // Alert ini mungkin tidak muncul karena redirect
        header("Location: dashboard.php?page=user");
        exit(); // <<<--- PASTIKAN ADA INI SETELAH REDIRECT
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Update Data (Update) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
    $id_user  = $_POST['id_user'];
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $no_hp    = $_POST['no_hp'];
    $username = $_POST['username'];
    $role     = $_POST['role'];

    $query_update = "";
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query_update = "UPDATE user SET nama='$nama', alamat='$alamat', no_hp='$no_hp', username='$username', password='$password', role='$role' WHERE id_user=$id_user";
    } else {
        $query_update = "UPDATE user SET nama='$nama', alamat='$alamat', no_hp='$no_hp', username='$username', role='$role' WHERE id_user=$id_user";
    }

    if (mysqli_query($koneksi, $query_update)) {
        $pesan = "<script>alert('Data berhasil diupdate!');</script>"; // Alert ini mungkin tidak muncul karena redirect
        header("Location: dashboard.php?page=user");
        exit(); // <<<--- PASTIKAN ADA INI SETELAH REDIRECT
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Hapus Data (Delete) ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];
    $query_hapus = "DELETE FROM user WHERE id_user=$id_user";

    if (mysqli_query($koneksi, $query_hapus)) {
        $pesan = "<script>alert('Data berhasil dihapus!');</script>"; // Alert ini mungkin tidak muncul karena redirect
        header("Location: dashboard.php?page=user");
        exit(); // <<<--- PASTIKAN ADA INI SETELAH REDIRECT
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Ambil Data untuk Ditampilkan (Read) ---
$query_read = "SELECT * FROM user ORDER BY id_user DESC";
$result_read = mysqli_query($koneksi, $query_read);

// --- Ambil Data untuk Form Edit ---
$data_edit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && isset($_GET['id_user'])) {
    $id_edit = $_GET['id_user'];
    $query_edit = "SELECT * FROM user WHERE id_user=$id_edit";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $data_edit = mysqli_fetch_assoc($result_edit);
}

// Tampilkan pesan jika ada
echo $pesan;
?>

<h2>Manajemen Pengguna</h2>

<a href="dashboard.php?page=user&aksi=tambah_form" class="btn btn-add">Tambah Pengguna Baru</a>
<br><br>

<table>
    <thead>
        <tr>
            <th>ID User</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No. HP</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result_read) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result_read)) { ?>
                <tr>
                    <td><?php echo $row['id_user']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['alamat']; ?></td>
                    <td><?php echo $row['no_hp']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a href="dashboard.php?page=user&aksi=edit&id_user=<?php echo $row['id_user']; ?>" class="btn btn-edit">Edit</a>
                        <a href="dashboard.php?page=user&aksi=hapus&id_user=<?php echo $row['id_user']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="7">Tidak ada data.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<hr>

<?php
// Tampilkan form tambah atau edit berdasarkan parameter 'aksi'
if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambah_form') {
?>
    <h2>Form Tambah Pengguna</h2>
    <form action="dashboard_user.php" method="POST">
        <input type="hidden" name="aksi" value="tambah">

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="alamat">Alamat:</label>
        <input type="text" id="alamat" name="alamat" required>

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
        <a href="dashboard.php?page=user" class="btn btn-cancel">Batal</a>
    </form>
<?php
} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && $data_edit) {
?>
    <h2>Form Edit Pengguna</h2>
    <form action="dashboard_user.php" method="POST">
        <input type="hidden" name="aksi" value="update">
        <input type="hidden" name="id_user" value="<?php echo $data_edit['id_user']; ?>">

        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="<?php echo $data_edit['nama']; ?>" required>

        <label for="alamat">Alamat:</label>
        <input type="text" id="alamat" name="alamat" value="<?php echo $data_edit['alamat']; ?>" required>

        <label for="no_hp">No. HP:</label>
        <input type="text" id="no_hp" name="no_hp" value="<?php echo $data_edit['no_hp']; ?>" required>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $data_edit['username']; ?>" required>

        <label for="password">Password (kosongkan jika tidak ingin diubah):</label>
        <input type="password" id="password" name="password">

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin" <?php if ($data_edit['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="user" <?php if ($data_edit['role'] == 'user') echo 'selected'; ?>>User</option>
        </select>

        <button type="submit">Update</button>
        <a href="dashboard.php?page=user" class="btn btn-cancel">Batal</a>
    </form>
<?php
}
?>
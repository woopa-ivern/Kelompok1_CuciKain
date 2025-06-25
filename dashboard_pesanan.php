<?php
// Bagian Koneksi Database
include 'koneksi.php';


// =====================================================================================================
// Bagian Logika CRUD (PHP)
// =====================================================================================================

$pesan = ''; // Variabel untuk menyimpan pesan notifikasi

// --- Tambah Data (Create) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id_user = $_POST['id_user'];
    $tanggal = $_POST['tanggal'];
    $status  = $_POST['status'];

    $query_tambah = "INSERT INTO pesanan (id_user, tanggal, status) VALUES ('$id_user', '$tanggal', '$status')";

    if (mysqli_query($koneksi, $query_tambah)) {
        $pesan = "<script>alert('Data berhasil ditambahkan!');</script>"; // Alert ini mungkin tidak muncul karena redirect
        header("Location: dashboard.php?page=pesanan"); // Redirect ke halaman pesanan
        exit(); // PENTING: Hentikan eksekusi script setelah redirect
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Update Data (Update) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
    $id_pesanan = $_POST['id_pesanan'];
    $id_user    = $_POST['id_user'];
    $tanggal    = $_POST['tanggal'];
    $status     = $_POST['status'];

    $query_update = "UPDATE pesanan SET id_user='$id_user', tanggal='$tanggal', status='$status' WHERE id_pesanan=$id_pesanan";

    if (mysqli_query($koneksi, $query_update)) {
        // $pesan = "<script>alert('Data berhasil diupdate!');</script>";
        header("Location: dashboard.php?page=pesanan"); // Redirect ke halaman pesanan
        exit(); // PENTING
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Hapus Data (Delete) ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id_pesanan'])) {
    $id_pesanan = $_GET['id_pesanan'];
    $query_hapus = "DELETE FROM pesanan WHERE id_pesanan=$id_pesanan";

    if (mysqli_query($koneksi, $query_hapus)) {
        // $pesan = "<script>alert('Data berhasil dihapus!');</script>";
        header("Location: dashboard.php?page=pesanan"); // Redirect ke halaman pesanan
        exit(); // PENTING
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Ambil Data untuk Ditampilkan (Read) ---
// Join dengan tabel user untuk menampilkan nama user
$query_read = "SELECT p.*, u.nama AS nama_user
               FROM pesanan p
               JOIN user u ON p.id_user = u.id_user
               ORDER BY p.id_pesanan DESC";
$result_read = mysqli_query($koneksi, $query_read);

// --- Ambil Data untuk Form Edit ---
$data_edit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && isset($_GET['id_pesanan'])) {
    $id_edit = $_GET['id_pesanan'];
    $query_edit = "SELECT * FROM pesanan WHERE id_pesanan=$id_edit";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $data_edit = mysqli_fetch_assoc($result_edit);
}

// Ambil data user untuk dropdown di form
$query_user = "SELECT id_user, nama FROM user ORDER BY nama ASC";
$result_user = mysqli_query($koneksi, $query_user);

// Tampilkan pesan jika ada error yang tidak menyebabkan redirect
echo $pesan;
?>

<h2>Manajemen Pesanan</h2>

<a href="dashboard.php?page=pesanan&aksi=tambah_form" class="btn btn-add">Tambah Pesanan Baru</a>
<br><br>

<table>
    <thead>
        <tr>
            <th>ID Pesanan</th>
            <th>ID User</th>
            <th>Nama Pengguna</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result_read) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result_read)) { ?>
                <tr>
                    <td><?php echo $row['id_pesanan']; ?></td>
                    <td><?php echo $row['id_user']; ?></td>
                    <td><?php echo $row['nama_user']; ?></td>
                    <td><?php echo $row['tanggal']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <a href="dashboard.php?page=pesanan&aksi=edit&id_pesanan=<?php echo $row['id_pesanan']; ?>" class="btn btn-edit">Edit</a>
                        <a href="dashboard.php?page=pesanan&aksi=hapus&id_pesanan=<?php echo $row['id_pesanan']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6">Tidak ada data.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<hr>

<?php
// Tampilkan form tambah atau edit berdasarkan parameter 'aksi'
if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambah_form') {
?>
    <h2>Form Tambah Pesanan</h2>
    <form action="dashboard_pesanan.php" method="POST">
        <input type="hidden" name="aksi" value="tambah">

        <label for="id_user">Pengguna:</label>
        <select id="id_user" name="id_user" required>
            <option value="">Pilih Pengguna</option>
            <?php while ($u = mysqli_fetch_assoc($result_user)) { ?>
                <option value="<?php echo $u['id_user']; ?>"><?php echo $u['nama']; ?></option>
            <?php } ?>
        </select>

        <label for="tanggal">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Dipesan">Dipesan</option>
            <option value="Diproses">Diproses</option>
            <option value="Selesai">Selesai</option>
            <option value="Diambil">Diambil</option>
            <option value="Dibatalkan">Dibatalkan</option>
        </select>

        <button type="submit">Tambah</button>
        <a href="dashboard.php?page=pesanan" class="btn btn-cancel">Batal</a>
    </form>
<?php
} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && $data_edit) {
?>
    <h2>Form Edit Pesanan</h2>
    <form action="dashboard_pesanan.php" method="POST">
        <input type="hidden" name="aksi" value="update">
        <input type="hidden" name="id_pesanan" value="<?php echo $data_edit['id_pesanan']; ?>">

        <label for="id_user">Pengguna:</label>
        <select id="id_user" name="id_user" required>
            <option value="">Pilih Pengguna</option>
            <?php
            // Reset pointer result_user agar bisa digunakan lagi
            mysqli_data_seek($result_user, 0);
            while ($u = mysqli_fetch_assoc($result_user)) { ?>
                <option value="<?php echo $u['id_user']; ?>" <?php if ($u['id_user'] == $data_edit['id_user']) echo 'selected'; ?>><?php echo $u['nama']; ?></option>
            <?php } ?>
        </select>

        <label for="tanggal">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" value="<?php echo $data_edit['tanggal']; ?>" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Dipesan" <?php if ($data_edit['status'] == 'Dipesan') echo 'selected'; ?>>Dipesan</option>
            <option value="Diproses" <?php if ($data_edit['status'] == 'Diproses') echo 'selected'; ?>>Diproses</option>
            <option value="Selesai" <?php if ($data_edit['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
            <option value="Diambil" <?php if ($data_edit['status'] == 'Diambil') echo 'selected'; ?>>Diambil</option>
            <option value="Dibatalkan" <?php if ($data_edit['status'] == 'Dibatalkan') echo 'selected'; ?>>Dibatalkan</option>
        </select>

        <button type="submit">Update</button>
        <a href="dashboard.php?page=pesanan" class="btn btn-cancel">Batal</a>
    </form>
<?php
}
?>

<?php
// mysqli_close($koneksi); // Hapus ini karena koneksi ditutup di dashboard.php
?>
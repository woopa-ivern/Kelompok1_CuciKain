<?php
// Catatan:
// - session_start() dan include 'koneksi.php' sudah dilakukan di dashboard.php
// - Tidak perlu tag <html>, <head>, <body> di sini karena ini adalah bagian dari halaman yang di-include
// - mysqli_close() tidak boleh ada di sini; hanya di dashboard.php setelah semua selesai.

$pesan = ''; // Variabel untuk menyimpan pesan notifikasi (akan ditampilkan di dashboard.php)

// --- Tambah Data (Create) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $nama_layanan = $_POST['nama_layanan'];
    $harga        = $_POST['harga'];

    // Hindari SQL Injection dengan prepared statements jika bisa, atau setidaknya mysqli_real_escape_string
    $nama_layanan = mysqli_real_escape_string($koneksi, $nama_layanan);
    $harga = mysqli_real_escape_string($koneksi, $harga);

    $query_tambah = "INSERT INTO layanan (nama_layanan, harga) VALUES ('$nama_layanan', '$harga')";

    if (mysqli_query($koneksi, $query_tambah)) {
        // Pesan sukses akan ditangani oleh redirect
        header("Location: dashboard.php?page=layanan&status=sukses_tambah"); // Tambahkan parameter status
        exit();
    } else {
        $pesan = "<div style='background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;'>Error saat menambahkan data: " . mysqli_error($koneksi) . "</div>";
    }
}

// --- Update Data (Update) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
    $id_layanan   = $_POST['id_layanan'];
    $nama_layanan = $_POST['nama_layanan'];
    $harga        = $_POST['harga'];

    // Hindari SQL Injection
    $id_layanan = mysqli_real_escape_string($koneksi, $id_layanan);
    $nama_layanan = mysqli_real_escape_string($koneksi, $nama_layanan);
    $harga = mysqli_real_escape_string($koneksi, $harga);

    $query_update = "UPDATE layanan SET nama_layanan='$nama_layanan', harga='$harga' WHERE id_layanan=$id_layanan";

    if (mysqli_query($koneksi, $query_update)) {
        // Pesan sukses akan ditangani oleh redirect
        header("Location: dashboard.php?page=layanan&status=sukses_update"); // Tambahkan parameter status
        exit();
    } else {
        $pesan = "<div style='background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;'>Error saat mengupdate data: " . mysqli_error($koneksi) . "</div>";
    }
}

// --- Hapus Data (Delete) ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_layanan = $_GET['id_layanan'];

    // Pastikan input aman dari SQL injection
    $id_layanan = mysqli_real_escape_string($koneksi, $id_layanan);

    // Pertama, cek apakah layanan ini digunakan di tabel detail_pesanan
    $query_check_dependency = "SELECT COUNT(*) AS total_related FROM detail_pesanan WHERE id_layanan = $id_layanan";
    $result_check = mysqli_query($koneksi, $query_check_dependency);
    $data_check = mysqli_fetch_assoc($result_check);

    if ($data_check['total_related'] > 0) {
        // Jika ada detail_pesanan yang terkait, berikan pesan error yang jelas
        $pesan = "<div style='background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;'>Gagal menghapus layanan: Layanan ini masih terhubung dengan " . $data_check['total_related'] . " detail pesanan. Harap hapus detail pesanan terkait terlebih dahulu.</div>";
    } else {
        // Jika tidak ada detail_pesanan yang terkait, lanjutkan dengan penghapusan
        $query_hapus = "DELETE FROM layanan WHERE id_layanan=$id_layanan";

        if (mysqli_query($koneksi, $query_hapus)) {
            // Pesan sukses akan ditangani oleh redirect
            header("Location: dashboard.php?page=layanan&status=sukses_hapus"); // Tambahkan parameter status
            exit();
        } else {
            // Ini akan menangkap error lain jika ada, bukan hanya foreign key
            $pesan = "<div style='background-color:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;'>Error saat menghapus layanan: " . mysqli_error($koneksi) . "</div>";
        }
    }
    // Jika ada pesan error dari pengecekan dependensi atau query_hapus gagal, pesan akan ditampilkan
    // Jika redirect berhasil, pesan ini tidak akan terlihat.
    // Jika ada masalah dengan header, pesan ini akan muncul di halaman.
    header("Location: dashboard.php?page=layanan"); // Redirect selalu dilakukan, bahkan jika ada pesan error
    exit(); // PENTING: selalu exit setelah header redirect
}

// --- Ambil Data untuk Ditampilkan (Read) ---
$query_read = "SELECT * FROM layanan ORDER BY id_layanan DESC";
$result_read = mysqli_query($koneksi, $query_read);

// --- Ambil Data untuk Form Edit ---
$data_edit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit') {
    $id_edit = $_GET['id_layanan'];
    // Pastikan input aman dari SQL injection
    $id_edit = mysqli_real_escape_string($koneksi, $id_edit);
    $query_edit = "SELECT * FROM layanan WHERE id_layanan=$id_edit";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $data_edit = mysqli_fetch_assoc($result_edit);
}

// =====================================================================================================
// Bagian Tampilan (HTML)
// =====================================================================================================
?>

<div class="container">
    <?php
    // Tampilkan pesan dari variabel $pesan (dari operasi CRUD yang gagal tanpa redirect)
    echo $pesan;

    // Tampilkan pesan sukses berdasarkan parameter 'status' di URL setelah redirect
    if (isset($_GET['status'])) {
        $status_pesan = '';
        if ($_GET['status'] == 'sukses_tambah') {
            $status_pesan = 'Data berhasil ditambahkan!';
        } elseif ($_GET['status'] == 'sukses_update') {
            $status_pesan = 'Data berhasil diupdate!';
        } elseif ($_GET['status'] == 'sukses_hapus') {
            $status_pesan = 'Data berhasil dihapus!';
        }
        if (!empty($status_pesan)) {
            echo "<div style='background-color:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;'>" . $status_pesan . "</div>";
        }
    }
    ?>

    <h2>Data Layanan</h2>
    <a href="dashboard.php?page=layanan&aksi=tambah_form" class="btn btn-add">Tambah Layanan Baru</a>
    <br><br>

    <table>
        <thead>
            <tr>
                <th>ID Layanan</th>
                <th>Nama Layanan</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result_read) > 0) { ?>
                <?php while($row = mysqli_fetch_assoc($result_read)) { ?>
                    <tr>
                        <td><?php echo $row['id_layanan']; ?></td>
                        <td><?php echo $row['nama_layanan']; ?></td>
                        <td><?php echo number_format($row['harga'], 2, ',', '.'); ?></td>
                        <td>
                            <a href="dashboard.php?page=layanan&aksi=edit&id_layanan=<?php echo $row['id_layanan']; ?>" class="btn btn-edit">Edit</a>
                            <a href="dashboard.php?page=layanan&aksi=hapus&id_layanan=<?php echo $row['id_layanan']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="4">Tidak ada data.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <hr>

    <?php
    // Tampilkan form tambah atau edit berdasarkan parameter 'aksi'
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambah_form') {
    ?>
        <h2>Tambah Layanan</h2>
        <form action="dashboard.php?page=layanan" method="POST"> <input type="hidden" name="aksi" value="tambah">

            <label for="nama_layanan">Nama Layanan:</label>
            <input type="text" id="nama_layanan" name="nama_layanan" required>

            <label for="harga">Harga:</label>
            <input type="number" step="0.01" id="harga" name="harga" required>
            <button type="submit">Tambah</button>
            <a href="dashboard.php?page=layanan" class="btn btn-cancel">Batal</a>
        </form>
    <?php
    } elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && $data_edit) {
    ?>
        <h2>Edit Layanan</h2>
        <form action="dashboard.php?page=layanan" method="POST"> <input type="hidden" name="aksi" value="update">
            <input type="hidden" name="id_layanan" value="<?php echo $data_edit['id_layanan']; ?>">

            <label for="nama_layanan">Nama Layanan:</label>
            <input type="text" id="nama_layanan" name="nama_layanan" value="<?php echo $data_edit['nama_layanan']; ?>" required>

            <label for="harga">Harga:</label>
            <input type="number" step="0.01" id="harga" name="harga" value="<?php echo $data_edit['harga']; ?>" required>

            <button type="submit">Update</button>
            <a href="dashboard.php?page=layanan" class="btn btn-cancel">Batal</a>
        </form>
    <?php
    }
    ?>
</div>
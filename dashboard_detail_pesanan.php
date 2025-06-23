<?php
// Bagian Koneksi Database
// KODE KONEKSI DATABASE DI SINI DIHAPUS karena sudah ada di dashboard.php
// Variabel $koneksi akan otomatis tersedia karena file ini di-include oleh dashboard.php

// =====================================================================================================
// Bagian Logika CRUD (PHP)
// =====================================================================================================

$pesan = ''; // Variabel untuk menyimpan pesan notifikasi

// --- Tambah Data (Create) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id_pesanan = $_POST['id_pesanan'];
    $id_layanan = $_POST['id_layanan'];
    $jumlah     = $_POST['jumlah'];
    $subtotal   = $_POST['subtotal']; // Subtotal bisa dihitung di frontend atau di sini

    $query_tambah = "INSERT INTO detail_pesanan (id_pesanan, id_layanan, jumlah, subtotal) VALUES ('$id_pesanan', '$id_layanan', '$jumlah', '$subtotal')";

    if (mysqli_query($koneksi, $query_tambah)) {
        // Alert PHP tidak akan efektif sebelum redirect, bisa dihapus atau diganti dengan session flash message
        // $pesan = "<script>alert('Data berhasil ditambahkan!');</script>";
        header("Location: dashboard.php?page=detail_pesanan"); // Redirect ke halaman detail_pesanan
        exit(); // PENTING: Hentikan eksekusi script setelah redirect
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Update Data (Update) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
    $id_detail  = $_POST['id_detail'];
    $id_pesanan = $_POST['id_pesanan'];
    $id_layanan = $_POST['id_layanan'];
    $jumlah     = $_POST['jumlah'];
    $subtotal   = $_POST['subtotal'];

    $query_update = "UPDATE detail_pesanan SET id_pesanan='$id_pesanan', id_layanan='$id_layanan', jumlah='$jumlah', subtotal='$subtotal' WHERE id_detail=$id_detail";

    if (mysqli_query($koneksi, $query_update)) {
        // $pesan = "<script>alert('Data berhasil diupdate!');</script>";
        header("Location: dashboard.php?page=detail_pesanan"); // Redirect ke halaman detail_pesanan
        exit(); // PENTING
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Hapus Data (Delete) ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id_detail'])) {
    $id_detail = $_GET['id_detail'];
    $query_hapus = "DELETE FROM detail_pesanan WHERE id_detail=$id_detail";

    if (mysqli_query($koneksi, $query_hapus)) {
        // $pesan = "<script>alert('Data berhasil dihapus!');</script>";
        header("Location: dashboard.php?page=detail_pesanan"); // Redirect ke halaman detail_pesanan
        exit(); // PENTING
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Ambil Data untuk Ditampilkan (Read) ---
// Join dengan tabel pesanan dan layanan untuk menampilkan nama yang lebih informatif
$query_read = "SELECT dp.*, p.tanggal AS tanggal_pesanan, l.nama_layanan AS nama_layanan
               FROM detail_pesanan dp
               JOIN pesanan p ON dp.id_pesanan = p.id_pesanan
               JOIN layanan l ON dp.id_layanan = l.id_layanan
               ORDER BY dp.id_detail DESC";
$result_read = mysqli_query($koneksi, $query_read);

// --- Ambil Data untuk Form Edit ---
$data_edit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && isset($_GET['id_detail'])) {
    $id_edit = $_GET['id_detail'];
    $query_edit = "SELECT * FROM detail_pesanan WHERE id_detail=$id_edit";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $data_edit = mysqli_fetch_assoc($result_edit);
}

// Ambil data pesanan dan layanan untuk dropdown di form
$query_pesanan = "SELECT id_pesanan, tanggal FROM pesanan ORDER BY tanggal DESC";
$result_pesanan = mysqli_query($koneksi, $query_pesanan);

$query_layanan = "SELECT id_layanan, nama_layanan FROM layanan ORDER BY nama_layanan ASC";
$result_layanan = mysqli_query($koneksi, $query_layanan);

// Tampilkan pesan jika ada error yang tidak menyebabkan redirect
echo $pesan;
?>

<h2>Manajemen Detail Pesanan</h2>

<a href="dashboard.php?page=detail_pesanan&aksi=tambah_form" class="btn btn-add">Tambah Detail Pesanan Baru</a>
<br><br>

<table>
    <thead>
        <tr>
            <th>ID Detail</th>
            <th>ID Pesanan</th>
            <th>Tanggal Pesanan</th>
            <th>Nama Layanan</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result_read) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result_read)) { ?>
                <tr>
                    <td><?php echo $row['id_detail']; ?></td>
                    <td><?php echo $row['id_pesanan']; ?></td>
                    <td><?php echo $row['tanggal_pesanan']; ?></td>
                    <td><?php echo $row['nama_layanan']; ?></td>
                    <td><?php echo $row['jumlah']; ?></td>
                    <td><?php echo number_format($row['subtotal'], 2, ',', '.'); ?></td>
                    <td>
                        <a href="dashboard.php?page=detail_pesanan&aksi=edit&id_detail=<?php echo $row['id_detail']; ?>" class="btn btn-edit">Edit</a>
                        <a href="dashboard.php?page=detail_pesanan&aksi=hapus&id_detail=<?php echo $row['id_detail']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
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
    <h2>Form Tambah Detail Pesanan</h2>
    <form action="dashboard.php?page=detail_pesanan" method="POST">
        <input type="hidden" name="aksi" value="tambah">

        <label for="id_pesanan">ID Pesanan:</label>
        <select id="id_pesanan" name="id_pesanan" required>
            <option value="">Pilih Pesanan</option>
            <?php while ($p = mysqli_fetch_assoc($result_pesanan)) { ?>
                <option value="<?php echo $p['id_pesanan']; ?>">ID: <?php echo $p['id_pesanan']; ?> (<?php echo $p['tanggal']; ?>)</option>
            <?php } ?>
        </select>

        <label for="id_layanan">Layanan:</label>
        <select id="id_layanan" name="id_layanan" required>
            <option value="">Pilih Layanan</option>
            <?php while ($l = mysqli_fetch_assoc($result_layanan)) { ?>
                <option value="<?php echo $l['id_layanan']; ?>"><?php echo $l['nama_layanan']; ?></option>
            <?php } ?>
        </select>

        <label for="jumlah">Jumlah (Kg/Pcs):</label>
        <input type="number" step="0.01" id="jumlah" name="jumlah" required>

        <label for="subtotal">Subtotal:</label>
        <input type="number" step="0.01" id="subtotal" name="subtotal" required>

        <button type="submit">Tambah</button>
        <a href="dashboard.php?page=detail_pesanan" class="btn btn-cancel">Batal</a>
    </form>
<?php
} elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && $data_edit) {
?>
    <h2>Form Edit Detail Pesanan</h2>
    <form action="dashboard.php?page=detail_pesanan" method="POST">
        <input type="hidden" name="aksi" value="update">
        <input type="hidden" name="id_detail" value="<?php echo $data_edit['id_detail']; ?>">

        <label for="id_pesanan">ID Pesanan:</label>
        <select id="id_pesanan" name="id_pesanan" required>
            <option value="">Pilih Pesanan</option>
            <?php
            // Reset pointer result_pesanan agar bisa digunakan lagi
            mysqli_data_seek($result_pesanan, 0);
            while ($p = mysqli_fetch_assoc($result_pesanan)) { ?>
                <option value="<?php echo $p['id_pesanan']; ?>" <?php if ($p['id_pesanan'] == $data_edit['id_pesanan']) echo 'selected'; ?>>ID: <?php echo $p['id_pesanan']; ?> (<?php echo $p['tanggal']; ?>)</option>
            <?php } ?>
        </select>

        <label for="id_layanan">Layanan:</label>
        <select id="id_layanan" name="id_layanan" required>
            <option value="">Pilih Layanan</option>
            <?php
            // Reset pointer result_layanan agar bisa digunakan lagi
            mysqli_data_seek($result_layanan, 0);
            while ($l = mysqli_fetch_assoc($result_layanan)) { ?>
                <option value="<?php echo $l['id_layanan']; ?>" <?php if ($l['id_layanan'] == $data_edit['id_layanan']) echo 'selected'; ?>><?php echo $l['nama_layanan']; ?></option>
            <?php } ?>
        </select>

        <label for="jumlah">Jumlah (Kg/Pcs):</label>
        <input type="number" step="0.01" id="jumlah" name="jumlah" value="<?php echo $data_edit['jumlah']; ?>" required>

        <label for="subtotal">Subtotal:</label>
        <input type="number" step="0.01" id="subtotal" name="subtotal" value="<?php echo $data_edit['subtotal']; ?>" required>

        <button type="submit">Update</button>
        <a href="dashboard.php?page=detail_pesanan" class="btn btn-cancel">Batal</a>
    </form>
<?php
}
?>

<?php
// mysqli_close($koneksi); // Hapus ini karena koneksi ditutup di dashboard.php
?>
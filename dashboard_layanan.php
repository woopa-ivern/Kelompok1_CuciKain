<?php
// Bagian Koneksi Database
include 'koneksi.php';

// =====================================================================================================
// Bagian Logika CRUD (PHP)
// =====================================================================================================

$pesan = ''; // Variabel untuk menyimpan pesan notifikasi

// --- Tambah Data (Create) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $nama_layanan = $_POST['nama_layanan'];
    $harga        = $_POST['harga'];

    $query_tambah = "INSERT INTO layanan (nama_layanan, harga) VALUES ('$nama_layanan', '$harga')";

    if (mysqli_query($koneksi, $query_tambah)) {
        $pesan = "<script>alert('Data berhasil ditambahkan!');</script>";
        header("Location: dashboard.php?page=layanan"); // Redirect untuk menghindari resubmission form
        exit();
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Update Data (Update) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
    $id_layanan   = $_POST['id_layanan'];
    $nama_layanan = $_POST['nama_layanan'];
    $harga        = $_POST['harga'];

    $query_update = "UPDATE layanan SET nama_layanan='$nama_layanan', harga='$harga' WHERE id_layanan=$id_layanan";

    if (mysqli_query($koneksi, $query_update)) {
        $pesan = "<script>alert('Data berhasil diupdate!');</script>";
        header("Location: dashboard.php?page=layanan");
        exit();
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Hapus Data (Delete) ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_layanan = $_GET['id_layanan'];
    $query_hapus = "DELETE FROM layanan WHERE id_layanan=$id_layanan";

    if (mysqli_query($koneksi, $query_hapus)) {
        $pesan = "<script>alert('Data berhasil dihapus!');</script>";
        header("Location: dashboard.php?page=layanan");
        exit();
    } else {
        $pesan = "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- Ambil Data untuk Ditampilkan (Read) ---
$query_read = "SELECT * FROM layanan ORDER BY id_layanan DESC";
$result_read = mysqli_query($koneksi, $query_read);

// --- Ambil Data untuk Form Edit ---
$data_edit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit') {
    $id_edit = $_GET['id_layanan'];
    $query_edit = "SELECT * FROM layanan WHERE id_layanan=$id_edit";
    $result_edit = mysqli_query($koneksi, $query_edit);
    $data_edit = mysqli_fetch_assoc($result_edit);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Layanan (Satu File)</title>
</head>
<body>
    <div class="container">
        <?php echo $pesan; // Tampilkan pesan notifikasi ?>

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
                            <td><?php echo number_format($row['harga'], 2, ',', '.'); ?></td> <td>
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
            <form action="dashboard_layanan.php" method="POST">
                <input type="hidden" name="aksi" value="tambah">

                <label for="nama_layanan">Nama Layanan:</label>
                <input type="text" id="nama_layanan" name="nama_layanan" required>

                <label for="harga">Harga:</label>
                <input type="number" step="0.01" id="harga" name="harga" required> <button type="submit">Tambah</button>
                <a href="dashboard_layanan.php" class="btn btn-cancel">Batal</a>
            </form>
        <?php
        } elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && $data_edit) {
        ?>
            <h2>Edit Layanan</h2>
            <form action="dashboard_layanan.php" method="POST">
                <input type="hidden" name="aksi" value="update">
                <input type="hidden" name="id_layanan" value="<?php echo $data_edit['id_layanan']; ?>">

                <label for="nama_layanan">Nama Layanan:</label>
                <input type="text" id="nama_layanan" name="nama_layanan" value="<?php echo $data_edit['nama_layanan']; ?>" required>

                <label for="harga">Harga:</label>
                <input type="number" step="0.01" id="harga" name="harga" value="<?php echo $data_edit['harga']; ?>" required>

                <button type="submit">Update</button>
                <a href="dashboard_layanan.php" class="btn btn-cancel">Batal</a>
            </form>
        <?php
        }
        ?>
    </div>
</body>
</html>
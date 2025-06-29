<?php
// dashboard_detail_pesanan.php

// Bagian Koneksi Database
include 'koneksi.php';

$pesan = ''; // Variabel untuk menyimpan pesan notifikasi

// =====================================================================================================
// Bagian Logika CRUD (PHP) - Ini adalah bagian dari file ini
// =====================================================================================================

// --- Tambah Data (Create) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $id_pesanan = isset($_POST['id_pesanan']) ? (int)$_POST['id_pesanan'] : 0;
    $id_layanan = $_POST['id_layanan'];
    $jumlah = $_POST['jumlah'];

    if ($id_pesanan === 0) {
        $pesan = "<script>alert('Error: ID Pesanan tidak valid. Pilih pesanan yang tersedia.');</script>";
    } else {
        // Ambil harga layanan dari database
        $query_get_harga = "SELECT harga FROM layanan WHERE id_layanan = ?";
        $stmt_get_harga = mysqli_prepare($koneksi, $query_get_harga);
        if ($stmt_get_harga === false) {
            $pesan = "<script>alert('Error preparing statement for price: " . mysqli_error($koneksi) . "');</script>";
        } else {
            mysqli_stmt_bind_param($stmt_get_harga, "i", $id_layanan);
            mysqli_stmt_execute($stmt_get_harga);
            $result_harga = mysqli_stmt_get_result($stmt_get_harga);
            $data_harga = mysqli_fetch_assoc($result_harga);
            mysqli_stmt_close($stmt_get_harga);

            if ($data_harga) {
                $harga_per_unit = (float)$data_harga['harga'];
                $subtotal_calculated = (float)$jumlah * $harga_per_unit;

                // Masukkan data dengan subtotal yang dihitung
                $query_tambah = "INSERT INTO detail_pesanan (id_pesanan, id_layanan, jumlah, subtotal) VALUES (?, ?, ?, ?)";
                $stmt_tambah = mysqli_prepare($koneksi, $query_tambah);
                if ($stmt_tambah === false) {
                    $pesan = "<script>alert('Error preparing statement for insert: " . mysqli_error($koneksi) . "');</script>";
                } else {
                    mysqli_stmt_bind_param($stmt_tambah, "iids", $id_pesanan, $id_layanan, $jumlah, $subtotal_calculated);
                    if (mysqli_stmt_execute($stmt_tambah)) {
                        header("Location: dashboard.php?page=detail_pesanan");
                        exit();
                    } else {
                        $pesan = "<script>alert('Error adding data: " . mysqli_error($koneksi) . "');</script>";
                    }
                    mysqli_stmt_close($stmt_tambah);
                }
            } else {
                $pesan = "<script>alert('Layanan tidak ditemukan.');</script>";
            }
        }
    }
}

// --- Update Data (Update) ---
if (isset($_POST['aksi']) && $_POST['aksi'] == 'update') {
    $id_detail = $_POST['id_detail'];
    $id_pesanan = $_POST['id_pesanan'];
    $id_layanan = $_POST['id_layanan'];
    $jumlah = $_POST['jumlah'];

    // Ambil harga layanan dari database
    $query_get_harga = "SELECT harga FROM layanan WHERE id_layanan = ?";
    $stmt_get_harga = mysqli_prepare($koneksi, $query_get_harga);
    if ($stmt_get_harga === false) {
        $pesan = "<script>alert('Error preparing statement for price: " . mysqli_error($koneksi) . "');</script>";
    } else {
        mysqli_stmt_bind_param($stmt_get_harga, "i", $id_layanan);
        mysqli_stmt_execute($stmt_get_harga);
        $result_harga = mysqli_stmt_get_result($stmt_get_harga);
        $data_harga = mysqli_fetch_assoc($result_harga);
        mysqli_stmt_close($stmt_get_harga);

        if ($data_harga) {
            $harga_per_unit = (float)$data_harga['harga'];
            $subtotal_calculated = (float)$jumlah * $harga_per_unit;

            // Update data dengan subtotal yang dihitung
            $query_update = "UPDATE detail_pesanan SET id_pesanan=?, id_layanan=?, jumlah=?, subtotal=? WHERE id_detail=?";
            $stmt_update = mysqli_prepare($koneksi, $query_update);
            if ($stmt_update === false) {
                $pesan = "<script>alert('Error preparing statement for update: " . mysqli_error($koneksi) . "');</script>";
            } else {
                mysqli_stmt_bind_param($stmt_update, "iidsi", $id_pesanan, $id_layanan, $jumlah, $subtotal_calculated, $id_detail);
                if (mysqli_stmt_execute($stmt_update)) {
                    header("Location: dashboard.php?page=detail_pesanan");
                    exit();
                } else {
                    $pesan = "<script>alert('Error updating data: " . mysqli_error($koneksi) . "');</script>";
                }
                mysqli_stmt_close($stmt_update);
            }
        } else {
            $pesan = "<script>alert('Layanan tidak ditemukan.');</script>";
        }
    }
}

// --- Hapus Data (Delete) ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id_detail'])) {
    $id_detail = $_GET['id_detail'];
    $query_hapus = "DELETE FROM detail_pesanan WHERE id_detail=?";
    $stmt_hapus = mysqli_prepare($koneksi, $query_hapus);
    if ($stmt_hapus === false) {
        $pesan = "<script>alert('Error preparing statement for delete: " . mysqli_error($koneksi) . "');</script>";
    } else {
        mysqli_stmt_bind_param($stmt_hapus, "i", $id_detail);
        if (mysqli_stmt_execute($stmt_hapus)) {
            header("Location: dashboard.php?page=detail_pesanan");
            exit();
        } else {
            $pesan = "<script>alert('Error deleting data: " . mysqli_error($koneksi) . "');</script>";
        }
        mysqli_stmt_close($stmt_hapus);
    }
}

// --- Ambil Data untuk Ditampilkan (Read) ---
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
    $query_edit = "SELECT * FROM detail_pesanan WHERE id_detail=?";
    $stmt_edit = mysqli_prepare($koneksi, $query_edit);
    if ($stmt_edit === false) {
        $pesan = "<script>alert('Error preparing statement for fetching edit data: " . mysqli_error($koneksi) . "');</script>";
    } else {
        mysqli_stmt_bind_param($stmt_edit, "i", $id_edit);
        mysqli_stmt_execute($stmt_edit);
        $result_edit = mysqli_stmt_get_result($stmt_edit);
        $data_edit = mysqli_fetch_assoc($result_edit);
        mysqli_stmt_close($stmt_edit);
    }
}

// Ambil data layanan untuk dropdown di form
$query_layanan = "SELECT id_layanan, nama_layanan, harga FROM layanan ORDER BY nama_layanan ASC";
$result_layanan = mysqli_query($koneksi, $query_layanan);

// Siapkan data layanan untuk JavaScript
$layanan_data = [];
if ($result_layanan) {
    mysqli_data_seek($result_layanan, 0);
    while ($l = mysqli_fetch_assoc($result_layanan)) {
        $layanan_data[$l['id_layanan']] = $l['harga'];
    }
    // Reset pointer lagi untuk form select
    mysqli_data_seek($result_layanan, 0);
}

// Query untuk mengambil ID Pesanan yang BELUM memiliki detail_pesanan (untuk form tambah)
$query_pesanan_tanpa_detail = "
    SELECT p.id_pesanan, p.tanggal
    FROM pesanan p
    LEFT JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
    WHERE dp.id_pesanan IS NULL
    ORDER BY p.tanggal DESC";
$result_pesanan_tanpa_detail = mysqli_query($koneksi, $query_pesanan_tanpa_detail);

// Ambil data pesanan untuk dropdown di form edit (semua pesanan)
$query_pesanan_all = "SELECT id_pesanan, tanggal FROM pesanan ORDER BY tanggal DESC";
$result_pesanan_all = mysqli_query($koneksi, $query_pesanan_all);

// Tampilkan pesan jika ada error yang tidak menyebabkan redirect
echo $pesan;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Detail Pesanan</title>
    <style>
        /* Contoh CSS sederhana untuk tampilan tabel dan form */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; margin-right: 5px; }
        .btn-add { background-color: #4CAF50; color: white; }
        .btn-edit { background-color: #008CBA; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-cancel { background-color: #888; color: white; }
        form { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 500px; margin: 20px auto; }
        form label { display: block; margin-bottom: 5px; font-weight: bold; }
        form input[type="text"],
        form input[type="number"],
        form select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        form button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        form button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        // Data harga layanan dari PHP ke JavaScript
        const layananPrices = <?php echo json_encode($layanan_data); ?>;

        function calculateSubtotal() {
            const layananSelect = document.getElementById('id_layanan');
            const jumlahInput = document.getElementById('jumlah');
            const subtotalInput = document.getElementById('subtotal');

            const selectedLayananId = layananSelect.value;
            const jumlah = parseFloat(jumlahInput.value);

            if (selectedLayananId && !isNaN(jumlah) && layananPrices[selectedLayananId]) {
                const harga = parseFloat(layananPrices[selectedLayananId]);
                const subtotal = jumlah * harga;
                subtotalInput.value = subtotal.toFixed(2);
            } else {
                subtotalInput.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const layananSelect = document.getElementById('id_layanan');
            const jumlahInput = document.getElementById('jumlah');

            if (layananSelect && jumlahInput) {
                layananSelect.addEventListener('change', calculateSubtotal);
                jumlahInput.addEventListener('input', calculateSubtotal);

                // Panggil sekali saat load form edit untuk mengisi subtotal awal
                // Cek jika ini halaman edit dan ada data_edit
                <?php if ($data_edit): ?>
                    calculateSubtotal();
                <?php endif; ?>
            }
        });
    </script>
</head>
<body>

<?php // include 'header.php'; // Header bisa diinclude di dashboard.php atau di sini ?>

<main>
    <h2>Manajemen Detail Pesanan</h2>

    <a href="dashboard.php?page=detail_pesanan&aksi=tambah_form" class="btn btn-add">Tambah Detail Pesanan Baru</a>
    <br><br>

    <table border="1">
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


    <?php
    // Tampilkan form tambah atau edit berdasarkan parameter 'aksi'
    if (isset($_GET['aksi']) && $_GET['aksi'] == 'tambah_form') {
    ?>
        <h2>Form Tambah Detail Pesanan Baru</h2>
        <form action="dashboard.php?page=detail_pesanan" method="POST">
            <input type="hidden" name="aksi" value="tambah">

            <label for="id_pesanan">ID Pesanan:</label>
            <select id="id_pesanan" name="id_pesanan" required>
                <option value="">Pilih Pesanan</option>
                <?php
                if (mysqli_num_rows($result_pesanan_tanpa_detail) > 0) {
                    while ($p = mysqli_fetch_assoc($result_pesanan_tanpa_detail)) { ?>
                        <option value="<?php echo $p['id_pesanan']; ?>">ID: <?php echo $p['id_pesanan']; ?> (<?php echo $p['tanggal']; ?>)</option>
                    <?php }
                } else { ?>
                    <option value="" disabled>Tidak ada pesanan yang belum memiliki detail.</option>
                <?php } ?>
            </select>

            <label for="id_layanan">Layanan:</label>
            <select id="id_layanan" name="id_layanan" required>
                <option value="">Pilih Layanan</option>
                <?php
                if ($result_layanan) {
                    mysqli_data_seek($result_layanan, 0); // Reset pointer
                    while ($l = mysqli_fetch_assoc($result_layanan)) { ?>
                        <option value="<?php echo $l['id_layanan']; ?>"><?php echo $l['nama_layanan']; ?></option>
                    <?php }
                }
                ?>
            </select>

            <label for="jumlah">Jumlah (Kg/Pcs):</label>
            <input type="number" step="0.01" id="jumlah" name="jumlah" required>

            <label for="subtotal">Subtotal:</label>
            <input type="number" step="0.01" id="subtotal" name="subtotal" readonly>

            <button type="submit">Tambah</button>
            <a href="dashboard.php?page=detail_pesanan" class="btn btn-cancel">Batal</a>
        </form>
    <?php
    } elseif (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && $data_edit) {
        // Form Edit
    ?>
        <h2>Form Edit Detail Pesanan</h2>
        <form action="dashboard.php?page=detail_pesanan" method="POST">
            <input type="hidden" name="aksi" value="update">
            <input type="hidden" name="id_detail" value="<?php echo $data_edit['id_detail']; ?>">

            <label for="id_pesanan">ID Pesanan:</label>
            <select id="id_pesanan" name="id_pesanan" required>
                <option value="">Pilih Pesanan</option>
                <?php
                if ($result_pesanan_all) {
                    mysqli_data_seek($result_pesanan_all, 0);
                    while ($p = mysqli_fetch_assoc($result_pesanan_all)) { ?>
                        <option value="<?php echo $p['id_pesanan']; ?>" <?php if ($p['id_pesanan'] == $data_edit['id_pesanan']) echo 'selected'; ?>>ID: <?php echo $p['id_pesanan']; ?> (<?php echo $p['tanggal']; ?>)</option>
                    <?php }
                }
                ?>
            </select>

            <label for="id_layanan">Layanan:</label>
            <select id="id_layanan" name="id_layanan" required>
                <option value="">Pilih Layanan</option>
                <?php
                if ($result_layanan) {
                    mysqli_data_seek($result_layanan, 0);
                    while ($l = mysqli_fetch_assoc($result_layanan)) { ?>
                        <option value="<?php echo $l['id_layanan']; ?>" <?php if ($l['id_layanan'] == $data_edit['id_layanan']) echo 'selected'; ?>><?php echo $l['nama_layanan']; ?></option>
                    <?php }
                }
                ?>
            </select>

            <label for="jumlah">Jumlah (Kg/Pcs):</label>
            <input type="number" step="0.01" id="jumlah" name="jumlah" value="<?php echo $data_edit['jumlah']; ?>" required>

            <label for="subtotal">Subtotal:</label>
            <input type="number" step="0.01" id="subtotal" name="subtotal" value="<?php echo $data_edit['subtotal']; ?>" readonly>

            <button type="submit">Update</button>
            <a href="dashboard.php?page=detail_pesanan" class="btn btn-cancel">Batal</a>
        </form>
    <?php
    }
    ?>
</main>

<?php // include 'footer.php'; // Footer bisa diinclude di dashboard.php atau di sini ?>
</body>
</html>
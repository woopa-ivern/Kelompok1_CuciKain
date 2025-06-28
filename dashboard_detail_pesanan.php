<?php
// Bagian Koneksi Database
include 'koneksi.php';
// =====================================================================================================
// Bagian Logika CRUD (PHP)
// =====================================================================================================

// INI ADALAH FUNGSI AGREGAT
$query_agregat_bulanan = "
    SELECT
        tahun_pesanan AS tahun,
        bulan_pesanan AS bulan,
        SUM(subtotal) AS total_bulanan,
        AVG(subtotal) AS rata_rata_subtotal_item_bulanan,
        MAX(subtotal) AS maks_subtotal_item_bulanan,
        MIN(subtotal) AS min_subtotal_item_bulanan,
        COUNT(id_detail) AS jumlah_item_detail_pesanan_bulanan
    FROM
        vw_laporan_detail_pesanan
    GROUP BY
        tahun_pesanan, bulan_pesanan
    ORDER BY
        tahun_pesanan ASC, bulan_pesanan ASC;
";

$result_agregat = mysqli_query($koneksi, $query_agregat_bulanan);

$data_laporan = [];
$total_keseluruhan = 0;
$jumlah_bulan_dengan_data = 0; // Untuk menghitung berapa banyak bulan yang ada datanya
$total_subtotal_untuk_avg_global = 0; // Untuk menghitung SUM dari semua subtotal (untuk AVG global)
$count_detail_global = 0; // Untuk menghitung COUNT dari semua id_detail (untuk COUNT global)

$max_subtotal_global = 0;
$min_subtotal_global = PHP_INT_MAX; // Nilai awal yang sangat besar

if (mysqli_num_rows($result_agregat) > 0) {
    while ($row = mysqli_fetch_assoc($result_agregat)) {
        $nama_bulan = DateTime::createFromFormat('!m', $row['bulan'])->format('F');
        $periode = $nama_bulan . " " . $row['tahun'];

        $data_laporan[] = [
            'periode'                             => $periode,
            'total_bulanan'                       => $row['total_bulanan'],
            'rata_rata_subtotal_item_bulanan'     => $row['rata_rata_subtotal_item_bulanan'],
            'maks_subtotal_item_bulanan'          => $row['maks_subtotal_item_bulanan'],
            'min_subtotal_item_bulanan'           => $row['min_subtotal_item_bulanan'],
            'jumlah_item_detail_pesanan_bulanan'  => $row['jumlah_item_detail_pesanan_bulanan']
        ];

        // Untuk agregat keseluruhan (global)
        $total_keseluruhan += $row['total_bulanan'];
        $jumlah_bulan_dengan_data++;
        $total_subtotal_untuk_avg_global += $row['total_bulanan']; // Mengumpulkan total subtotal dari setiap bulan untuk rata-rata global
        
        // Memperbarui MAX subtotal global
        if ($row['maks_subtotal_item_bulanan'] > $max_subtotal_global) {
            $max_subtotal_global = $row['maks_subtotal_item_bulanan'];
        }

        // Memperbarui MIN subtotal global
        if ($row['min_subtotal_item_bulanan'] < $min_subtotal_global) {
            $min_subtotal_global = $row['min_subtotal_item_bulanan'];
        }
        
        $count_detail_global += $row['jumlah_item_detail_pesanan_bulanan'];
    }

    // Hitung rata-rata keseluruhan setelah loop selesai
    // Rata-rata global dihitung dari total_keseluruhan dibagi jumlah bulan yang memiliki data
    $avg_keseluruhan = ($jumlah_bulan_dengan_data > 0) ? ($total_keseluruhan / $jumlah_bulan_dengan_data) : 0;

} else {
    // Handle case where no data is found for min_subtotal_global
    $min_subtotal_global = 0;
}

// Tutup koneksi database
?>

<h2>Laporan Agregat Penghasilan Pesanan</h2>

<?php if (!empty($data_laporan)) { ?>
    <h3>Ringkasan Agregat Keseluruhan:</h3>
    <table border="1">
        <tr>
            <th>Metrik</th>
            <th>Nilai</th>
        </tr>
        <tr>
            <td>Total Penghasilan Keseluruhan (SUM)</td>
            <td><strong>Rp. <?php echo number_format($total_keseluruhan, 2, ',', '.'); ?></strong></td>
        </tr>
        <tr>
            <td>Rata-rata Penghasilan per Bulan (AVG)</td>
            <td><strong>Rp. <?php echo number_format($avg_keseluruhan, 2, ',', '.'); ?></strong></td>
        </tr>
        <tr>
            <td>Subtotal Item Tertinggi (MAX)</td>
            <td><strong>Rp. <?php echo number_format($max_subtotal_global, 2, ',', '.'); ?></strong></td>
        </tr>
        <tr>
            <td>Subtotal Item Terendah (MIN)</td>
            <td><strong>Rp. <?php echo number_format($min_subtotal_global, 2, ',', '.'); ?></strong></td>
        </tr>
        <tr>
            <td>Total Jumlah Item Detail Pesanan (COUNT)</td>
            <td><strong><?php echo number_format($count_detail_global, 0, ',', '.'); ?></strong></td>
        </tr>
    </table>

    <h3>Detail Agregat per Bulan:</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Bulan/Tahun</th>
                <th>Total Penghasilan (SUM)</th>
                <th>Rata-rata Subtotal Item (AVG)</th>
                <th>Subtotal Item Tertinggi (MAX)</th>
                <th>Subtotal Item Terendah (MIN)</th>
                <th>Jumlah Item Detail Pesanan (COUNT)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_laporan as $data) { ?>
                <tr>
                    <td><?php echo $data['periode']; ?></td>
                    <td>Rp. <?php echo number_format($data['total_bulanan'], 2, ',', '.'); ?></td>
                    <td>Rp. <?php echo number_format($data['rata_rata_subtotal_item_bulanan'], 2, ',', '.'); ?></td>
                    <td>Rp. <?php echo number_format($data['maks_subtotal_item_bulanan'], 2, ',', '.'); ?></td>
                    <td>Rp. <?php echo number_format($data['min_subtotal_item_bulanan'], 2, ',', '.'); ?></td>
                    <td><?php echo number_format($data['jumlah_item_detail_pesanan_bulanan'], 0, ',', '.'); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>Tidak ada data penghasilan yang tersedia untuk laporan.</p>
<?php }
// FUNGSI AGREGAT


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
    <form action="dashboard_detail_pesanan" method="POST">
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
    <form action="dashboard_detail_pesanan.php" method="POST">
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
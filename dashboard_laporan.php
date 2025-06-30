<?php
// dashboard_laporan.php

// Bagian Koneksi Database
include 'koneksi.php';

// =====================================================================================================
// Bagian Logika Laporan (PHP) - Ini adalah bagian dari file ini
// =====================================================================================================
$data_laporan = [];
$total_keseluruhan = 0;
$avg_keseluruhan = 0;
$max_subtotal_global = 0;
$min_subtotal_global = 0;
$count_detail_global = 0;

// Query untuk laporan agregat per bulan
$query_laporan = "
    SELECT
        DATE_FORMAT(p.tanggal, '%Y-%m') AS periode,
        SUM(dp.subtotal) AS total_bulanan,
        AVG(dp.subtotal) AS rata_rata_subtotal_item_bulanan,
        MAX(dp.subtotal) AS maks_subtotal_item_bulanan,
        MIN(dp.subtotal) AS min_subtotal_item_bulanan,
        COUNT(dp.id_detail) AS jumlah_item_detail_pesanan_bulanan
    FROM
        detail_pesanan dp
    JOIN
        pesanan p ON dp.id_pesanan = p.id_pesanan
    GROUP BY
        periode
    ORDER BY
        periode DESC;
";
$result_laporan = mysqli_query($koneksi, $query_laporan);

if ($result_laporan) {
    while ($row = mysqli_fetch_assoc($result_laporan)) {
        $data_laporan[] = $row;
    }
} else {
    // Handle error jika query laporan gagal
    error_log("Error fetching laporan: " . mysqli_error($koneksi));
}

// Query untuk ringkasan agregat keseluruhan
$query_agregat_global = "
    SELECT
        SUM(dp.subtotal) AS total_keseluruhan_val,
        AVG(dp.subtotal) AS avg_keseluruhan_val,
        MAX(dp.subtotal) AS max_subtotal_global_val,
        MIN(dp.subtotal) AS min_subtotal_global_val,
        COUNT(dp.id_detail) AS count_detail_global_val
    FROM
        detail_pesanan dp;
";
$result_agregat_global = mysqli_query($koneksi, $query_agregat_global);

if ($result_agregat_global && mysqli_num_rows($result_agregat_global) > 0) {
    $agregat_data = mysqli_fetch_assoc($result_agregat_global);
    $total_keseluruhan = (float)$agregat_data['total_keseluruhan_val'];
    $avg_keseluruhan = (float)$agregat_data['avg_keseluruhan_val'];
    $max_subtotal_global = (float)$agregat_data['max_subtotal_global_val'];
    $min_subtotal_global = (float)$agregat_data['min_subtotal_global_val'];
    $count_detail_global = (int)$agregat_data['count_detail_global_val'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Agregat Penghasilan Pesanan</title>
    <style>
        /* Contoh CSS sederhana untuk tampilan tabel */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<?php // include 'header.php'; // Header bisa diinclude di dashboard.php atau di sini ?>

<main>
    <h2>Laporan Bulanan Laundry CuciKain</h2>

    <?php if (!empty($data_laporan)) { ?>
        <h3>Laporan Agregat Keseluruhan:</h3>
        <table border="1">
            <tr>
                <th>Keterangan</th>
                <th>Nilai</th>
            </tr>
            <tr>
                <td>Total Penghasilan Keseluruhan</td>
                <td><strong>Rp. <?php echo number_format($total_keseluruhan, 2, ',', '.'); ?></strong></td>
            </tr>
            <tr>
                <td>Rata-rata Penghasilan per Bulan</td>
                <td><strong>Rp. <?php echo number_format($avg_keseluruhan, 2, ',', '.'); ?></strong></td>
            </tr>
            <tr>
                <td>Subtotal Tertinggi</td>
                <td><strong>Rp. <?php echo number_format($max_subtotal_global, 2, ',', '.'); ?></strong></td>
            </tr>
            <tr>
                <td>Subtotal Terendah</td>
                <td><strong>Rp. <?php echo number_format($min_subtotal_global, 2, ',', '.'); ?></strong></td>
            </tr>
            <tr>
                <td>Total Jumlah Detail Pesanan</td>
                <td><strong><?php echo number_format($count_detail_global, 0, ',', '.'); ?></strong></td>
            </tr>
        </table>

        <h3>Detail Laporan per Bulan:</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Bulan/Tahun</th>
                    <th>Total Penghasilan</th>
                    <th>Rata-rata Subtotal</th>
                    <th>Subtotal Tertinggi</th>
                    <th>Subtotal Terendah</th>
                    <th>Jumlah Detail Pesanan</th>
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
    <?php } ?>
</main>

<?php // include 'footer.php'; // Footer bisa diinclude di dashboard.php atau di sini ?>
</body>
</html>
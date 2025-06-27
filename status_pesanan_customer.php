<?php
include 'koneksi.php'; // Include koneksi database

include 'dashboard_customer.php'; // Include the main dashboard file

// --- Query to get the customer's name and their order status ---
// Assuming these table structures:
// - `users` table: `id_user`, `nama_user` (or `nama_pelanggan`)
// - `pesanan` table: `id_pesanan`, `id_user` (FK to users), `status_pesanan`, `tanggal_pesanan`

$query_status_pesanan = "
    SELECT
        u.nama_user,
        p.id_pesanan,
        p.status_pesanan,
        p.tanggal_pesanan
    FROM
        users u
    JOIN
        pesanan p ON u.id_user = p.id_user
    WHERE
        u.id_user = ?
    ORDER BY
        p.tanggal_pesanan DESC, p.id_pesanan DESC";

// Prepare the statement for security (prevents SQL Injection)
$stmt = mysqli_prepare($koneksi, $query_status_pesanan);
if ($stmt === false) {
    echo "<p>Error preparing statement: " . mysqli_error($koneksi) . "</p>";
    exit();
}

// Bind the parameter (user ID)
mysqli_stmt_bind_param($stmt, "i", $current_user_id); // "i" for integer type

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result set
$result_status_pesanan = mysqli_stmt_get_result($stmt);

// Check for query execution errors
if (!$result_status_pesanan) {
    echo "<p>Terjadi kesalahan saat mengambil data pesanan: " . mysqli_error($koneksi) . "</p>";
}
?>

<h2>Status Pesanan Anda</h2>

<?php if (mysqli_num_rows($result_status_pesanan) > 0) { ?>
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Status Pesanan</th>
                <th>Tanggal Pesanan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_status_pesanan)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_pesanan']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_user']); ?></td>
                    <td><?php echo htmlspecialchars($row['status_pesanan']); ?></td>
                    <td><?php echo htmlspecialchars($row['tanggal_pesanan']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>Anda belum memiliki pesanan aktif.</p>
<?php } 

// Close the statement
mysqli_stmt_close($stmt);

// Note: mysqli_close($koneksi) is done in the main dashboard file
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/header_footer.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="TESTING/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="images/Logo_CuciKain.png" type="image/x-icon">
    <title>Dashboard CuciKain</title>
</head>

<body>
    <main class="site-main">
        <aside class="sidebar">
            <div class="dashboard-container">
                <div class="wrapper">
                    <div class="dash-logo">
                        <img src="images/Logo_CuciKain.png" alt="">
                    </div>
                    <nav class="dash-menu">
                        <ul class="dash-listing">
                            <li class="list-item">Informasi</li>
                            <li class="list-item">User</li>
                            <li class="list-item">Detail Pesanan</li>
                            <li class="list-item">Layanan</li>
                            <li class="list-item">Pesanan</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </aside>
        <section>
            <div class="dash-content">
                <div class="container">
                    <h2>Manajemen Pengguna</h2>

                    <?php
                    $servername = "localhost";
                    $username_db = "root";
                    $password_db = "";
                    $dbname = "cuci_kain";

                    $conn = mysqli_connect($servername, $username_db, $password_db, $dbname);

                    if (!$conn) {
                        die("Koneksi gagal: " . mysqli_connect_error());
                    }

                    $edit_id_user = '';
                    $edit_nama = '';
                    $edit_alamat = '';
                    $edit_no_hp = '';
                    $edit_username = '';
                    $edit_password = '';
                    $form_button_text = 'Tambah Pengguna Baru';
                    $form_action_type = 'add';

                    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
                        $id_to_edit = intval($_GET['id']);

                        $stmt_select_edit = mysqli_prepare($conn, "SELECT id_user, nama, alamat, no_hp, username FROM user WHERE id_user = ?");
                        mysqli_stmt_bind_param($stmt_select_edit, "i", $id_to_edit);
                        mysqli_stmt_execute($stmt_select_edit);
                        $result_edit = mysqli_stmt_get_result($stmt_select_edit);

                        if (mysqli_num_rows($result_edit) > 0) {
                            $row_edit = mysqli_fetch_assoc($result_edit);
                            $edit_id_user = $row_edit['id_user'];
                            $edit_nama = $row_edit['nama'];
                            $edit_alamat = $row_edit['alamat'];
                            $edit_no_hp = $row_edit['no_hp'];
                            $edit_username = $row_edit['username'];
                            $form_button_text = 'Update Pengguna';
                            $form_action_type = 'update';
                        } else {
                            echo "<p class='message warning'>Pengguna tidak ditemukan untuk diedit.</p>";
                        }
                        mysqli_stmt_close($stmt_select_edit);
                    }

                    if (isset($_POST['submit_button'])) {
                        $nama_form = trim($_POST['nama']);
                        $alamat_form = trim($_POST['alamat']);
                        $no_hp_form = trim($_POST['no_hp']);
                        $username_form = trim($_POST['username']);
                        $password_form = $_POST['password'];
                        $id_user_form = trim($_POST['id_user']);
                        $current_action_type = $_POST['form_action_type'];

                        if (empty($nama_form) || empty($username_form)) {
                            echo "<p class='message error'>Nama dan Username tidak boleh kosong!</p>";
                        } else {
                            $hashed_password = md5($password_form);

                            if ($current_action_type == 'update' && !empty($id_user_form)) {
                                $id_user_form = intval($id_user_form);

                                if (!empty($password_form)) {
                                    $sql_update = "UPDATE user SET nama = ?, alamat = ?, no_hp = ?, username = ?, password = ? WHERE id_user = ?";
                                    $stmt_update = mysqli_prepare($conn, $sql_update);
                                    mysqli_stmt_bind_param($stmt_update, "sssssi", $nama_form, $alamat_form, $no_hp_form, $username_form, $hashed_password, $id_user_form);
                                } else {
                                    $sql_update = "UPDATE user SET nama = ?, alamat = ?, no_hp = ?, username = ? WHERE id_user = ?";
                                    $stmt_update = mysqli_prepare($conn, $sql_update);
                                    mysqli_stmt_bind_param($stmt_update, "ssssi", $nama_form, $alamat_form, $no_hp_form, $username_form, $id_user_form);
                                }

                                if (mysqli_stmt_execute($stmt_update)) {
                                    echo "<p class='message success'>Data pengguna berhasil diperbarui!</p>";
                                    $edit_id_user = '';
                                    $edit_nama = '';
                                    $edit_alamat = '';
                                    $edit_no_hp = '';
                                    $edit_username = '';
                                    $edit_password = '';
                                    $form_button_text = 'Tambah Pengguna Baru';
                                    $form_action_type = 'add';
                                } else {
                                    echo "<p class='message error'>Error memperbarui data: " . mysqli_error($conn) . "</p>";
                                }
                                mysqli_stmt_close($stmt_update);
                            } else {
                                $sql_insert = "INSERT INTO user (nama, alamat, no_hp, username, password) VALUES (?, ?, ?, ?, ?)";
                                $stmt_insert = mysqli_prepare($conn, $sql_insert);
                                mysqli_stmt_bind_param($stmt_insert, "sssss", $nama_form, $alamat_form, $no_hp_form, $username_form, $hashed_password);

                                if (mysqli_stmt_execute($stmt_insert)) {
                                    echo "<p class='message success'>Pengguna baru berhasil ditambahkan!</p>";
                                    $edit_nama = '';
                                    $edit_alamat = '';
                                    $edit_no_hp = '';
                                    $edit_username = '';
                                    $edit_password = '';
                                } else {
                                    echo "<p class='message error'>Error menambahkan pengguna: " . mysqli_error($conn) . "</p>";
                                }
                                mysqli_stmt_close($stmt_insert);
                            }
                        }
                    }

                    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
                        $id_to_delete = intval($_GET['id']);

                        $sql_delete = "DELETE FROM user WHERE id_user = ?";
                        $stmt_delete = mysqli_prepare($conn, $sql_delete);
                        mysqli_stmt_bind_param($stmt_delete, "i", $id_to_delete);

                        if (mysqli_stmt_execute($stmt_delete)) {
                            echo "<p class='message success'>Pengguna berhasil dihapus!</p>";
                        } else {
                            echo "<p class='message error'>Error menghapus pengguna: " . mysqli_error($conn) . "</p>";
                        }
                        mysqli_stmt_close($stmt_delete);
                    }
                    ?>

                    <form action="" method="POST">
                        <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($edit_id_user); ?>">
                        <input type="hidden" name="form_action_type" value="<?php echo htmlspecialchars($form_action_type); ?>">

                        <label for="nama">Nama:</label>
                        <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($edit_nama); ?>" required>

                        <label for="alamat">Alamat:</label>
                        <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($edit_alamat); ?>">

                        <label for="no_hp">No. HP:</label>
                        <input type="text" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($edit_no_hp); ?>">

                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($edit_username); ?>" required>

                        <label for="password">Password <?php echo ($form_action_type == 'update') ? '(Isi untuk mengubah)' : ''; ?>:</label>
                        <input type="password" id="password" name="password" <?php echo ($form_action_type == 'add') ? 'required' : ''; ?>>

                        <button type="submit" name="submit_button"><?php echo htmlspecialchars($form_button_text); ?></button>
                    </form>

                    <h3>Daftar Pengguna</h3>
                    <?php
                    $sql_select_all = "SELECT id_user, nama, alamat, no_hp, username, password FROM user ORDER BY id_user DESC";
                    $result_all = mysqli_query($conn, $sql_select_all);

                    if (mysqli_num_rows($result_all) > 0) {
                        echo "<div class=table-wrap>";
                        echo "<table>";
                        echo "<thead><tr><th>ID</th><th>Nama</th><th>Alamat</th><th>No. HP</th><th>Username</th><th>Password (MD5)</th><th>Aksi</th></tr></thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_assoc($result_all)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id_user']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['no_hp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                            echo "<td>";
                            echo "<a href='?action=edit&id=" . htmlspecialchars($row['id_user']) . "' class='action-link'>Edit</a>";
                            echo "<a href='?action=delete&id=" . htmlspecialchars($row['id_user']) . "' class='action-link delete-link' onclick='return confirm(\"Apakah Anda yakin ingin menghapus pengguna ini?\");'>Hapus</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<p>Tidak ada pengguna ditemukan.</p>";
                    }

                    mysqli_close($conn);
                    ?>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
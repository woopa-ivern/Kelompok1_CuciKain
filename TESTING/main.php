<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Sistem Manajemen Mahasiswa Sederhana</title>
</head>
<body>
    <div class="container">
        <h2>Mahasiswa</h2>  

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dbmyusuf_1tib";

        $conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        $edit_ID = '';
        $edit_Nama = '';
        $edit_NIM = '';
        $edit_Kelas = '';
        $edit_Username = '';
        $edit_Password = '';
        $form_button_text = 'Tambah Mahasiswa';

        if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
            $id_to_edit = $_GET['id'];

            $sql_select_edit = "SELECT ID, Nama, NIM, Kelas, Username, Password FROM mahasiswa WHERE ID = " . $id_to_edit;
            $result_edit = mysqli_query($conn, $sql_select_edit);

            if (mysqli_num_rows($result_edit) > 0) {
                $row_edit = mysqli_fetch_assoc($result_edit);
                $edit_ID = $row_edit['ID'];
                $edit_Nama = $row_edit['Nama'];
                $edit_NIM = $row_edit['NIM'];
                $edit_Kelas = $row_edit['Kelas'];
                $edit_Username = $row_edit['Username'];
                $edit_Password = $row_edit['Password'];
                $form_button_text = 'Update Mahasiswa';
            } else {
                echo "<p class='message' style='color: orange;'>Mahasiswa tidak ditemukan untuk diedit.</p>";
            }
        }

        if (isset($_POST['submit_button'])) {
            $Nama_form = $_POST['Nama'];
            $NIM_form = $_POST['NIM'];
            $Kelas_form = $_POST['Kelas'];
            $Username_form = $_POST['Username'];
            $Password_form = md5($_POST['Password']);
            $ID_form = $_POST['mahasiswa_ID'];

            if (empty($Nama_form) || empty($NIM_form) || empty($Username_form) || empty($Password_form)) {
                echo "<p class='message' style='color: red;'>Nama, NIM, Username, dan Password tidak boleh kosong!</p>";
            } else {
                if (!empty($ID_form)) {
                    $sql_update = "UPDATE mahasiswa SET Nama = '" . $Nama_form . "', NIM = '" . $NIM_form . "', Kelas = '" . $Kelas_form . "', Username = '" . $Username_form . "', Password = '" . $Password_form . "' WHERE ID = " . $ID_form;
                    if (mysqli_query($conn, $sql_update)) {
                        echo "<p class='message' style='color: green;'>Data mahasiswa berhasil diperbarui!</p>";
                        $edit_ID = '';
                        $edit_Nama = '';
                        $edit_NIM = '';
                        $edit_Kelas = '';
                        $edit_Username = '';
                        $edit_Password = '';
                        $form_button_text = 'Tambah Mahasiswa';
                    } else {
                        echo "<p class='message' style='color: red;'>Error memperbarui data: " . mysqli_error($conn) . "</p>";
                    }
                } else {
                    $sql_insert = "INSERT INTO mahasiswa (Nama, NIM, Kelas, Username, Password) VALUES ('" . $Nama_form . "', '" . $NIM_form . "', '" . $Kelas_form . "', '" . $Username_form . "', '" . $Password_form . "')";
                    if (mysqli_query($conn, $sql_insert)) {
                        echo "<p class='message' style='color: green;'>Mahasiswa baru berhasil ditambahkan!</p>";
                        $edit_Nama = '';
                        $edit_NIM = '';
                        $edit_Kelas = '';
                        $edit_Username = '';
                        $edit_Password = '';
                    } else {
                        echo "<p class='message' style='color: red;'>Error menambahkan mahasiswa: " . mysqli_error($conn) . "</p>";
                    }
                }
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $id_to_delete = $_GET['id'];

            $sql_delete = "DELETE FROM mahasiswa WHERE ID = " . $id_to_delete;
            if (mysqli_query($conn, $sql_delete)) {
                echo "<p class='message' style='color: green;'>Mahasiswa berhasil dihapus!</p>";
            } else {
                echo "<p class='message' style='color: red;'>Error menghapus mahasiswa: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>

        <form action="" method="POST">
            <input type="hidden" name="mahasiswa_ID" value="<?php echo $edit_ID; ?>">

            <label for="Nama">Nama:</label>
            <input type="text" id="Nama" name="Nama" value="<?php echo $edit_Nama; ?>" required>

            <label for="NIM">NIM:</label>
            <input type="text" id="NIM" name="NIM" value="<?php echo $edit_NIM; ?>" required>

            <label for="Kelas">Kelas:</label>
            <input type="text" id="Kelas" name="Kelas" value="<?php echo $edit_Kelas; ?>">

            <label for="Username">Username:</label>
            <input type="text" id="Username" name="Username" value="<?php echo $edit_Username; ?>" required>

            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" value="<?php echo $edit_Password; ?>" required>

            <button type="submit" name="submit_button"><?php echo $form_button_text; ?></button>
        </form>

        <h3>Daftar Mahasiswa</h3>
        <?php
        $sql_select_all = "SELECT ID, Nama, NIM, Kelas, Username, Password FROM mahasiswa ORDER BY ID DESC";
        $result_all = mysqli_query($conn, $sql_select_all);

        if (mysqli_num_rows($result_all) > 0) {
            echo "<table>";
            echo "<thead><tr><th>ID</th><th>Nama</th><th>NIM</th><th>Kelas</th><th>Username</th><th>Password</th><th>Aksi</th></tr></thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_assoc($result_all)) {
                echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>" . $row['Nama'] . "</td>";
                echo "<td>" . $row['NIM'] . "</td>";
                echo "<td>" . $row['Kelas'] . "</td>";
                echo "<td>" . $row['Username'] . "</td>";
                echo "<td>" . $row['Password'] . "</td>";
                echo "<td>";
                echo "<a href='?action=edit&id=" . $row['ID'] . "' class='action-link'>Edit</a>";
                echo "<a href='?action=delete&id=" . $row['ID'] . "' class='action-link delete-link' onclick='return confirm(\"Apakah Anda yakin ingin menghapus mahasiswa ini?\");'>Hapus</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>Tidak ada mahasiswa ditemukan.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user      = $_POST['id_user'];
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $email    = $_POST['email'];
    $no_hp    = $_POST['no_hp'];
    $username = $_POST['username'];
    $role     = $_POST['role'];

    // Cek apakah password diisi atau tidak
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE user SET nama='$nama', alamat='$alamat', email='$email', no_hp='$no_hp', username='$username', password='$password', role='$role' WHERE id_user=$id_user";
    } else {
        $query = "UPDATE user SET nama='$nama', alamat='$alamat', email='$email', no_hp='$no_hp', username='$username', role='$role' WHERE id_user=$id_user";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
} else {
    header('Location: index.php');
}
?>
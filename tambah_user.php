<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $email    = $_POST['email'];
    $no_hp    = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $role     = $_POST['role'];

    $query = "INSERT INTO user (nama, alamat, email, no_hp, username, password, role) VALUES ('$nama', '$alamat', '$email', '$no_hp', '$username', '$password', '$role')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
}
?>
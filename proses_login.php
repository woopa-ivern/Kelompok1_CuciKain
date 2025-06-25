<?php
// process_login.php

// 1. Memulai Session
// Session harus dimulai di setiap halaman yang akan menggunakan atau memeriksa session.
session_start();

// 2. Meng-include file koneksi database
require_once 'koneksi.php'; // Pastikan path ini benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 3. Query ke database untuk mencari user berdasarkan username
    $query = "SELECT id_user, username, password, role FROM user WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // 4. Verifikasi Password
        // Gunakan password_verify() karena password disimpan dalam bentuk hash
        if (password_verify($password, $user['password'])) {
            // Password cocok, buat session
            $_SESSION['loggedin'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Simpan role di session

            // 5. Redirect berdasarkan Role
            if ($user['role'] == 'admin') {
                header("Location: dashboard.php"); // Redirect ke dashboard admin
            } else { // Jika role-nya 'user' atau role lainnya
                header("Location: dashboard_customer.php"); // Redirect ke dashboard customer
            }
            exit(); // Penting: Hentikan eksekusi script setelah redirect
        } else {
            // Password tidak cocok
            header("Location: login.php?error=Username atau password salah!");
            exit();
        }
    } else {
        // Username tidak ditemukan
        header("Location: login.php?error=Username atau password salahhhhhhhhhhh!");
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    // Jika akses langsung ke process_login.php tanpa POST
    header("Location: login.php");
    exit();
}

// Tutup koneksi database (opsional di sini, bisa juga di akhir script utama jika diperlukan)
mysqli_close($koneksi);
?>
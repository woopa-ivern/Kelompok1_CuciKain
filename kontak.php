<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/header_footer.css">
    <link rel="stylesheet" href="styles/kontak.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="images/Logo_CuciKain.png" type="image/x-icon">
    <title>CuciKain</title>
</head>

<body>
    <header class="site-header">
        <div class="navbar-header">
            <div class="container">
                <div class="site-branding">
                    <a href="index.php"><img src="images/Logo_CuciKain.png"></a>
                    <a href="index.php">CuciKain</a>
                </div>
                <nav class="site-navigation">
                    <div class="main-navbar">
                        <ul class="primary-menu">
                            <li class="menu-item"><a href="index.php">Beranda</a></li>
                            <li class="menu-item"><a href="tentang.php">Tentang Kami</a></li>
                            <li class="menu-item"><a href="paket.php">Paket</a></li>
                            <li class="menu-item"><a href="kontak.php">Kontak</a></li>
                            <li class="menu-item"><a href="login.php">Masuk</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <article>
            <section>
                <div class="contents content-5 container">
                    <div class="wrapper">
                        <form action="main.php" method="post">
                            <div class="login-container">
                                <h2>Hubungi Kami</h2>
                                <p>Ada pertanyaan atau ingin pesan layanan? Tim kami siap melayani.</p>
                            
                                <input type="text" placeholder="Nama Lengkap" name="nama" required>
                                <input type="email" placeholder="Email" name="email" required>
                                <input type="number" placeholder="Nomor Handphone" name="phone" required>
                            
                                <div class="paket">
                                  <a href="paket.php">Masih bingung?</a>
                                </div>
                            
                                <button class="login-btn">Login</button>
                            
                                <div class="galeri">
                                  Kunjungi galeri kami <a href="#">Disini</a>
                                </div>
                              </div>
                        </form>
                    </div>
                </div>
            </section>
        </article>
    </main>
    <footer class="site-footer">
        <div class="wrapper">
            <h3>Laundry <span>CuciKain</span> Indonesia</h3>
            <p>Laundry & Dry Clean Premium</p>
            <div class="icon-group">
                <a href="https://www.facebook.com/"><img src="images/facebook.png"></a>
                <a href="https://www.instagram.com/"><img src="images/instagram.png"></a>
                <a href="https://www.tiktok.com/"><img src="images/tiktok.png"></a>
            </div>
            <p>&copy; Copyright Laundry <span>CuciKain</span> Indonesia. All Rights Reserved</p>
        </div>
    </footer>
</body>

</html>
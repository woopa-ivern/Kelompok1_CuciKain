<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/kontak.css">
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
<?php include 'footer.php';?>
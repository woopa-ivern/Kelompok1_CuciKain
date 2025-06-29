<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/login.css">
<main>
    <article>
        <section>
            <div class="contents content-5 container">
                <div class="wrapper">
                    <form action="proses_login.php" method="post">
                        <div class="login-container">
                            <h2>Masuk</h2>
                            <br>
                            <input type="text" placeholder="Username" name="username" required>
                            <input type="password" placeholder="Password" name="password" required>

                            <div class="paket">
                                <span>Belum punya akun? </span><a href="daftar.php">Daftar</a>
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
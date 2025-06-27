<?php include 'header.php'; ?>
<link rel="stylesheet" href="styles/beranda.css">
<main class="site-main">
    <article>
        <section>
            <div class="contents content-1 container">
                <div class="wrapper">
                    <div class="left-content">
                        <div class="desc">
                            <h1>Layanan Laundry <span>Terbaik di</span> <strong>Pekanbaru, Riau</strong></h1>
                            <p>Selesai dalam sehari, tanpa antri panjang.
                                Kami jemput dan antar cucian langsung ke rumah Anda.
                                Cepat, praktis, dan bersih maksimal.</p>
                            <div class="btn-group">
                                <a href="paket.php" class="primary-btn">Lihat Paket</a>
                                <a href="tentang.php" class="secondary-btn">Tentang kami</a>
                            </div>
                        </div>
                    </div>
                    <div class="right-content">
                        <img src="images/image2.jpg">
                    </div>
                </div>
            </div>
            <div class="contents content-2 container">
                <div class="wrapper">
                    <div class="heading">
                        <h2>Kenapa Memilih Kami?</h2>
                        <p>Layanan laundry cepat, bersih, dan terpercaya dengan kualitas terbaik untuk Anda.</p>
                    </div>
                    <ul class="card-item">
                        <li class="listing-card">
                            <div class="inner-wrapper">
                                <img src="images/laundry.png">
                                <h3>Pengalaman</h3>
                                <p>Lebih dari 5 tahun melayani pelanggan
                                    dengan hasil cucian terbaik dan memuaskan.
                                    Profesional, teliti, dan terpercaya.
                                </p>
                            </div>
                        </li>
                        <li class="listing-card">
                            <div class="inner-wrapper">
                                <img src="images/tshirt.png">
                                <h3>Bersih</h3>
                                <p>Pakaian Anda dicuci hingga benar-benar bersih,
                                    wangi, dan rapi seperti baru.
                                    Kualitas kebersihan adalah prioritas kami.
                                </p>
                            </div>
                        </li>
                        <li class="listing-card">
                            <div class="inner-wrapper">
                                <img src="images/clock.png">
                                <h3>Cepat</h3>
                                <p>Layanan laundry express, selesai dalam sehari.
                                    Cocok untuk Anda yang punya banyak aktivitas.
                                    Cepat tanpa mengorbankan kualitas.
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="contents content-3"
                style="background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ),url('images/planetcare-23coWmkTNSg-unsplash.jpg');">
                <div class="wrapper">
                    <h2>Profesional & Modern</h2>
                    <p>Laundry modern dengan hasil maksimal.</p>
                    <p>Mesin canggih, hasil bersih, dan pelayanan terbaik untuk kenyamanan Anda.</p>
                    <a href="kontak.php">Hubungi Kami</a>
                </div>
            </div>
            <div class="contents content-4 container">
                <div class="wrapper">
                    <div class="heading">
                        <h2>Paket Laundry</h2>
                        <p>Beragam pilihan paket laundry praktis dan terjangkau, sesuai kebutuhan harian Anda.</p>
                    </div>
                    <ul class="card-item">
                        <li class="list-item">
                            <div class="inner-wrapper">
                                <img src="images/weighing-machine.png">
                                <h3>Paket Laundry Kiloan</h3>
                                <p> Pakaian dicuci, dikeringkan,
                                    dan disetrika, dihitung berdasarkan berat.
                                </p>
                                <p class="price">Rp 20.000,-</p>
                                <h4>Keterangan</h4>
                                <ul class="desc">
                                    <li class="list-desc">Reguler : 2 - 3 hari selesai, Cuci + Setrika</li>
                                    <li class="list-desc">Express : 1 hari selesai, lebih mahal sedikit</li>
                                    <li class="list-desc">Super Express : Selesai dalam 4–6 jam</li>
                                </ul>
                                <a href="kontak.php">Pesan Sekarang</a>
                            </div>
                        </li>
                        <li class="list-item">
                            <div class="inner-wrapper">
                                <img src="images/shirt.png">
                                <h3>Laundry Satuan</h3>
                                <p>Kami mencuci item seperti jas, selimut, dan jaket dengan
                                    hati-hati dan standar tinggi.
                                </p>
                                <p class="price">Rp 5.000,-</p>
                                <h4>Keterangan</h4>
                                <ul class="desc">
                                    <li class="list-desc">Untuk pakaian khusus: jas, kebaya, jaket, selimut, bed
                                        cover, boneka, dll.</li>
                                    <li class="list-desc">Harga dihitung per item, bukan per kilo.</li>
                                </ul>
                                <a href="kontak.php">Pesan Sekarang</a>
                            </div>
                        </li>
                        <li class="list-item">
                            <div class="inner-wrapper">
                                <img src="images/iron.png">
                                <h3>Paket Cuci Setrika</h3>
                                <p>Layanan lengkap dari cuci hingga setrika,
                                    menjamin pakaian tetap harum dan rapi.
                                </p>
                                <p class="price">Rp 20.000,-</p>
                                <h4>Keterangan</h4>
                                <ul class="desc">
                                    <li class="list-desc">Dicuci, dikeringkan, dan disetrika sampai rapi.</li>
                                    <li class="list-desc">Layanan ini paling umum dipakai.</li>
                                </ul>
                                <a href="kontak.php">Pesan Sekarang</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="contents content-5 container">
                <div class="wrapper">
                    <form action="index.php" method="post">
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

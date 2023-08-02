<!-- 
    Nama    : Faradilla Fairuz Alsha
    Kelas   : XI-RPL
-->
<?php
    require_once('data.php');
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Website Pribadi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
</head>
<body>
<div class="container">
    <!--Bagian Pembuka-->
    <div class="section-1 row-2">
        <div class="column-1">
            <center>
            <div class="photo-container">
                <div class="photo"></div>
                <p class="name"><?php echo $datadiri->outputNama() ?></p>
                <p class="subtitle">Siswi Kelas <?php echo $datadiri->outputKelas() ?> SMK Multi Karya</p>
            </div>
            </center>
        </div>
        <div class="column-1">
            <h1 class="title">Cita Cita</h1>
            <p class="desc">
                <?php echo $datadiri->outputCitacita() ?>
            </p>
            <div class="line"></div>
            <div class="biodata">
                <div class="field">Tanggal Lahir</div>
                <div>:</div>
                <div class="data"><?php echo $datadiri->outputTanggalLahir() ?></div>

                <div class="field">Nomor Telepon</div>
                <div>:</div>
                <div class="data"><?php echo $datadiri->outputNomorTelepon() ?></div>

                <div class="field">Alamat</div>
                <div>:</div>
                <div class="data"><?php echo $datadiri->outputAlamatSiswa() ?></div>

                <div class="field">Email</div>
                <div>:</div>
                <div class="data"><?php echo $datadiri->outputEmailSiswa() ?></div>

                <div class="field">Website</div>
                <div>:</div>
                <div class="data"><?php echo $datadiri->outputNamaWebsite() ?></div>
            </div>
        </div>
    </div>
    <!--Bagian Pembuka-->

    <!--Bagian Pengenalan-->
    <div class="section-2">
        <h1 class="text-center title-2">Tentang Saya</h1>
        <div class="row-2">
            <div class="column-2">
                <img src="assets/img/avatar1.png" width="450" alt="Avatar 1">
            </div>
            <div class="column-2">
                <p class="subtitle-2">Perkenalan Diri Pribadi</p>
                <p class="desc-2">
                    <?php echo $datadiri->outputDeskripsiPribadi1() ?>
                </p>
            </div>
            <div class="column-2">
                <p class="subtitle-2">Hobi dan Kesukaan</p>
                <p class="desc-2">
                    <?php echo $datadiri->outputDeskripsiPribadi2() ?>
                </p>
            </div>
            <div class="column-2">
                <img src="assets/img/avatar2.png" width="450" alt="Avatar 1">
            </div>
        </div>
    </div>
    <!--Bagian Pengenalan-->

    <!--Bagian Pendidikan-->
    <div class="section-3">
        <h1 class="text-center title-2">Pendidikan</h1>
        <div class="row-3 text-center">
            <div class="column-3">
                <p class="desc-tahun">Tahun <?php echo $datadiri->outputTahunSekolah1() ?></p>
                <p class="nama-sekolah"><?php echo $datadiri->outputNamaSekolah1() ?></p>
            </div>
            <div class="column-3">
                <p class="desc-tahun">Tahun <?php echo $datadiri->outputTahunSekolah2() ?></p>
                <p class="nama-sekolah"><?php echo $datadiri->outputNamaSekolah2() ?></p>
            </div>
            <div class="column-3">
                <p class="desc-tahun">Tahun <?php echo $datadiri->outputTahunSekolah3() ?></p>
                <p class="nama-sekolah"><?php echo $datadiri->outputNamaSekolah3() ?></p>
            </div>
        </div>
    </div>
    <!--Bagian Pendidikan-->

    <!--Bagian Hubungi Saya-->
    <div class="section-4">
        <h1 class="title-3 text-center">Hubungi Saya</h1>
        <div class="row-4">
            <div class="column-4a">
                <form action="mailto:<?php echo $datadiri->outputEmailSiswa() ?>?subject=Pesan" method="post">
                    <input type="text" autocomplete="off" placeholder="Nama" name="nama" required>
                    <input type="email" autocomplete="off" placeholder="Email" name="email" required>
                    <input type="text" autocomplete="off" placeholder="Nomor Telepon" name="nomortelepon" required>
            </div>
            <div class="column-4b">
                    <textarea name="pesan" placeholder="Pesan" cols="30"></textarea>
                    <button class="submit" type="submit" name="submit">Kirim</button>
                </form>
            </div>
        </div>
    </div>
    <!--Bagian Hubungi Saya-->

    <!--Bagian Footer-->
    <div class="footer">
        <div class="row-5">
            <div class="column-5">
                <center>
                <div class="icon-container">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <h2 class="title">Alamat</h2>
                <p class="data"><?php echo $datadiri->outputAlamatSiswa() ?></p>
                </center>
            </div>
            <div class="column-5">
                <center>
                <div class="icon-container">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <h2 class="title">Nomor Telepon</h2>
                <p class="data"><?php echo $datadiri->outputNomorTelepon() ?></p>
                </center>
            </div>
            <div class="column-5">
                <center>
                <div class="icon-container">
                    <i class="bi bi-envelope-fill"></i>
                </div>
                <h2 class="title">Email</h2>
                <p class="data"><?php echo $datadiri->outputEmailSiswa() ?></p>
                </center>
            </div>
        </div>
        <p class="footer-text text-center"><?php echo date('Y') ?> &copy; <?php echo $datadiri->outputNamaWebsite() ?>. made with &#10084; by <?php echo $datadiri->outputNama() ?></p>
    </div>
    <!--Bagian Footer-->
</div>
</body>
</html>
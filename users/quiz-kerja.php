<?php
    session_start();
    if (!isset($_SESSION['id_user'])) {
        // Arahkan ke halaman login jika belum login
        header("Location: ../index.php");
        exit();
    }
    if (isset($_SESSION['id_user'])) {
        // Cek level user
        $level = $_SESSION['level'];
        if ($level == "admin") {
            // Jika admin, arahkan ke halaman admin
            header("Location: ../admin/quiz.php");
            exit();
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Include file koneksi database
        require_once "../koneksi.php";
        // Ambil data form
        $id_quiz = $_POST['id_quiz'];
        $id_user = $_SESSION['id_user'];
        $jawaban_user = []; 
        $jumlah_benar = 0; 
        $nilai_quiz = 0;
        foreach ($_POST['jawaban'] as $index => $jwb) {
            array_push($jawaban_user, $_POST['jawaban'][$index]);
        }
        // Ambil jawaban yang benar
        $stmt = $conn->prepare("SELECT nama_quiz, jumlah_quiz, quiz FROM Quiz WHERE id_quiz = ?");
        $stmt->bind_param("s", $id_quiz);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        }
        //Tentukan nilai per quiz berdasarkan 100 / jumlah quiz
        $nilai_per_quiz = round(100 / $row['jumlah_quiz'], 1);
        //Bandingkan jawaban yang benar dengan jawaban user
        foreach (json_decode($row['quiz']) as $index => $kj) {
            if ($jawaban_user[$index] == $kj->jawaban) {
                //Jika sama, maka tambah jumlah benar +1 dan jumlahkan nilai quiz
                $jumlah_benar += 1; 
                $nilai_quiz += $nilai_per_quiz;
            }
        }
        //Bulatkan nilai quiz menjadi 100 jika benar semua
        if ($jumlah_benar == $row['jumlah_quiz']) { 
            $nilai_quiz = 100.0; 
        }
        // Simpan data hasil perhitungan ke db
        $stmt = $conn->prepare("INSERT INTO Hasil (id_quiz, id_user, jumlah_benar, nilai_quiz) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $id_quiz, $id_user, $jumlah_benar, $nilai_quiz);
        if ($stmt->execute()) {
            // Jika proses simpan data hasil berhasil, arahkan ke halaman daftar quiz
            header("Location: quiz-list.php?process=success&quiz_name=".$row['nama_quiz']."&quiz_score=".$nilai_quiz);
            exit();
        }
        $stmt->close();
        $conn->close();
    }
    if (!empty($_GET)) {
        // Include file koneksi database
        require_once "../koneksi.php";
        $id = $_GET['id'];
        // Ambil data quiz dari db
        $stmt = $conn->prepare("SELECT * FROM quiz WHERE id_quiz = ?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $count = $result->num_rows;
            if ($count == 0) {
                //Jika data id tidak ditemukan, kembalikan ke halaman quiz list
                header("Location: quiz-list.php");
                exit();
            }
        } else {
            // Jika error, beri tahu user
            header("Location: quiz-list.php?process=error&message=Terjadi kesalahan saat menampilkan quiz.");
            exit();
        }
    } else {
        //Jika tidak ada parameter get, kembalikan ke hahalaman quiz list
        header("Location: quiz-list.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Apps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
    crossorigin="anonymous">
    <style>
        .carousel-inner .carousel-item {
            transition: none !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-8 col-12">
                <div class="card rounded-5 border-0 shadow mb-3">
                    <form name="quiz-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <div id="quizCarousel" class="carousel slide" data-bs-touch="false" data-bs-interval="false" data-bs-ride="false" >
                            <div class="card-header d-flex justify-content-between border-bottom border-primary border-3 p-3 bg-white">
                                <div class="h4 my-auto">
                                    Quiz Apps
                                </div>
                                <div class="my-auto">
                                    <button class="btn btn-dark">
                                        Waktu <span name="quiz-timer" class="badge text-bg-light">02:00</span>
                                    </button>
                                    <button type="button" class="btn btn-primary" name="quiz-exit">Keluar</button>
                                </div>
                            </div>
                            <div class="card-body bg-white">
                                <?php while($data = mysqli_fetch_array($result)) {
                                    $dataQuiz = json_decode($data['quiz'], true);
                                ?>
                                <div class="carousel-inner">
                                    <input type="hidden" name="id_quiz" value="<?php echo $data['id_quiz']; ?>" required>
                                    <?php foreach ($dataQuiz as $key => $quiz) { 
                                        $number = $key + 1;
                                        ?>        
                                        <div class="carousel-item <?php echo ($number == 1) ? 'active' : ''; ?>">
                                            <h4 class="mb-3 text-center">
                                                Jawaban untuk Soal No <?php echo $number; ?>
                                            </h4>
                                            <div class="row row-cols-1 row-cols-md-2 jawaban-container" data-durasi="<?php echo $data['durasi_per_quiz']; ?>">
                                                <div class="col mb-3">
                                                    <input type="radio" class="btn-check" name="jawaban[<?php echo $number ?>]" 
                                                    id="jawaban[<?php echo $number ?>]-a" value="A" autocomplete="off">
                                                    <label class="btn btn-outline-primary w-100 text-start" for="jawaban[<?php echo $number ?>]-a">
                                                        A. <?php echo $quiz['jawaban-a'] ?>
                                                    </label>
                                                </div>
                                                <div class="col mb-3">
                                                    <input type="radio" class="btn-check" name="jawaban[<?php echo $number ?>]" 
                                                    id="jawaban[<?php echo $number ?>]-b" value="B" autocomplete="off">
                                                    <label class="btn btn-outline-primary w-100 text-start" for="jawaban[<?php echo $number ?>]-b">
                                                        B. <?php echo $quiz['jawaban-b'] ?>
                                                    </label>
                                                </div>
                                                <div class="col mb-3">
                                                    <input type="radio" class="btn-check" name="jawaban[<?php echo $number ?>]" 
                                                    id="jawaban[<?php echo $number ?>]-c" value="C" autocomplete="off">
                                                    <label class="btn btn-outline-primary w-100 text-start" for="jawaban[<?php echo $number ?>]-c">
                                                        C. <?php echo $quiz['jawaban-c'] ?>
                                                    </label>
                                                </div>
                                                <div class="col mb-3">
                                                    <input type="radio" class="btn-check" name="jawaban[<?php echo $number ?>]" 
                                                    id="jawaban[<?php echo $number ?>]-d" value="D" autocomplete="off">
                                                    <label class="btn btn-outline-primary w-100 text-start" for="jawaban[<?php echo $number ?>]-d">
                                                        D. <?php echo $quiz['jawaban-d'] ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="card-footer border-0 d-flex justify-content-start p-3 bg-white">
                                <div class="soal-count fw-bold my-auto">
                                    <span name="quiz-count">
                                        <?php echo 1 ." dari ".count($dataQuiz)." Quiz"; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" 
    crossorigin="anonymous"></script>
    <script>
        //Tampilkan konfirmasi saat ingin reload
        window.onbeforeunload = function(event){
            return confirm();
        };
        $(document).ready(function() {
            //Simpan elemen dan data ke variabel
            var carousel = $('#quizCarousel');
            var timerBadge = $('span[name="quiz-timer"]');
            var durasiPerQuiz = $('.jawaban-container').data('durasi') * 60;
            //Quiz Label control
            carousel.on('slide.bs.carousel', function(event) {
                var totalQuiz = $('.carousel-inner .carousel-item').length;
                var urutanQuizSekarang = $(event.relatedTarget).index();
                $('span[name="quiz-count"]').text(urutanQuizSekarang + ' dari ' + totalQuiz + ' Quiz');
            });
            // Konfirmasi jika ingin keluar dari halaman
            $('button[name="quiz-exit"]').on('click', function() {
                var confirmed = confirm("Anda yakin ingin keluar dari halaman quiz ?");
                if (confirmed) {
                    window.localStorage.clear();
                    window.onbeforeunload = null;
                    window.location.href = "quiz-list.php";
                } else {
                    return false;
                }
            });
            // Perbarui jawaban yang telah dijawab jika halaman di reload
            $('input[type="radio"][name^="jawaban"]').each(function() {
                const savedValue = localStorage.getItem($(this).attr('name'));
                if (savedValue === $(this).val()) {
                    $(this).prop('checked', true);
                }
            });
            $('input[type="radio"][name^="jawaban"]').on('change', function() {
                localStorage.setItem($(this).attr('name'), $(this).val());
            });
            // Ambil data durasi per quiz dari localstorage (jika ada) atau buat data localStorage baru (jika tidak ada)
            if (localStorage.getItem('durasiPerQuiz')) {
                durasiPerQuiz = parseInt(localStorage.getItem('durasiPerQuiz'));
            } else {
                localStorage.setItem('durasiPerQuiz', durasiPerQuiz.toString());
            }
            // Ambil data urutan quiz sekarang dari localstorage (jika ada) atau buat data localStorage baru (jika tidak ada)
            var quizSekarang = 0;
            if (localStorage.getItem('quizSekarang')) {
                quizSekarang = parseInt(localStorage.getItem('quizSekarang'));
            } else {
                localStorage.setItem('quizSekarang', quizSekarang.toString());
            }
            // Ambil data waktu mulai dari localstorage (jika ada) atau buat data localStorage baru (jika tidak ada)
            var waktuMulai = 0;
            if (localStorage.getItem('waktuMulai')) {
                waktuMulai = parseInt(localStorage.getItem('waktuMulai'));
            } else {
                // Simpan waktu mulai dalam detik
                waktuMulai = Math.floor(Date.now() / 1000); 
                localStorage.setItem('waktuMulai', waktuMulai.toString());
            }
            // Kalkukasikan waktu terlewat semenjak mulai
            var waktuTerlewat = Math.floor(Date.now() / 1000) - waktuMulai; 
            // Kalkukasi waktu tersisa
            var durasiTersisa = durasiPerQuiz - waktuTerlewat; 
            carousel.carousel(quizSekarang);
            aturDurasiTimer();

            function aturDurasiTimer() {
                timerBadge.text(formatWaktu(durasiTersisa));
                var timer = setInterval(function() {
                durasiTersisa--;
                timerBadge.text(formatWaktu(durasiTersisa));
                localStorage.setItem('durasiTersisa', durasiTersisa.toString());
                if (durasiTersisa <= 0) {
                    clearInterval(timer);
                    gantiQuiz();
                }
                }, 1000);

                function gantiQuiz() {
                    quizSekarang++;
                    if (quizSekarang === carousel.find('.carousel-item').length) {
                        $('.card').hide();
                        //Jika ada quiz yang tidak dijawab, set jawabannya menjadi null
                        $('input[type="radio"]').each(function() {
                            var radioName = $(this).attr('name');
                            if ($('input[type="radio"][name="' + radioName + '"]:checked').length === 0) {
                                $('input[type="radio"][name="' + radioName + '"]').val(null).attr('checked', true);
                            }
                        });
                        // Hapus localStorage sebelum form tersubmit
                        localStorage.clear();
                        window.onbeforeunload = null;
                        // Submit jawaban quiz
                        $('form[name="quiz-form"]').submit();
                    } else { // Perbarui urutan quiz dan lain lain jika belum sampai di ujung quiz
                        carousel.carousel(quizSekarang);
                        localStorage.setItem('quizSekarang', quizSekarang.toString());
                        waktuMulai = Math.floor(Date.now() / 1000); 
                        localStorage.setItem('waktuMulai', waktuMulai.toString());
                        durasiTersisa = durasiPerQuiz;
                        localStorage.setItem('durasiTersisa', durasiTersisa.toString());
                        aturDurasiTimer();
                    }
                }
            }
            // Function untuk Format waktu dalam format MM:SS
            function formatWaktu(detik) {
                var menit = Math.floor(detik / 60);
                var detikSisa = detik % 60;
                return tambahNolDepan(menit) + ':' + tambahNolDepan(detikSisa);
            }
            // Function untuk menambahkan angka 0 di depan jika angka kurang dari 10
            function tambahNolDepan(angka) {
                return angka < 10 ? '0' + angka : angka;
            }
        });
    </script>
</body>
</html>
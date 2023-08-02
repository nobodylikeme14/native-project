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
        if ($level == "user") {
            // Jika user, arahkan ke halaman user
            header("Location: ../users/quiz-list.php");
            exit();
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Include file koneksi database
        require_once "../koneksi.php";
        // Ambil data form
        $nama_quiz = $_POST['nama-quiz'];
        $durasi_per_quiz = $_POST['durasi-per-quiz'];
        $jumlah_quiz = $_POST['jumlah-quiz'];
        $quiz_quiz = [];
        //Simpan soal dalam array 
        foreach ($_POST['no'] as $index => $nomor) {
            array_push($quiz_quiz, [
                'no'         => $nomor,
                'quiz'       => $_POST['quiz'][$nomor],
                'jawaban-a'  => $_POST['jawaban-a'][$nomor],
                'jawaban-b'  => $_POST['jawaban-b'][$nomor],
                'jawaban-c'  => $_POST['jawaban-c'][$nomor],
                'jawaban-d'  => $_POST['jawaban-d'][$nomor],
                'jawaban'    => $_POST['jawaban'][$nomor]
            ]);
        }
        //Encode soal array ke json
        $quiz_quiz_encoded = json_encode($quiz_quiz);
        // Simpan data quiz ke db
        $stmt = $conn->prepare("INSERT INTO Quiz (nama_quiz, durasi_per_quiz, jumlah_quiz, quiz) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $nama_quiz, $durasi_per_quiz, $jumlah_quiz, $quiz_quiz_encoded);
        if ($stmt->execute()) {
            // Jika proses tambah quiz berhasil, arahkan ke halaman daftar quiz
            header("Location: quiz.php?process=success&message=Quiz berhasil disimpan.");
            exit();
        } else {
            // Jika error, beri tahu user
            $error = "Terjadi kesalahan dalam menyimpan quiz.";
        }
        $stmt->close();
        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Quiz Apps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
    crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-8 col-12">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="card border-0 shadow mb-3">
                        <div class="card-header d-flex justify-content-between border-bottom border-primary border-3 p-3 bg-white">
                            <div class="h4 my-auto">
                                Admin Quiz Apps
                            </div>
                            <div class="my-auto">
                                <a href="../logout.php" class="btn btn-primary" onclick="return confirm('Anda yakin ingin logout ?');">
                                    Logout
                                </a>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <a class="btn btn-primary mb-3" href="quiz.php">Kembali</a>
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger border-0 border-start border-danger border-3 rounded-0" role="alert">
                                    <?php echo $error ?>
                                </div>                                
                            <?php } ?>
                            <div class="mb-2">
                                <label for="nama-quiz" class="form-label">Nama Quiz</label>
                                <input type="text" class="form-control" placeholder="Masukkan Nama Quiz" id="nama-quiz" name="nama-quiz" required>
                            </div>
                            <div class="mb-2">
                                <label for="nama-quiz" class="form-label">Durasi per Quiz (Menit)</label>
                                <input type="number" class="form-control" placeholder="Masukkan Durasi" id="durasi-per-quiz" name="durasi-per-quiz" step="any" required>
                            </div>
                            <div class="mb-2">
                                <label for="jumlah-quiz" class="form-label">Jumlah Quiz</label>
                                <input type="number" class="form-control" placeholder="Masukkan Jumlah Quiz" id="jumlah-quiz" name="jumlah-quiz" min="1" required>
                            </div>
                            <div class="card quiz-card shadow-sm p-3 mb-2 d-none"></div>
                        </div>
                        <div class="card-footer bg-white border-0 mb-2">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" 
    crossorigin="anonymous"></script>
    <script>
        //On input, tampilkan kolom quiz sesuai input user
        $('input[name="jumlah-quiz"]').on('input', function() {
            var jumlahQuiz = parseInt($(this).val());
            if (jumlahQuiz > 0) {
                //Jika input > 0, tampilkan kolom quiz dan containernya
                $('.quiz-card').removeClass('d-none').empty();
                for (var i = 1; i <= jumlahQuiz; i++) {
                    const element = `
                        <div class="quiz-wrapper mb-3">
                            <input type="hidden" name="no[${i}]" value="${i}" required>
                            <div class="mb-2">
                                <label class="form-label">Quiz Nomor ${i}</label>
                                <textarea class="form-control" placeholder="Masukkan Quiz Nomor ${i}" name="quiz[${i}]" rows="3" required></textarea>
                            </div>
                            <div class="row row-cols-1 row-cols-md-2 mb-2">
                                <div class="col mb-1">
                                    <div class="input-group">
                                        <span class="input-group-text">A</span>
                                        <input type="text" class="form-control" placeholder="Masukkan Jawaban A dari Quiz ${i}" autocomplete="off" name="jawaban-a[${i}]" required>
                                    </div>
                                </div>
                                <div class="col mb-1">
                                    <div class="input-group">
                                        <span class="input-group-text">B</span>
                                        <input type="text" class="form-control" placeholder="Masukkan Jawaban B dari Quiz ${i}" autocomplete="off" name="jawaban-b[${i}]" required>
                                    </div>
                                </div>
                                <div class="col mb-1">
                                    <div class="input-group">
                                        <span class="input-group-text">C</span>
                                        <input type="text" class="form-control" placeholder="Masukkan Jawaban C dari Quiz ${i}" autocomplete="off" name="jawaban-c[${i}]" required>
                                    </div>
                                </div>
                                <div class="col mb-1">
                                    <div class="input-group">
                                        <span class="input-group-text">D</span>
                                        <input type="text" class="form-control" placeholder="Masukkan Jawaban D dari Quiz ${i}" autocomplete="off" name="jawaban-d[${i}]" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <select class="form-select" name="jawaban[${i}]" required>
                                    <option selected value disabled>Pilih Jawaban Quiz ${i}</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                        </div>
                    `;
                    $('.quiz-card').append(element);
                }
            } else {
                //Jika tidak, sembunyikan container kolom quiz
                $('.quiz-card').addClass('d-none');
            }
        });
    </script>
</body>
</html>
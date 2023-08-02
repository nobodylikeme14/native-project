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
                //Jika data id tidak ditemukan, kembalikan ke halaman quiz
                header("Location: quiz.php");
                exit();
            }
        } else {
            // Jika error, beri tahu user
            header("Location: quiz.php?process=error&message=Terjadi kesalahan saat menampilkan quiz.");
            exit();
        }
    } else {
        //Jika tidak ada parameter get, kembalikan ke halaman quiz
        header("Location: quiz.php");
        exit();
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
                    <div id="quizCarousel" class="carousel slide" data-bs-touch="false" data-bs-interval="false" data-bs-ride="false" >
                        <div class="card-header d-flex justify-content-between border-bottom border-primary border-3 p-3 bg-white">
                            <div class="h4 my-auto">
                                Admin Quiz Apps
                            </div>
                            <div class="my-auto">
                                <a href="quiz.php" class="btn btn-primary">Kembali</a>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <?php while($data = mysqli_fetch_array($result)) {
                                $dataQuiz = json_decode($data['quiz'], true);
                            ?>
                            <div class="carousel-inner">
                                <?php foreach ($dataQuiz as $key => $quiz) { 
                                    $number = $key + 1;
                                    ?>        
                                    <div class="carousel-item <?php echo ($number == 1) ? 'active' : ''; ?>">
                                        <h4 class="mb-3 text-center">
                                            <?php echo $number .". "; echo $quiz['quiz']; ?>
                                        </h4>
                                        <div class="row row-cols-1 row-cols-md-2 jawaban-container" data-jawaban="<?php echo $quiz['jawaban'] ?>">
                                            <div class="col mb-3">
                                                <div class="card border border-primary text-primary py-2 px-3" data-value="A">
                                                    A. <?php echo $quiz['jawaban-a'] ?>
                                                </div>
                                            </div>
                                            <div class="col mb-3">
                                                <div class="card border border-primary text-primary py-2 px-3" data-value="B">
                                                    B. <?php echo $quiz['jawaban-b'] ?>
                                                </div>
                                            </div>
                                            <div class="col mb-3">
                                                <div class="card border border-primary text-primary py-2 px-3" data-value="C">
                                                    C. <?php echo $quiz['jawaban-c'] ?>
                                                </div>
                                            </div>
                                            <div class="col mb-3">
                                                <div class="card border border-primary text-primary py-2 px-3" data-value="D">
                                                    D. <?php echo $quiz['jawaban-d'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success" name="show-answer">Lihat Jawaban</button>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="card-footer border-0 d-flex justify-content-between p-3 bg-white">
                            <div class="soal-count fw-bold my-auto">
                                <span name="quiz-count">
                                    <?php echo 1 ." dari ".count($dataQuiz)." Quiz"; ?>
                                </span>
                            </div>
                            <div class="">
                                <button type="button" class="btn btn-primary" data-bs-target="#quizCarousel" 
                                data-bs-slide="prev" name="previous-question" disabled>Sebelumnya</button>
                                <button type="button" class="btn btn-primary" data-bs-target="#quizCarousel" 
                                data-bs-slide="next" name="next-question">Selanjutnya</button>
                            </div>
                        </div>
                    </div>
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
        $('#quizCarousel').on('slide.bs.carousel', function(event) {
            //Quiz label control
            var totalSlideQuiz = $('.carousel-inner .carousel-item').length;
            var slideQuizNow = $(event.relatedTarget).index() + 1;
            $('span[name="quiz-count"]').text(slideQuizNow + ' dari ' + totalSlideQuiz + ' Quiz');
            //Navigator button control
            $('button[name="previous-question"]').prop('disabled', false);
            $('button[name="next-question"]').prop('disabled', false);
            if (slideQuizNow === 1) {
                $('button[name="previous-question"]').prop('disabled', true);
            } else if (slideQuizNow === totalSlideQuiz) {
                $('button[name="next-question"]').prop('disabled', true);
            }
        });
        //Show answer control
        $('button[name="show-answer"]').on('click', function() {
            var jawabanValue = $(this).closest('.carousel-item').find('.jawaban-container').data('jawaban');
            $(this).closest('.carousel-item').find('.card[data-value="'+jawabanValue+'"]').addClass('bg-primary text-white');
        });
    </script>
</body>
</html>
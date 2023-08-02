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
    $processError = isset($_GET['process']) && $_GET['process'] == 'error' && isset($_GET['message']);
    $processSuccess = isset($_GET['process']) && $_GET['process'] == 'success' && isset($_GET['quiz_name']) && isset($_GET['quiz_score']);
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
</head>
<body>
    <div class="container">
        <?php if ($processSuccess) { ?>
        <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body pt-5 pb-3 d-flex justify-content-center">
                        <div class="text-center">
                            <h4 class="mb-3">Skor Quiz</h4>
                            <?php if ($_GET['quiz_score'] >= 70 && $_GET['quiz_score'] <= 100) { ?>
                            <div class="btn btn-primary btn-lg px-5 mb-5">
                                <span class="h3"><?php echo $_GET['quiz_score']; ?></span>
                            </div>
                            <?php } elseif ($_GET['quiz_score'] >= 40 && $_GET['quiz_score'] <= 69) { ?>
                            <div class="btn btn-warning text-white btn-lg px-5 mb-5">
                                <span class="h3"><?php echo $_GET['quiz_score']; ?></span>
                            </div>
                            <?php } elseif ($_GET['quiz_score'] >= 0 && $_GET['quiz_score'] <= 39) { ?>
                            <div class="btn btn-danger btn-lg px-5 mb-5">
                                <span class="h3"><?php echo $_GET['quiz_score']; ?></span>
                            </div>
                            <?php } ?>
                            <h5>Terima kasih !</h5>
                            <p>Anda telah berhasil mengerjakan quiz <?php echo $_GET['quiz_name']; ?>.</p>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center bg-white border-0">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-8 col-12">
                <div class="card rounded-5 border-0 shadow mb-3">
                    <div class="card-header d-flex justify-content-between border-bottom border-primary border-3 p-3 bg-white">
                        <div class="h4 my-auto">
                            Quiz Apps
                        </div>
                        <div class="my-auto">
                            <a href="../logout.php" class="btn btn-primary" onclick="return confirm('Anda yakin ingin logout ?');">
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <h5 class="mb-3">Daftar Quiz</h5>
                        <?php if ($processError) { ?>
                            <div class="alert alert-danger border-0 border-start border-danger border-3 rounded-0" role="alert">
                                <?php echo $_GET['message']; ?>
                            </div>
                        <?php } ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Quiz</th>
                                        <th>Durasi Per Quiz</th>
                                        <th>Jumlah Quiz</th>
                                        <th>Nilai Quiz</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Include file koneksi database
                                    require_once "../koneksi.php";
                                    // Ambil data dari tabel quiz dan tabel hasil (Join)
                                    $stmt = $conn->prepare("SELECT hasil.id_hasil AS id_hasil, quiz.id_quiz, quiz.nama_quiz, quiz.durasi_per_quiz, quiz.jumlah_quiz, hasil.id_user, hasil.nilai_quiz
                                    FROM quiz LEFT JOIN hasil ON quiz.id_quiz = hasil.id_quiz AND hasil.id_user = ?");
                                    $stmt->bind_param("s", $_SESSION['id_user']);
                                    if ($stmt->execute()) {
                                        $result = $stmt->get_result();
                                    }
                                    $nomor = 1;
                                    if (mysqli_num_rows($result) > 0) {
                                        while($data = mysqli_fetch_array($result)) {      
                                    ?>
                                    <tr>
                                        <td><?php echo $nomor++; ?></td>
                                        <td><?php echo $data['nama_quiz']; ?></td>
                                        <td><?php echo $data['durasi_per_quiz']; ?> Menit</td>
                                        <td><?php echo $data['jumlah_quiz']; ?> Quiz</td>
                                        <td>
                                            <?php if ($data['id_hasil'] != null) { ?>
                                                <?php echo $data['nilai_quiz']; ?>
                                            <?php } else { ?>
                                                -
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($data['id_hasil'] == null) { ?>
                                                <a class="btn btn-sm btn-success" onclick="return confirm('Anda yakin ingin mengerjakan quiz ini ?');" href="quiz-kerja.php?id=<?php echo $data['id_quiz']; ?>">Ikuti</a>
                                            <?php } else { ?>
                                                -
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php }
                                    }else{ ?>
                                    <tr class="text-center"> 
                                        <td colspan="6">Tidak ada data quiz ditemukan</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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
        $(document).ready(function() {
            //Tampilkan modal skor quiz jika baru selesai mengerjakan quiz
            var param = '<?php echo $processSuccess ?>';
            if (param) {
                $('#successModal').modal('show');
            }
        });
    </script>
</body>
</html>
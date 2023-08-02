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
    $processSuccess = isset($_GET['process']) && $_GET['process'] == 'success' && isset($_GET['message']);
    $processError = isset($_GET['process']) && $_GET['process'] == 'error' && isset($_GET['message']);
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
                <div class="card rounded-5 border-0 shadow mb-3">
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
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="quiz.php">Daftar Quiz</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="hasil.php">Hasil Quiz</a>
                            </li>
                        </ul>
                        <a class="btn btn-primary mb-3" href="tambah-quiz.php">Tambah Quiz</a>
                        <?php if ($processSuccess) { ?>
                            <div class="alert alert-success border-0 border-start border-success border-3 rounded-0" role="alert">
                                <?php echo $_GET['message']; ?>
                            </div>
                        <?php } elseif ($processError) { ?>
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
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Include file koneksi database
                                    require_once "../koneksi.php";
                                    // Ambil data dari tabel quiz
                                    $query = "SELECT id_quiz, nama_quiz, durasi_per_quiz, jumlah_quiz FROM Quiz;";
                                    $result = $conn->query($query);
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
                                            <a class="btn btn-sm btn-danger" onclick="return confirm('Hapus quiz ini ?');" href="hapus-quiz.php?id=<?php echo $data['id_quiz']; ?>">Hapus</a>
                                            <a class="btn btn-sm btn-success" href="lihat-quiz.php?id=<?php echo $data['id_quiz']; ?>">Lihat</a>
                                        </td>
                                    </tr>
                                    <?php }
                                    }else{ ?>
                                    <tr class="text-center"> 
                                        <td colspan="5">Tidak ada data quiz ditemukan</td>
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
</body>
</html>
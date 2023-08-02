<?php
    session_start();
    if (isset($_SESSION['id_user'])) {
        //Cek level user
        $level = $_SESSION['level'];
        if ($level == "admin") {
            //Jika admin, arahkan ke halaman admin
            header("Location: admin/quiz.php");
            exit();
        } else if ($level == "user") {
            //Jika user, arahkan ke halaman user
            header("Location: users/quiz-list.php");
            exit();
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Include file koneksi database
        require_once "koneksi.php";
        // Ambil data login
        $username = $_POST['username'];
        $password = $_POST['password'];
        // Proses login dengan parameter
        $stmt = $conn->prepare("SELECT id_user, password, level FROM Users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id_user, $hashed_password, $level);
            $stmt->fetch();
            // Verifikasi password user
            if (password_verify($password, $hashed_password)) {
                $_SESSION['id_user'] = $id_user;
                $_SESSION['level'] = $level;
                if ($level == "admin") {
                    // Jika level Admin, arahkan ke halaman admin
                    header("Location: admin/quiz.php");
                } else {
                    // Jika level User, arahkan ke halaman user
                    header("Location: users/quiz-list.php");
                }
                exit();
            } else {
                $error = "Username atau Password anda salah.";
            }
        } else {
            $error = "Username atau Password anda salah.";
        }
    }
    $registrationSuccess = isset($_GET['registration']) && $_GET['registration'] == 'success' && isset($_GET['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
    crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-4 col-12">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="card rounded-5 border-0 shadow mb-3">
                        <div class="card-header border-bottom border-primary border-3 p-3 bg-white">
                            <div class="h4 mb-0">
                                Quiz Apps
                            </div>
                        </div>
                        <div class="card-body bg-white py-2">
                            <h5 class="mb-3">Login</h5>
                            <?php if ($registrationSuccess) { ?>
                                <div class="alert alert-success border-0 border-start border-success border-3 rounded-0" role="alert">
                                    <?php echo $_GET['message'] ?>
                                </div>
                            <?php } elseif (isset($error)) { ?>
                                <div class="alert alert-danger border-0 border-start border-danger border-3 rounded-0" role="alert">
                                    <?php echo $error ?>
                                </div>
                            <?php } ?>
                            <div class="mb-1">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" required>
                            </div>
                            <div class="mb-1">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>   
                            
                        </div>
                        <div class="card-footer d-flex flex-md-row flex-column justify-content-between border-0 p-3 bg-white">
                            <button type="submit" class="btn btn-primary mb-md-0 mb-3">Login</button>
                            <div class="my-md-auto">
                                Belum punya akun ? Register <a href="register.php">disini</a>.
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
    crossorigin="anonymous"></script>
</body>
</html>
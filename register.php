<?php
    session_start();
    if (isset($_SESSION['id_user'])) {
        // Arahkan user ke halaman utama jika sudah login
        header("Location: index.php");
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Include file koneksi database
        require_once "koneksi.php";
        // Ambil data dari form registrasi
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $level = "user";
        // Simpan data registrasi ke db
        $stmt = $conn->prepare("INSERT INTO Users (username, password, email, level) VALUES (?, ?, ?, ?)");
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $level);
        if ($stmt->execute()) {
            // Jika proses registrasi berhasil, arahkan ke halaman login
            header("Location: index.php?registration=success&message=Registrasi berhasil. Anda dapat Login sekarang.");
            exit();
        } else {
            // Jika error, beri tahu user
            $error = "Terjadi kesalahan dalam proses registrasi.";
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
    <title>Register</title>
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
                            <h5 class="mb-3">Register</h5>
                            <?php if (isset($error)) { ?>
                                <div class="alert alert-danger border-0 border-start border-danger border-3 rounded-0" role="alert">
                                    <?php echo $error ?>
                                </div>                                
                            <?php } ?>
                            <div class="mb-1">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="mb-1">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" required>
                            </div>
                            <div class="mb-1">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" required>
                            </div>   
                            <?php
                            ?>
                        </div>
                        <div class="card-footer d-flex flex-md-row flex-column justify-content-between border-0 p-3 bg-white">
                            <button type="submit" class="btn btn-primary mb-md-0 mb-3">Register</button>
                            <div class="my-md-auto">
                                Sudah punya akun ? Login <a href="index.php">disini</a>.
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
<?php
    // Info database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "db_quiz";
    // Buat koneksi
    $conn = new mysqli($servername, $username, $password);
    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    // Buat database jika belum ada
    $create_database_query = "CREATE DATABASE IF NOT EXISTS $database";
    if ($conn->query($create_database_query) === FALSE) {
        die("Pembuatan database gagal: " . $conn->error);
    }
    $conn->select_db($database);
    // Query pembuatan tabel users
    $table_users_query = "CREATE TABLE IF NOT EXISTS Users (
        id_user INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        level VARCHAR(50) NOT NULL
    )";
    // Jalankan query pembuatan tabel
    if ($conn->query($table_users_query) === FALSE) {
        die("Pembuatan tabel Users gagal: " . $conn->error);
    }
    // Query pembuatan tabel quiz
    $table_quiz_query = "CREATE TABLE IF NOT EXISTS Quiz (
        id_quiz INT(11) AUTO_INCREMENT PRIMARY KEY,
        nama_quiz VARCHAR(255) NOT NULL,
        durasi_per_quiz INT NOT NULL,
        jumlah_quiz INT NOT NULL,
        quiz TEXT NOT NULL
    )";    
    // Jalankan query pembuatan tabel quiz
    if ($conn->query($table_quiz_query) === FALSE) {
        die("Pembuatan tabel Quiz gagal: " . $conn->error);
    }
    // Query pembuatan tabel quiz
    $table_hasil_query = "CREATE TABLE IF NOT EXISTS Hasil (
        id_hasil INT(11) AUTO_INCREMENT PRIMARY KEY,
        id_quiz INT,
        id_user INT,
        jumlah_benar INT NOT NULL,
        nilai_quiz DOUBLE NOT NULL,
        FOREIGN KEY (id_quiz) REFERENCES Quiz(id_quiz) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (id_user) REFERENCES Users(id_user) ON DELETE CASCADE ON UPDATE CASCADE
    )";
    // Jalankan query pembuatan tabel quiz
    if ($conn->query($table_hasil_query) === FALSE) {
        die("Pembuatan tabel Hasil gagal: " . $conn->error);
    }
    // Tambahkan data admin ke tabel Users (jika belum ada)
    $admin_username = "admin";
    $cek_data_admin = "SELECT * FROM Users WHERE username='$admin_username'";
    $result = $conn->query($cek_data_admin);
    if ($result->num_rows == 0) {
        $admin_email = "admin@admin.com";
        $admin_password = password_hash("12345", PASSWORD_DEFAULT);
        $admin_level = "admin";
        $stmt = $conn->prepare("INSERT INTO Users (username, password, email, level) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $admin_username, $admin_password, $admin_email, $admin_level);
        $stmt->execute();
    }
?>
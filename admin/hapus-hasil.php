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
        // Ambil id
        $id = $_GET['id'];
        // Cari data berdasarkan id
        $selectStmt = $conn->prepare("SELECT * FROM Hasil WHERE id_hasil = ?");
        $selectStmt->bind_param("s", $id);
        $selectStmt->execute();
        $result = $selectStmt->get_result();
        $count = $result->num_rows;
        if ($count > 0) {
            // Jika data ditemukan, hapus data itu
            $deleteStmt = $conn->prepare("DELETE FROM Hasil WHERE id_hasil = ?");
            $deleteStmt->bind_param("s", $id);
            if ($deleteStmt->execute()) {
                // Jika proses penghapusan berhasil, arahkan ke halaman hasil.php
                header("Location: hasil.php?process=success&message=Hasil quiz berhasil dihapus.");
                exit();
            } else {
                // Jika error saat penghapusan, beri tahu user
                header("Location: hasil.php?process=error&message=Terjadi kesalahan saat menghapus hasil quiz.");
                exit();
            }
        } else {
            // Jika tidak ada data berdasarkan parameter id, arahkan ke halaman hasil.php
            header("Location: hasil.php");
            exit();
        }
        $selectStmt->close();
        $deleteStmt->close();
        $conn->close();
    }    
    header("Location: hasil.php");
?>
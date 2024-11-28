<?php
include "koneksi.php";

// Periksa apakah parameter id ada
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk delete data
    $sql = "DELETE FROM mahasiswa WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect kembali ke halaman utama setelah berhasil menghapus
        header("Location: /week11.php?message=success");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request! ID tidak ditemukan.";
    exit;
}
?>

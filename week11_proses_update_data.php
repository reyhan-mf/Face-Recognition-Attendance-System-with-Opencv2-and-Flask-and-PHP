<?php
include "koneksi.php";

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $conn->real_escape_string($_POST['nama']);
    $npm = $conn->real_escape_string($_POST['npm']);
    $nilai = $conn->real_escape_string($_POST['nilai']);

    // Query untuk update data
    $sql = "UPDATE mahasiswa SET nama='$nama', npm='$npm', nilai=$nilai WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diupdate.";
        header("Location: /week11.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request!";
    exit;
}
?>

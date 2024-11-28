<?php
include "week11_simpan.php";
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $npm = $_POST["npm"];
    $nilai = $_POST["nilai"];

    // Masukkan data ke tabel
    $sql = "INSERT INTO mahasiswa (nama, npm, nilai) VALUES ('$nama', '$npm', $nilai)";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil ditambahkan.";
        header("Location: week11.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>
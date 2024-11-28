<?php
include "koneksi.php";

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id = $id");
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data</title>
</head>

<body>
    <h2>Update Data Mahasiswa</h2>
    <form action="week11_proses_update_data.php" method="POST">
        <table>
            <tr>
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <td><label for="nama">Nama:</label></td>
            <td><input type="text" id="nama" name="nama" value="<?php echo $data['nama']; ?>"></td>
            </tr>
            <tr>
            <td><label for="npm">NPM:</label></td>
            <td><input type="text" id="npm" name="npm" value="<?php echo $data['npm']; ?>"></td>
            </tr>
            <tr>
            <td><label for="nilai">Nilai:</label></td>
            <td><input type="number" id="nilai" name="nilai" value="<?php echo $data['nilai']; ?>"></td>
            </tr>
        </table>

        <button type="submit">Simpan</button>
    </form>
</body>

</html>
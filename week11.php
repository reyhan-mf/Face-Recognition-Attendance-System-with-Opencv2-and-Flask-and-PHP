<?php
include "koneksi.php";

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <table border="1">
        <tr>
            <td>Nomor</td>
            <td>Nama</td>
            <td>NPM</td>
            <td>Nilai</td>
            <td>Aksi</td>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM mahasiswa");
        $nomor = 1;
        echo mysqli_num_rows($result);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
        ?>
                <tr>
                    <td><?php echo $nomor; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['npm']; ?></td>
                    <td><?php echo $row['nilai']; ?></td>
                    <td>
                        <a href="/week11_update.php?id=<?php echo $row['id']; ?>">
                            <button>Update</button>
                        </a>
                    </td>
                    <td>
                        <a href="/week11_delete_proses.php?id=<?php echo $row['id']; ?>">
                            <button>Delete</button>
                        </a>
                    </td>
                </tr>
        <?php
                $nomor++;
            }
        }
        ?>
    </table>
    <br>
    <a href="/week11_simpan.php"><button>Tambah Data</button></a>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="/week11_proses_tambah_data.php" method="POST">
        <table>
            <tr>
                <td>Masukkan Nama</td>
                <td>:</td>
                <td><input type="text" name="nama"></td>
            </tr>
            <tr>
                <td>Masukkan NPM</td>
                <td>:</td>
                <td><input type="text" name="npm"></td>
            </tr>
            <tr>
                <td>Masukkan Nilai</td>
                <td>:</td>
                <td><input type="text" name="nilai"></td>
            </tr>
            <td colspan="3">
                <button>Tambahkan</button>
            </td>
        </table>
    </form>

</body>

</html>
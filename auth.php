<?php
session_start();
require './koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role']; // admin/guru/siswa
        // echo ($_POST['kelas_id']);

        if ($_POST['jenis_kelamin'] == 'laki') {
            $_POST['jenis_kelamin'] = 'L';
            echo $_POST['jenis_kelamin'];
        } else {
            $_POST['jenis_kelamin'] = 'P';
        }
        // Cek ketersediaan email
        $check_sql = "SELECT * FROM USER WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param('s', $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "Email sudah terdaftar!";
            exit();
        }

        // Insert user baru
        $sql = "INSERT INTO USER (email, password_hash, role, is_active) VALUES (?, ?, ?, TRUE)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $email, $password, $role);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Insert detail sesuai role
            switch ($role) {
                case 'siswa':
                    if (isset($_FILES['student_picture']) && $_FILES['student_picture']['error'] === UPLOAD_ERR_OK) {
                        // Read the file contents
                        $image_data = file_get_contents($_FILES['student_picture']['tmp_name']);

                        // Prepare SQL statement
                        $siswa_sql = "INSERT INTO SISWA (user_id, nis, nama, jenis_kelamin, kelas_id, student_picture) VALUES (?, ?, ?, ?, ?, ?)";
                        $siswa_stmt = $conn->prepare($siswa_sql);

                        // Bind parameters
                        $siswa_stmt->bind_param(
                            'isssis',
                            $user_id,
                            $_POST['nis'],
                            $_POST['nama'],
                            $_POST['jenis_kelamin'],
                            $_POST['kelas_id'],
                            $image_data
                        );

                        // Execute the statement
                        $siswa_stmt->execute();
                    } else {
                        // Handle file upload error
                        // You might want to set a default image or log an error
                        echo "File upload failed";
                    }
                    break;

                case 'guru':
                    $subjects = isset($_POST['subjects']) ?
                        (is_array($_POST['subjects']) ? $_POST['subjects'] : [$_POST['subjects']]) :
                        [];
                    $guru_sql = "INSERT INTO GURU (user_id, nip, nama, mata_pelajaran) VALUES (?, ?, ?, ?)";
                    $guru_stmt = $conn->prepare($guru_sql);
                    $guru_stmt->bind_param(
                        'isss',
                        $user_id,
                        $_POST['nip'],
                        $_POST['nama'],
                        $subjects,
                    );
                    $guru_stmt->execute();
                    break;
            }

            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Redirect berdasarkan role
            switch ($role) {
                case 'admin':
                    // header('Location: dashboard_admin.php');
                    // header('Location: page_absensi_siswa.php');
                    break;
                case 'guru':
                    header('Location: dashboard_guru.php');
                    break;
                case 'siswa':
                    // header('Location: dashboard_murid.php');
                    header('Location: page_absensi_siswa.php');

                    break;
            }
            exit();
        } else {
            echo "Registrasi gagal.";
        }
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM USER WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $user['role'];

                // Redirect berdasarkan role
                switch ($user['role']) {
                    case 'admin':
                        header('Location: dashboard_admin.php');
                        break;
                    case 'guru':
                        header('Location: dashboard_guru.php');
                        break;
                    case 'siswa':
                        // header('Location: dashboard_murid.php');
                        header('Location: page_absensi_siswa.php');

                        break;
                }
                exit;
            } else {
                echo "Password salah!";
            }
        } else {
            echo "Email tidak ditemukan!";
        }
        $stmt->close();
    }
}

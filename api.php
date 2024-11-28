<?php
include("koneksi.php");
session_start();

// Flask API URL
$api_url = "http://127.0.0.1:5000/predict";

$userId = $_SESSION['user_id'];
$ambilJadwalId = 1;


$query_siswa = "SELECT s.nis, s.siswa_id ,s.nama, j.mata_pelajaran, a.waktu_absen, a.status
              FROM siswa s
              INNER JOIN kelas k ON s.kelas_id = k.kelas_id
              INNER JOIN jadwal_pelajaran j ON j.kelas_id = k.kelas_id
              LEFT JOIN absensi a ON a.siswa_id = s.siswa_id
              WHERE s.user_id = $userId AND j.jadwal_id = $ambilJadwalId;
              ";
$stmt_siswa = $conn->prepare($query_siswa);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();
$data_siswa = $result_siswa->fetch_all(MYSQLI_ASSOC);


// Processing logic
$response_message = []; // Variable to store the response message
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"])) {
    if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $temp_image_path = $_FILES["image"]["tmp_name"];
        $file_name = $_FILES["image"]["name"];
        $user_id = $_SESSION['user_id']; // Retrieve user_id from session data

        if (!$user_id) {
            $response_message = ["error" => "User ID is missing."];
        } else {
            // Initialize cURL
            $curl = curl_init();
            $cfile = new CURLFile($temp_image_path, mime_content_type($temp_image_path), $file_name);

            // Set cURL options
            curl_setopt_array($curl, [
                CURLOPT_URL => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    "image" => $cfile,
                    "user_id" => $user_id // Include user_id in the request
                ],
            ]);

            // Execute the cURL request
            $response = curl_exec($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($http_code === 200) {
                $response_data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Prepare the dynamic SQL INSERT query
                    $siswa_id = $data_siswa[0]['siswa_id']; // Assuming 'nis' is the student identifier

                    $jadwal_id = $ambilJadwalId;
                    $status = 'terabsen';
                    date_default_timezone_set('Asia/Jakarta');
                    $waktu_absen = date("Y-m-d H:i:s");
                    $pertemuan_id = null; // Set to null if not specified

                    // Use the correct database connection ($conn or $koneksi, whichever is the active connection)
                    $stmt = $conn->prepare("INSERT INTO `absensi` (`siswa_id`, `jadwal_id`, `pertemuan_id`, `waktu_absen`, `status`) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("iisss", $siswa_id, $jadwal_id, $pertemuan_id, $waktu_absen, $status);

                    if ($stmt->execute()) {
                        // $response_message = ["success" => "Attendance record inserted successfully."];
                        $response_message = $response_data;
                        $response_message['waktu_absen'] = $waktu_absen;
                        echo json_encode($response_message);
                        exit;
                    } else {
                        $response_message = ["error" => "Failed to insert attendance record: " . $stmt->error];
                    }

                    $stmt->close();
                } else {
                    $response_message = ["error" => "Invalid response data from Flask API."];
                }
            } else {
                $response_message = ["error" => "HTTP $http_code. Response: " . htmlspecialchars($response)];
            }
        }
    } else {
        $response_message = ["error" => "Error uploading image: " . $_FILES["image"]["error"]];
    }

    header('Content-Type: application/json');
    echo json_encode($response_message);
}

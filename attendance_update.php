<?php
include("koneksi.php");
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guru') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siswa_ids = $_POST['siswa_id']; // Array of student IDs
    $statuses = $_POST['status']; // Associative array of statuses with siswa_id as keys
    foreach ($siswa_ids as $siswa_id) {
        $status = isset($statuses[$siswa_id]) ? $statuses[$siswa_id] : 'tidak terabsen';
        $query = "UPDATE `absensi` SET `status` = ? WHERE `siswa_id` = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $siswa_id);
        $stmt->execute();
    }

    $stmt->close();


}

// Check if the form is submitted
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Get the arrays of student IDs and statuses
//     $siswa_id = $_POST['siswa_id'];
//     // $statuses = $_POST['statuses'];
//     echo $siswa_id;
//     // // Prepare the update statement
//     // $updateQuery = "UPDATE absensi SET status = ? WHERE siswa_id = ? AND jadwal_id = ? AND pertemuan_id = ?";

//     // // Assuming you have the jadwal_id and pertemuan_id available (you might need to pass them via hidden fields)
//     // // $jadwal_id = /* your logic to get jadwal_id */
//     // // $pertemuan_id = /* your logic to get pertemuan_id */

//     // $stmt = $conn->prepare($updateQuery);

//     // // Loop through each student ID and update their status
//     // for ($i = 0; $i < count($siswa_ids); $i++) {
//     //     $siswa_id = $siswa_ids[$i];
//     //     $status = $statuses[$i];

//     //     // Bind parameters and execute
//     //     $stmt->bind_param("siii", $status, $siswa_id, $jadwal_id, $pertemuan_id);
//     //     $stmt->execute();
//     // }

//     // // Close the statement
//     // $stmt->close();

//     // // Redirect back to the attendance page with a success message
//     // header('Location: attendance_page.php?message=Attendance updated successfully');
//     exit();
// }

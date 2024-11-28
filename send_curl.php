<?php
include("koneksi.php");
session_start();
$userId = $_SESSION['user_id'];
// $userId = 25;
$url = 'http://127.0.0.1:5000/predict'; // Replace with your API endpoint
   // Get file details
   $temp_image_path = $_FILES["image"]["tmp_name"];
   $file_name = $_FILES["image"]["name"];
   // Add this before sending the request

   // Send the image to the Flask API
   $ch = curl_init($url);
   $cfile = new CURLFile($temp_image_path, mime_content_type($temp_image_path), $file_name);

   curl_setopt_array($ch, [
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_POST => true,
       CURLOPT_POSTFIELDS => [
           "image" => $cfile,
           "user_id" => $userId, // Make sure this is exactly "user_id"
       ],
   ]);

   $response = curl_exec($ch);
   curl_close($ch);

   echo $response;
?>
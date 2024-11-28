<?php
// Flask API URL
$api_url = "http://127.0.0.1:5000/predict";

// Processing logic
$response_message = []; // Variable to store the response message
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"])) {
    if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $temp_image_path = $_FILES["image"]["tmp_name"];
        $file_name = $_FILES["image"]["name"];

        $curl = curl_init();
        $cfile = new CURLFile($temp_image_path, mime_content_type($temp_image_path), $file_name);
        curl_setopt_array($curl, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ["image" => $cfile],
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code === 200) {
            $response_data = json_decode($response, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($response_data['predictions'][0]['name'])) {
                    $response_message = $response_data;
                } else {
                    $response_message = ["error" => "Name not found in the response."];
                }
            } else {
                $response_message = ["error" => "Error decoding JSON response."];
            }
        } else {
            $response_message = ["error" => "HTTP $http_code . Response: " . htmlspecialchars($response)];
        }
    } else {
        $response_message = ["error" => "Error uploading image: " . $_FILES["image"]["error"]];
    }
    header('Content-Type: application/json');
    echo json_encode($response_message);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <style>
        .upload-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            margin-bottom: 2rem;
        }

        .upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-bottom: 1rem;
        }

        .upload-zone:hover {
            border-color: #94a3b8;
            background: #f8fafc;
        }

        .upload-zone.dragover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .upload-icon {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            color: #64748b;
        }

        .upload-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .upload-subtitle {
            color: #64748b;
            margin-bottom: 1rem;
        }

        #preview {
            margin-top: 1rem;
            text-align: center;
        }

        .status-box {
            position: relative;
            display: flex;
            justify-content: space-evenly;
        }

        #preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            display: none;
        }

        #upload-status {
            margin-top: 1rem;
            color: #1e293b;
            font-size: 1.1rem;
            display: none;
        }

        @media (max-width: 640px) {
            .upload-container {
                padding: 1rem;
            }

            .upload-zone {
                padding: 1rem;
            }

            .upload-icon {
                width: 48px;
                height: 48px;
            }

            .upload-title {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <h1>Upload an Image</h1>
    <!-- <form action="" method="POST" enctype="multipart/form-data">
        <label for="image">Choose an image to upload:</label>
        <input type="file" name="image" id="image" accept="image/*" required>
        <button type="submit">Upload and Send</button>
    </form> -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Image Upload</h4>
                </div>
                <div class="card-body">
                    <form id="upload-form" method="POST" enctype="multipart/form-data">
                        <div class="upload-container">
                            <div id="upload-zone" class="upload-zone"> <svg class="upload-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <h2 class="upload-title">Drag and drop your image here</h2>
                                <p class="upload-subtitle">or</p> <button type="button" class="btn btn-primary" onclick="document.getElementById('file-input').click()"> Browse Files </button> <input type="file" id="file-input" name="image" hidden accept="image/*" />
                            </div>
                            <div id="preview">
                                <div class="status-box"> <img id="preview-image" src="" alt="Preview" />
                                    <div id="upload-status-success" style="display: none;">
                                        <h4>Wajah Terdeteksi</h4>
                                        <p>Nama: <span id="name-field"></span></p>
                                        <p>Kelas: <span id="kelas-field"></span></p>
                                        <p>Jam Absen: <span id="jam-absen-field"></span></p>
                                        <h4>Absen Berhasil!</h4>
                                    </div>
                                    <div id="upload-status-failure" style="display: none;">
                                        <h4>Absen Gagal!</h4>
                                        <h4>Wajah tak Terdeteksi</h4>
                                        <h4>Silahkan Coba lagi</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3"> <button type="submit" class="btn btn-success">Upload and Predict</button> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

<script>
    const uploadZone = document.getElementById("upload-zone");
    const fileInput = document.getElementById("file-input");
    const previewImage = document.getElementById("preview-image");
    const uploadStatusSuccess = document.getElementById("upload-status-success");
    const uploadStatusFailure = document.getElementById("upload-status-failure");
    const uploadForm = document.getElementById("upload-form");

    // Drag and drop handlers
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
        uploadZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    ["dragenter", "dragover"].forEach((eventName) => {
        uploadZone.addEventListener(eventName, highlight, false);
    });

    ["dragleave", "drop"].forEach((eventName) => {
        uploadZone.addEventListener(eventName, unhighlight, false);
    });

    uploadZone.addEventListener("drop", handleDrop, false);
    fileInput.addEventListener("change", handleFiles, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        uploadZone.classList.add("dragover");
    }

    function unhighlight(e) {
        uploadZone.classList.remove("dragover");
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles({
            target: {
                files: files
            }
        });
    }

    function handleFiles(e) {
        const files = e.target.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                swal("Error", "Please upload an image file", "error");
            }
        }
    }

    // Handle form submission to use AJAX
    uploadForm.addEventListener("submit", function(e) {
        e.preventDefault(); // Prevent the default form submission behavior

        const formData = new FormData(uploadForm);

        fetch("api.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Check for errors
                if (data.error) {
                    console.error(data.error);
                    uploadStatusFailure.style.display = "block";
                    uploadStatusSuccess.style.display = "none";
                    return;
                }

                // Show the upload-status div
                uploadStatusSuccess.style.display = "block";
                uploadStatusFailure.style.display = "none";

                // Update the div with response data
                document.getElementById("name-field").innerText = data.predictions[0].name;
                document.getElementById("kelas-field").innerText = "Your class here"; // Replace with your actual data
                document.getElementById("jam-absen-field").innerText = "Your time here"; // Replace with your actual data
            })
            .catch(error => {
                console.error("Error:", error);
                uploadStatusFailure.style.display = "block";
                uploadStatusSuccess.style.display = "none";
            });
    });
</script>



</html>
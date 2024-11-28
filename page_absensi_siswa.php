<?php
session_start();
// Flask API URL
$api_url = "http://127.0.0.1:5000/predict";
$userId = $_SESSION['user_id'];
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: index.php');
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Kaiadmin - Bootstrap 5 Admin Dashboard</title>
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />
    <link
        rel="icon"
        href="assets/img/kaiadmin/favicon.ico"
        type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"],
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- <script src="/assets/js/reyhan.js"></script> -->

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="assets/css/reyhan.css" />
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
            align-items: center;
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

        @media (max-width: 550px) {
            .status-box {
                display: grid;
                align-items: center;
            }

            div#upload-status-success {
                margin-top: 2em;
            }
        }

        .custom-swal-spinner .custom-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .custom-swal-btn {
            transition: all 0.3s ease;
            background-color: #007bff !important;
            color: white !important;
            padding: 10px 20px !important;
            border-radius: 5px !important;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .custom-swal-btn:hover {
            background-color: #0056b3 !important;
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .custom-swal-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.5);
        }
    </style>
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="index.html" class="logo">
                        <img
                            src="assets/img/kaiadmin/logo_light.svg"
                            alt="navbar brand"
                            class="navbar-brand"
                            height="20" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                </div>
                <!-- End Logo Header -->
            </div>
            <div
                class="scroll-wrapper sidebar-wrapper scrollbar scrollbar-inner"
                style="position: relative">
                <div
                    class="sidebar-wrapper scrollbar scrollbar-inner scroll-content scroll-scrolly_visible"
                    style="
              height: auto;
              margin-bottom: 0px;
              margin-right: 0px;
              max-height: 616px;
            ">
                    <div class="sidebar-content">
                        <ul class="nav nav-secondary">
                            <li class="nav-item">
                                <a href="#dashboard" class="collapsed" aria-expanded="false">
                                    <i class="fas fa-home"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-section">
                                <span class="sidebar-mini-icon">
                                    <i class="fa fa-ellipsis-h"></i>
                                </span>
                                <h4 class="text-section">Components</h4>
                            </li>

                            <li class="nav-item">
                                <a href="profile.php">
                                    <i class="fas fa-user"></i>
                                    <p>Profile</p>
                                    <s></s>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="./logout.php">
                                    <i class="fas fa-power-off"></i>
                                    <p>Log Out</p>
                                    <s></s>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <!-- <img
                  src="assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                /> -->
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav
                    class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <nav
                            class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input
                                    type="text"
                                    placeholder="Search ..."
                                    class="form-control" />
                            </div>
                        </nav>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div
                        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <!-- <div>
              <h3 class="fw-bold mb-3">Halo, Pak <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
            </div> -->
                        <div class="page-header">
                            <h4 class="page-title">Dashboard</h4>
                            <ul class="breadcrumbs">
                                <li class="nav-home">
                                    <a href="#">
                                        <i class="icon-home"></i>
                                    </a>
                                </li>
                                <li class="separator">
                                    <i class="icon-arrow-right"></i>
                                </li>
                                <li class="nav-item">
                                    <a href="#">Pages</a>
                                </li>
                                <li class="separator">
                                    <i class="icon-arrow-right"></i>
                                </li>
                                <li class="nav-item">
                                    <a href="#">Starter Page</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Image Upload</h4>
                                </div>
                                <div class="card-body">
                                    <form id="upload-form" method="POST" enctype="multipart/form-data">

                                        <input style="display: none;" type="text" class="form-control" id="user_id" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">
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
                                                        <!-- <p>Kelas: <span id="kelas-field"></span></p> -->
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


                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <nav class="pull-left">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="http://www.themekita.com">
                                    ThemeKita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Help </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Licenses </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright">
                        2024, made with <i class="fa fa-heart heart text-danger"></i> by
                        <a href="http://www.themekita.com">ThemeKita</a>
                    </div>
                    <div>
                        Distributed by
                        <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
                    </div>
                </div>
            </footer>
        </div>

        <!-- Custom template | don't include it in your project! -->

        <!-- End Custom template -->
    </div>

    <script>
        const uploadZone = document.getElementById("upload-zone");
        const fileInput = document.getElementById("file-input");
        const previewImage = document.getElementById("preview-image");
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

            // Show loading sweet alert
            swal({
                title: "Processing...",
                content: {
                    element: "div",
                    attributes: {
                        innerHTML: `
                <p>Please wait while we verify your attendance</p>
                <div class="text-center mt-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `
                    }
                },
                icon: "info",
                buttons: false,
                closeOnClickOutside: false,
                closeOnEsc: false
            });

            fetch("api.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Check for errors
                    if (data.error || data.predictions[0].name === "Unknown") {
                        console.error(data.error);

                        // Show error sweet alert
                        swal({
                            title: "Attendance Failed",
                            text: "Face not detected. Please try again.",
                            icon: "error",
                            buttons: {
                                confirm: {
                                    text: "Close",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-primary custom-swal-btn",
                                    closeModal: true
                                }
                            }
                        }).then(() => {
                            // Reset the form
                            // uploadForm.reset();
                            // previewImage.style.display = "none";
                            window.location.href = '';
                        });;
                    } else {
                        // Show success sweet alert with detailed information
                        // Show success sweet alert with detailed information
                        swal({
                            title: "Attendance Successful!",
                            content: {
                                element: "div",
                                attributes: {
                                    innerHTML: `
                <p><strong>Name:</strong> ${data.predictions[0].name}</p>
                <p><strong>Attendance Time:</strong> ${data.waktu_absen}</p>
            `
                                }
                            },
                            icon: "success",
                            buttons: {
                                confirm: {
                                    text: "Close",
                                    value: true,
                                    visible: true,
                                    className: "btn btn-primary custom-swal-btn",
                                    closeModal: true
                                }
                            }
                        }).then(() => {
                            // Reset the form
                            // uploadForm.reset();
                            // previewImage.style.display = "none";
                            window.location.href = '';
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);

                    // Show network error sweet alert
                    swal({
                        title: "Error",
                        text: "There was a problem processing your request. Please try again.",
                        icon: "error",
                        buttons: {
                            confirm: {
                                text: "Close",
                                value: true,
                                visible: true,
                                className: "btn btn-primary custom-swal-btn",
                                closeModal: true
                            }
                        }
                    }).then(() => {
                        // Reset the form
                        // uploadForm.reset();
                        // previewImage.style.display = "none";
                        window.location.href = '';
                    });;
                });
        });
    </script>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <!-- <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script> -->

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>
</body>

</html>
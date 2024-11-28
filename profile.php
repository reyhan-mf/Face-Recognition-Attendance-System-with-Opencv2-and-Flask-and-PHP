<?php
include("koneksi.php");
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "siswa") {
  header("Location: index.php");
  exit();
}

$query = "SELECT *
FROM siswa s
INNER JOIN kelas k ON s.kelas_id = k.kelas_id
WHERE s.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$data_siswa = $result->fetch_assoc();
// print_r($data_siswa['nama']);

// exit();
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
    .student-profile .card {
      border-radius: 10px;
    }

    .student-profile .card .card-header .profile_img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      margin: 10px auto;
      border: 10px solid #ccc;
      border-radius: 50%;
    }

    .student-profile .card h3 {
      font-size: 20px;
      font-weight: 700;
    }

    .student-profile .card p {
      font-size: 16px;
      color: #000;
    }

    .student-profile .table th,
    .student-profile .table td {
      font-size: 14px;
      padding: 5px 10px;
      color: #000;
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
                <a href="page_absensi_siswa.php" class="collapsed" aria-expanded="false">
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
            <!-- Student Profile -->
            <div class="student-profile py-4">
              <div class="container">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="card shadow-sm">
                      <div class="card-header bg-transparent text-center">
                        <!-- <img
                            class="profile_img"
                            src="https://brandingforthepeople.com/wp-content/uploads/2019/04/Stock-Photography-vs-Real-Imagery.jpg"
                            alt=""
                          /> -->
                        <img class="profile_img" src="data:image/jpeg;base64,<?php echo base64_encode($data_siswa['student_picture']); ?>" alt="Student Picture" />
                        <h3></h3>
                        <h3><?php echo $data_siswa['nama'] ?></h3>
                      </div>
                      <div class="card-body">
                        <p class="mb-0">
                          <strong class="pr-1">Student ID: </strong><?php echo $data_siswa['nis'] ?>
                        </p>
                        <p class="mb-0">
                          <strong class="pr-1">Class: </strong> <?php echo $data_siswa['nama_kelas'] ?>
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8">
                    <div class="card shadow-sm">
                      <div class="card-header bg-transparent border-0">
                        <h3 class="mb-0">
                          <i class="far fa-clone pr-1"></i>General Information
                        </h3>
                      </div>
                      <div class="card-body pt-0">
                        <table class="table table-bordered">
                          <tr>
                            <th width="30%">Gender</th>
                            <td width="2%">:</td>
                            <?php
                            if ($data_siswa['jenis_kelamin'] == 'L') {
                              echo '<td widtd="30%">Laki</td>';
                            } else {
                              echo '<td width="30%">Perempuan</td>';
                            }
                            ?>
                          </tr>
                          <tr>
                            <th width="30%">Academic Year</th>
                            <td width="2%">:</td>
                            <td><?php echo $data_siswa['tahun_ajaran'] ?></td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>

    <!-- Custom template | don't include it in your project! -->

    <!-- End Custom template -->
  </div>

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
<?php
include("koneksi.php");
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'guru') {
  header('Location: index.php');
  exit();
}

$query = "SELECT g.nama
          FROM guru g 
          INNER JOIN user u ON u.user_id = g.user_id 
          WHERE u.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$data_guru = $result->fetch_assoc();

$query_siswa = " SELECT s.student_picture, s.siswa_id , s.nis, s.nama, j.mata_pelajaran, a.waktu_absen, a.status
              FROM siswa s
              INNER JOIN kelas k ON s.kelas_id = k.kelas_id
              INNER JOIN jadwal_pelajaran j ON j.kelas_id = k.kelas_id
              LEFT JOIN absensi a ON a.siswa_id = s.siswa_id
              WHERE j.mata_pelajaran = 'Fisika'
              ";
$stmt_siswa = $conn->prepare($query_siswa);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();
$data_siswa = $result_siswa->fetch_all(MYSQLI_ASSOC);

// echo ($data_guru['nama']);

?>
<!-- Your protected page content -->


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
        families: ["Public Sans:300,400,500,600,700"]
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

  <script src="/assets/js/reyhan.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const filterMenu = document.getElementById('filterMenu');
      const tableRows = document.querySelectorAll('tbody tr');

      filterMenu.addEventListener('click', function(event) {
        const filter = event.target.textContent.trim();
        tableRows.forEach(row => {
          if (filter === 'All' || row.dataset.status === filter) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });
      });
    });

    function updateDropdown(buttonId, value) {
      // Update button text
      document.getElementById(buttonId).textContent = value;

      // Trigger validation
      validateForm();
    }

    function validateForm() {
      // Get current values
      const kelas = document.getElementById("dropdownMenuButtonKelas").textContent.trim();
      const mataPelajaran = document.getElementById("dropdownMenuButtonMataPelajaran").textContent.trim();
      const pertemuan = document.getElementById("dropdownMenuButtonPertemuan").textContent.trim();

      // Apply button element
      const applyButton = document.getElementById("applyButton");

      // Check if all dropdowns have valid selections
      if (kelas !== "Kelas" && mataPelajaran !== "Mata Pelajaran" && pertemuan !== "Pertemuan") {
        applyButton.classList.remove("btn-disabled");
        applyButton.classList.add("btn-active");
        applyButton.disabled = false;
      } else {
        applyButton.classList.add("btn-disabled");
        applyButton.classList.remove("btn-active");
        applyButton.disabled = true;
      }
    }

    let editMode = false;
    let originalStatuses = [];

    function toggleEditMode() {
      const statusDisplays = document.querySelectorAll('.status-display');
      const statusSelects = document.querySelectorAll('.status-select');
      const saveFooter = document.getElementById('saveFooter');

      if (!editMode) {
        // Store original statuses
        originalStatuses = Array.from(statusSelects).map((select) => select.value);
      }

      editMode = !editMode;

      statusDisplays.forEach((display, index) => {
        if (editMode) {
          display.classList.add('d-none');
          statusSelects[index].classList.remove('d-none');
        } else {
          display.classList.remove('d-none');
          statusSelects[index].classList.add('d-none');
        }
      });

      saveFooter.classList.toggle('d-none', !editMode);
    }

    function saveChanges() {
      const statusSelects = document.querySelectorAll('.status-select');
      const statusDisplays = document.querySelectorAll('.status-display');

      statusSelects.forEach((select, index) => {
        const newValue = select.value;
        statusDisplays[index].textContent = newValue;
        statusDisplays[index].className = `status-display badge badge-${getBadgeClass(newValue)}`;
      });

      toggleEditMode(); // Exit edit mode after saving
    }

    function cancelChanges() {
      const statusSelects = document.querySelectorAll('.status-select');
      const statusDisplays = document.querySelectorAll('.status-display');

      // Revert to original statuses
      originalStatuses.forEach((status, index) => {
        statusSelects[index].value = status;
      });

      toggleEditMode(); // Exit edit mode without saving
    }

    function getBadgeClass(status) {
      switch (status) {
        case 'Terabsen':
          return 'success';
        case 'Telat':
          return 'warning';
        case 'Tidak Terabsen':
          return 'danger';
        case 'Sakit':
          return 'sakit'; // Tambahkan class CSS jika perlu
        default:
          return 'secondary';
      }
    }
    let changedFields = {};

    // Track changes in the select inputs
    document.addEventListener("DOMContentLoaded", () => {
      const statusSelects = document.querySelectorAll(".status-select");

      statusSelects.forEach((select) => {
        select.addEventListener("change", () => {
          const siswaId = select.name.match(/\d+/)[0]; // Extract the siswa_id from the name attribute
          changedFields[siswaId] = select.value; // Store the changed status
        });
      });
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById("attendanceForm");
      if (form) {
        form.addEventListener("submit", function(event) {
          // Prevent default form submission
          event.preventDefault();

          // Create a new FormData object
          const formData = new FormData();

          // Add only changed fields to the FormData
          Object.keys(changedFields).forEach((siswaId) => {
            formData.append(`status[${siswaId}]`, changedFields[siswaId]);
            formData.append(`siswa_id[]`, siswaId); // Include the siswa_id array
          });

          // Send the filtered data via an AJAX request
          fetch("attendance_update.php", {
              method: "POST",
              body: formData,
            })
            .then((response) => response.text())
            .then((result) => {
              console.log(result); // Handle success (optional)
              Swal.fire({
                title: 'Success!',
                text: 'Changes saved successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                window.location.reload();
              });
            })
            .catch((error) => console.error("Error:", error));
        });
      } else {
        console.error("Form not found");
      }
    });
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/plugins.min.css" />
  <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
  <link rel="stylesheet" href="assets/css/reyhan.css">

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
      <div class="scroll-wrapper sidebar-wrapper scrollbar scrollbar-inner" style="position: relative;">
        <div class="sidebar-wrapper scrollbar scrollbar-inner scroll-content scroll-scrolly_visible"
          style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 616px;">
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
                <a href="">
                  <i class="fas fa-user
                    "></i>
                  <p>Profile</p><s></s>
                </a>
              </li>

              <li class="nav-item">
                <a href="./logout.php">
                  <i class="fas fa-power-off
                    "></i>
                  <p>Log Out</p><s></s>
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
        <!-- End Navbar -->
      </div>


      <div class="container">
        <div class="page-inner">
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <!-- <div>
              <h3 class="fw-bold mb-3">Halo, Pak <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
            </div> -->
            <div class="page-header">
              <h4 class="page-title">Halo, Pak <?php echo htmlspecialchars($data_guru['nama']); ?></h4>
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
            <div style="display: none;">
              <form class="ms-md-auto py-2 py-md-0 d-flex flex-column flex-md-row gap-2" method="GET" action="your_action_page.php">
                <!-- Dropdown Kelas -->
                <div class="btn-group dropdown">
                  <button class="btn btn-black btn-border dropdown-toggle" type="button" id="dropdownMenuButtonKelas" data-bs-toggle="dropdown">
                    Kelas
                  </button>
                  <ul class="dropdown-menu w-100 scrollable-dropdown" aria-labelledby="dropdownMenuButtonKelas" role="menu" style="max-height: 150px; overflow-y: auto">
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonKelas', '10 IPA 1')">10 IPA 1</a></li>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonKelas', '10 IPS 1')">10 IPS 1</a></li>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonKelas', '11 IPA 1')">11 IPA 1</a></li>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonKelas', '11 IPS 1')">11 IPS 1</a></li>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonKelas', '12 IPA 1')">12 IPA 1</a></li>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonKelas', '12 IPS 1')">12 IPS 1</a></li>
                  </ul>
                </div>

                <!-- Dropdown Mata Pelajaran -->
                <div class="btn-group dropdown">
                  <button class="btn btn-black btn-border dropdown-toggle" type="button" id="dropdownMenuButtonMataPelajaran" data-bs-toggle="dropdown">
                    Mata Pelajaran
                  </button>
                  <ul class="dropdown-menu w-100 scrollable-dropdown" aria-labelledby="dropdownMenuButtonMataPelajaran" role="menu" style="max-height: 150px; overflow-y: auto">
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonMataPelajaran', 'Matematika')">Matematika</a></li>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonMataPelajaran', 'Fisika')">Fisika</a></li>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonMataPelajaran', 'Kimia')">Kimia</a></li>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonMataPelajaran', 'Biologi')">Biologi</a></li>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonMataPelajaran', 'Sejarah')">Sejarah</a></li>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonMataPelajaran', 'Geografi')">Geografi</a></li>
                  </ul>
                </div>

                <!-- Dropdown Pertemuan -->
                <div class="btn-group dropdown">
                  <button class="btn btn-black btn-border dropdown-toggle" type="button" id="dropdownMenuButtonPertemuan" data-bs-toggle="dropdown">
                    Pertemuan
                  </button>
                  <ul class="dropdown-menu w-100 scrollable-dropdown" aria-labelledby="dropdownMenuButtonPertemuan" role="menu" style="max-height: 150px; overflow-y: auto">
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonPertemuan', 'Pertemuan 1')">Pertemuan 1</a></li>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item item" href="#" onclick="updateDropdown('dropdownMenuButtonPertemuan', 'Pertemuan 2')">Pertemuan 2</a></li>
                  </ul>
                </div>

                <!-- Button Apply -->
                <button id="applyButton" class="btn btn-secondary" type="submit" disabled>Apply</button>
              </form>
            </div>


          </div>
          <div class="row">
            <div class="col-sm-4 col-md-4">
              <div class="card card-stats card-danger card-round" style="background-color: rgb(36, 162, 36) !important">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="fas fa-check"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Siswa Hadir</p>
                        <h4 class="card-title">2</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4 col-md-4">
              <div class="card card-stats card-danger card-round" style="background-color: red !important">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="fas fa-times-circle"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Siswa Tidak Hadir</p>
                        <h4 class="card-title">2</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4 col-md-4">
              <div class="card card-stats card-danger card-round" style="background-color: #ffad46 !important">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="fas fa-walking"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Siswa Telat</p>
                        <h4 class="card-title">2</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4 col-md-4">
              <div class="card card-stats card-danger card-round" style="background-color: #d12ac1 !important">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="fas fa-ambulance"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Siswa Sakit</p>
                        <h4 class="card-title">2</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-4 col-md-4">
              <div class="card card-stats card-danger card-round" style="background-color: #d9c700 !important">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="far fa-sticky-note"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Siswa Izin</p>
                        <h4 class="card-title">2</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md">
              <div class="card card-stats card-primary card-round">
                <div class="card-body">
                  <div class="row justify-row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                    <div class="col-7 col-stats col-jumlah">
                      <div class="numbers">
                        <p class="card-category">Jumlah Siswa</p>
                        <h1 class="card-title">32</h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="card card-round">
                <div class="card-header">
                  <div class="card-head-row card-tools-still-right">
                    <div class="card-title">Daftar Absen</div>
                    <!-- DROPDOWN LAGI ABSEN -->
                    <div class="card-tools d-flex align-items-center">
                      <div class="btn-group dropdown me-2">
                        <button class="btn btn-border btn-black dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Filter Status
                        </button>
                        <ul class="dropdown-menu" id="filterMenu">
                          <li><a class="dropdown-item" href="#" onclick="updateTitle(event)">All</a></li>
                          <li><a class="dropdown-item" href="#" onclick="updateTitle(event)">Terabsen</a></li>
                          <li><a class="dropdown-item" href="#" onclick="updateTitle(event)">Telat</a></li>
                          <li><a class="dropdown-item" href="#" onclick="updateTitle(event)">Tidak Terabsen</a></li>
                          <li><a class="dropdown-item" href="#" onclick="updateTitle(event)">Sakit</a></li>
                          <li><a class="dropdown-item" href="#" onclick="updateTitle(event)">Izin</a></li>

                        </ul>
                      </div>
                      <div class="dropdown">
                        <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton" onclick="toggleEditMode()">
                          <i class="fas fa-user-cog"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <form id="attendanceForm" method="POST">
                  <div class="card-body p-0">
                    <div class="table-responsive scrollable-table">
                      <!-- Projects table -->
                      <table class="table align-items-center mb-0" id="attendanceTable">
                        <thead class="thead-light">
                          <tr>
                            <th scope="col">Nama</th>
                            <th scope="col" class="text-end">Waktu & Tanggal</th>
                            <th scope="col" class="text-end">Mata Pelajaran</th>
                            <th scope="col" class="text-end">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $statusOptions = [
                            'terabsen' => ['label' => 'Terabsen', 'badge' => 'badge-success', 'selected' => 'selected'],
                            'tidak terabsen' => ['label' => 'Tidak Terabsen', 'badge' => 'badge-danger', 'selected' => 'selected'],
                            'telat' => ['label' => 'Telat', 'badge' => 'badge-warning', 'selected' => 'selected'],
                            'izin' => ['label' => 'Izin', 'badge' => 'badge-warning', 'selected' => 'selected'],
                            'sakit' => ['label' => 'Sakit', 'badge' => 'badge-warning', 'selected' => 'selected']
                          ];

                          foreach ($data_siswa as $siswa) {
                            $status = $siswa['status'] ?? 'tidak terabsen'; // Default to 'tidak terabsen' if status is null
                            $badgeClass = $statusOptions[$status]['badge'];
                            $selectedStatus = $statusOptions[$status]['selected'] ?? '';
                            $waktuAbsen = $siswa['waktu_absen'] ?? ' - ';
                            $siswaId = $siswa['siswa_id'] ?? '';

                            echo '
                              <tr data-status="' . ucwords(strtolower($status)) . '">
                                  <th scope="row" class="d-flex align-items-center">
                                      <div class="avatar me-4">
                                          <img src="data:image/jpeg;base64,' . base64_encode($siswa['student_picture']) . '" alt="..." class="avatar-img-table rounded-circle" />
                                      </div>
                                      ' . htmlspecialchars($siswa['nama']) . '
                                  </th>
                                  <td class="text-end">' . htmlspecialchars($waktuAbsen) . '</td>
                                  <td class="text-end">Fisika</td>
                                  <td class="text-end">
                                      <input type="hidden" name="siswa_id[]" value="' . htmlspecialchars($siswaId) . '">
                                      <select class="form-select status-select d-none" name="status[' . htmlspecialchars($siswaId) . ']">
                                          <option value="Terabsen" ' . ($status == 'terabsen' ? 'selected' : '') . '>Terabsen</option>
                                          <option value="Telat" ' . ($status == 'telat' ? 'selected' : '') . '>Telat</option>
                                          <option value="Tidak Terabsen" ' . ($status == 'tidak terabsen' ? 'selected' : '') . '>Tidak Terabsen</option>
                                          <option value="Sakit" ' . ($status == 'sakit' ? 'selected' : '') . '>Sakit</option>
                                          <option value="Izin" ' . ($status == 'izin' ? 'selected' : '') . '>Izin</option>
                                      </select>
                                      <span class="status-display badge ' . htmlspecialchars($badgeClass) . '">' . htmlspecialchars($statusOptions[$status]['label']) . '</span>
                                  </td>
                              </tr>
                              ';
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="card-footer text-end d-none" id="saveFooter">
                    <button class="btn btn-primary" type="submit" onclick="saveChanges()">Save</button>
                    <button class="btn btn-black btn-border" type="button" onclick="cancelChanges()">Cancel</button>
                  </div>
                </form>

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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Kaiadmin JS -->
  <script src="assets/js/kaiadmin.min.js"></script>


  <!-- Kaiadmin DEMO methods, don't include it in your project! -->
  <script src="assets/js/setting-demo.js"></script>
  <script src="assets/js/demo.js"></script>
  <script>
    $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
      type: "line",
      height: "70",
      width: "100%",
      lineWidth: "2",
      lineColor: "#177dff",
      fillColor: "rgba(23, 125, 255, 0.14)",
    });

    $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
      type: "line",
      height: "70",
      width: "100%",
      lineWidth: "2",
      lineColor: "#f3545d",
      fillColor: "rgba(243, 84, 93, .14)",
    });

    $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
      type: "line",
      height: "70",
      width: "100%",
      lineWidth: "2",
      lineColor: "#ffa534",
      fillColor: "rgba(255, 165, 52, .14)",
    });
  </script>
</body>

</html>
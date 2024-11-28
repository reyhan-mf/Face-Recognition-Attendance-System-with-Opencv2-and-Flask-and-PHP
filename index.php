<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="form-modal">
        <div class="form-toggle">
            <button id="login-toggle" onclick="toggleLogin()">log in</button>
            <button id="signup-toggle" onclick="toggleSignup()">sign up</button>
        </div>

        <!-- Login Form -->
        <div id="login-form">
            <form action="auth.php" method="POST">
                <input type="hidden" name="action" value="login">
                <input type="email" name="email" placeholder="Enter email or username" required />
                <input type="password" name="password" placeholder="Enter password" required />
                <button type="submit" class="btn login">login</button>
                <p><a href="javascript:void(0)">Forgotten account</a></p>
                <hr />
            </form>
        </div>

        <!-- Register Form -->
        <div id="signup-form">
            <form action="auth.php" method="POST" enctype="multipart/form-data">
                <!-- <form action=""> -->

                <!-- Dropdown for user type -->
                <div class="dropdown-wrapper">
                    <select name="role" class="form-select" required onchange="toggleUserTypeFields(this.value)">
                        <option value="" disabled selected>Select account type</option>
                        <option value="siswa">Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                </div>

                <input type="hidden" name="action" value="register">
                <input type="name" name="nama" placeholder="Masukkan Nama" required />

                <!-- NIP input for Guru -->
                <div id="nip-wrapper">
                    <input type="number" name="nip" placeholder="Masukkan NIP" />
                </div>

                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Create password" required />


                <!-- Student ID input for Murid -->
                <div id="student-id-wrapper">
                    <input type="number" name="nis" placeholder="Masukkan NIS" />
                </div>

                <div class="dropdown-wrapper" id="student-kelas">
                    <select name="kelas_id" class="form-select">
                        <option value="" disabled selected>Pilih Kelas</option>
                        <option value="1">10 IPA 1</option>
                        <option value="2">11 IPA 1</option>
                        <option value="3">12 IPA 1</option>
                    </select>
                </div>



                <div class="gender-cards">

                    <label class="top">Jenis Kelamin</label>
                    <br>
                    <div class="gender-card bottom-left">
                        <input type="radio" id="male" name="jenis_kelamin" value="laki" required>
                        <label for="male">
                            <div class="gender-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 4a4 4 0 100 8 4 4 0 000-8zM6 8a6 6 0 1112 0A6 6 0 016 8zm2 10a3 3 0 00-3 3v1h14v-1a3 3 0 00-3-3H8z" />
                                </svg>
                            </div>
                            <span class="gender-label">Male</span>
                        </label>
                    </div>


                    <div class="gender-card bottom-right">
                        <input type="radio" id="female" name="jenis_kelamin" value="perempuan" required>
                        <label for="female">
                            <div class="gender-icon">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 4a4 4 0 100 8 4 4 0 000-8zM6 8a6 6 0 1112 0A6 6 0 016 8zm2 10a3 3 0 00-3 3v1h14v-1a3 3 0 00-3-3H8z" />
                                </svg>
                            </div>
                            <span class="gender-label">Female</span>
                        </label>
                    </div>
                </div>



                <div id="teacher-subjects">
                    <label class="form-label">Mata Pelajaran yang diajar</label>
                    <div class="subject-cards">
                        <div class="subject-card" data-subject="math">
                            <input type="checkbox" id="math" name="subjects" value="mathematics">
                            <label for="math">
                                <div class="subject-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M7 5h2v2H7v2H5V7H3V5h2V3h2v2zm10 10h2v2h-2v2h-2v-2h-2v-2h2v-2h2v2zM12 2c5.52 0 10 4.48 10 10s-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2zm0 2c-4.42 0-8 3.58-8 8s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8z" />
                                    </svg>
                                </div>
                                <span class="subject-label">Mathematics</span>
                            </label>
                        </div>

                        <div class="subject-card" data-subject="science">
                            <input type="checkbox" id="science" name="subjects" value="science">
                            <label for="science">
                                <div class="subject-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M13 6v11l-5-2.18L5 17V6h8zm6.5 3.5L21 11l-1.5 1.5L18 11l1.5-1.5zM16 17l-2.04 2.04L12 17l1.96-2.04L16 17zm-4-3.5L13.5 12l1.5 1.5-1.5 1.5-1.5-1.5z" />
                                    </svg>
                                </div>
                                <span class="subject-label">Science</span>
                            </label>
                        </div>

                        <div class="subject-card" data-subject="english">
                            <input type="checkbox" id="english" name="subjects" value="english">
                            <label for="english">
                                <div class="subject-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2zm0 15l-5-2.18L7 18V5h10v13z" />
                                    </svg>
                                </div>
                                <span class="subject-label">English</span>
                            </label>
                        </div>

                        <div class="subject-card" data-subject="history">
                            <input type="checkbox" id="history" name="subjects" value="history">
                            <label for="history">
                                <div class="subject-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.25 2.52.77-1.28-3.52-2.09V8z" />
                                    </svg>
                                </div>
                                <span class="subject-label">History</span>
                            </label>
                        </div>

                        <div class="subject-card" data-subject="geography">
                            <input type="checkbox" id="geography" name="subjects" value="geography">
                            <label for="geography">
                                <div class="subject-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                                    </svg>
                                </div>
                                <span class="subject-label">Geography</span>
                            </label>
                        </div>

                        <div class="subject-card" data-subject="art">
                            <input type="checkbox" id="art" name="subjects" value="art">
                            <label for="art">
                                <div class="subject-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
                                    </svg>
                                </div>
                                <span class="subject-label">Art</span>
                            </label>
                        </div>

                        <div class="subject-card" data-subject="music">
                            <input type="checkbox" id="music" name="subjects" value="music">
                            <label for="music">
                                <div class="subject-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z" />
                                    </svg>
                                </div>
                                <span class="subject-label">Music</span>
                            </label>
                        </div>

                        <div class="subject-card" data-subject="pe">
                            <input type="checkbox" id="pe" name="subjects" value="physical_education">
                            <label for="pe">
                                <div class="subject-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M13.49 5.48c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-3.6 13.9l1-4.4 2.1 2v6h2v-7.5l-2.1-2 .6-3c1.3 1.5 3.3 2.5 5.5 2.5v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1l-5.2 2.2v4.7h2v-3.4l1.8-.7-1.6 8.1-4.9-1-.4 2 7 1.4z" />
                                    </svg>
                                </div>
                                <span class="subject-label">Physical Ed</span>
                            </label>
                        </div>
                    </div>
                </div>


                <div class="upload-wrapper" id="student-upload">
                    <input type="file" id="file-upload" name="student_picture" accept="image/*">
                    <label for="file-upload" class="upload-trigger" id="upload-label">
                        <div class="upload-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 5v13m0-13l-4 4m4-4l4 4m-9 9h10c1.1 0 2-.9 2-2v-3" />
                            </svg>
                        </div>
                        <span>Upload Wajah Kamu Disini</span>
                    </label>

                    <div class="file-info" id="file-info">
                        <span class="file-name" id="file-name"></span>
                        <button type="button" class="remove-file" id="remove-file">Remove</button>
                    </div>

                    <img class="file-preview" id="file-preview" alt="File preview">

                    <div class="upload-progress" id="upload-progress">
                        <div class="progress-bar" id="progress-bar"></div>
                    </div>
                </div>
                <button type="submit" class="btn signup">create account</button>
                <p>Clicking <strong>create account</strong> means that you agree to our
                    <a href="javascript:void(0)">terms of services</a>.
                </p>
                <hr />
            </form>
        </div>


    </div>


    <script src="./code.js"></script>
    <script>
        function toggleUserTypeFields(userType) {
            // Elements
            const studentFields = [
                document.getElementById('nis'),
                document.getElementById('kelas_id'),
                document.getElementById('student-upload')
            ];
            const teacherFields = [
                document.getElementById('nip'),
                document.getElementById('teacher-subjects'),
            ];

            if (userType === 'siswa') {
                // Show student fields
                document.getElementById('student-id-wrapper').style.display = 'block';
                document.getElementById('student-kelas').style.display = 'block';
                document.getElementById('student-upload').style.display = 'block'; // Show upload for students

                // Hide teacher fields
                document.getElementById('nip-wrapper').style.display = 'none';
                document.getElementById('teacher-subjects').style.display = 'none';

                // Add required attributes for student fields
                studentFields.forEach(field => field.setAttribute('required', 'true'));

                // Remove required attributes from teacher fields
                teacherFields.forEach(field => field.removeAttribute('required'));
            } else if (userType === 'guru') {
                // Show teacher fields
                document.getElementById('nip-wrapper').style.display = 'block';
                document.getElementById('teacher-subjects').style.display = 'block';

                // Hide student fields
                document.getElementById('student-id-wrapper').style.display = 'none';
                document.getElementById('student-kelas').style.display = 'none';
                document.getElementById('student-upload').style.display = 'none'; // Hide upload for teachers

                // Add required attributes for teacher fields
                teacherFields[0].setAttribute('required', 'true'); // NIP
                // Optional: Handle subjects checkbox validation if needed

                // Remove required attributes from student fields
                studentFields.forEach(field => field.removeAttribute('required'));
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            const uploadInput = document.getElementById('file-upload');
            const uploadLabel = document.getElementById('upload-label');
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const removeButton = document.getElementById('remove-file');
            const filePreview = document.getElementById('file-preview');
            const uploadProgress = document.getElementById('upload-progress');
            const progressBar = document.getElementById('progress-bar');

            // Handle drag and drop
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadLabel.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadLabel.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadLabel.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                uploadLabel.classList.add('drag-over');
            }

            function unhighlight(e) {
                uploadLabel.classList.remove('drag-over');
            }

            uploadLabel.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                uploadInput.files = files;
                handleFiles(files);
            }

            // Handle file selection
            uploadInput.addEventListener('change', function(e) {
                handleFiles(this.files);
            });

            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];

                    // Show file info
                    fileName.textContent = file.name;
                    fileInfo.classList.add('active');

                    // Show preview if it's an image
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            filePreview.src = e.target.result;
                            filePreview.classList.add('active');
                        }
                        reader.readAsDataURL(file);
                    }

                    // Simulate upload progress
                    uploadProgress.classList.add('active');
                    simulateUpload();
                }
            }

            // Remove file
            removeButton.addEventListener('click', function() {
                uploadInput.value = '';
                fileInfo.classList.remove('active');
                filePreview.classList.remove('active');
                uploadProgress.classList.remove('active');
                progressBar.style.width = '0%';
            });

            // Simulate upload progress (remove this in production and replace with actual upload logic)
            function simulateUpload() {
                let progress = 0;
                progressBar.style.width = '0%';

                const interval = setInterval(() => {
                    progress += 5;
                    progressBar.style.width = `${progress}%`;

                    if (progress >= 100) {
                        clearInterval(interval);
                    }
                }, 100);
            }
        });
    </script>

</body>

</html>
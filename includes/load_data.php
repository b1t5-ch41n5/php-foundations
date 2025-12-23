<!DOCTYPE html>
<html lang="en">
<?php
    // 🛠️ Include necessary files for session and database operations
    include 'session.php';
    include 'header_nav.php';
    include '../db/conect_database.php';
    include '../db/crud.php';

    $UserLoged = False;
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['user_id'])) {
        $UserLoged = True; // ✅ User is logged in
    }

    // 🗄️ Create CRUD instance and fetch data from database
    $LevelAndAcademyItems = new Crud($Connection);
    $Level = $LevelAndAcademyItems->level_table();
    $Academy = $LevelAndAcademyItems->AcdemyTable();
    
    // 📋 Fetch lists for dropdown selectors
    $stmt_languages = $Connection->query("SELECT id_lenguage, lenguage_name FROM programming_lenguage_specialized ORDER BY lenguage_name");
    $languages = $stmt_languages->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt_skills = $Connection->query("SELECT id_skill, skill_name FROM skills ORDER BY skill_name");
    $skills = $stmt_skills->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt_professions = $Connection->query("SELECT id_profetion, profetion_name FROM profetions ORDER BY profetion_name");
    $professions = $stmt_professions->fetchAll(PDO::FETCH_ASSOC);
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        #complete-form {
            background: rgba(10, 10, 10, 0.4);
            color: #e0ffe5;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid rgba(0, 102, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.08);
            transition: all 0.3s ease-in-out;
        }
        
        .form-select, .form-control {
            background: rgba(20, 20, 20, 0.7);
            color: #e0ffe5;
            border: 1px solid rgba(0, 102, 255, 0.3);
        }
        
        .form-select:focus, .form-control:focus {
            background: rgba(30, 30, 30, 0.8);
            color: #e0ffe5;
            border-color: #00bfff;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
        }
        
        .form-select option {
            background: #1a1a1a;
            color: #e0ffe5;
            padding: 8px;
        }

        /* Improved styles for multiple selects */
        .form-select[multiple] {
            min-height: 150px;
            padding: 10px;
            background: rgba(20, 20, 20, 0.85);
            border: 2px solid rgba(0, 191, 255, 0.4);
            border-radius: 8px;
        }

        .form-select[multiple]:focus {
            border-color: #00ff88;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.4);
        }

        .form-select[multiple] option {
            padding: 10px;
            margin: 3px 0;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-select[multiple] option:hover {
            background: rgba(0, 191, 255, 0.3);
            color: #ffffff;
        }

        .form-select[multiple] option:checked {
            background: linear-gradient(135deg, #00bfff 0%, #00ff88 100%);
            color: #000;
            font-weight: bold;
        }

        /* Visual improvement for labels */
        .form-label {
            font-weight: 600;
            color: #00bfff;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <div style="background: url('../css/img/load_bg.jpg') no-repeat center center fixed; background-size: cover; min-height: 100vh;">

        <?php if ($UserLoged === True) : ?>
        
            <div class="container pt-4">
                <div class="row justify-content-center">
                    <div class="col-12 text-center">
                        <div class="hero-content">
                            <h1 class="display-4 font-weight-bold epic-title">         
                                <span class="text-warning">B1t5 CH41N5</span>
                                <span class="text-info"> H4CK3R GR0UP</span>
                            </h1>
                        </div>
                    </div>
                
                    <!-- FULL FORM -->
                    <form id="complete-form" method="POST" action="store_data.php" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left column: User creation form -->
                            <div class="col-md-6">
                                <div class="p-4 mt-3">
                                    <h4 class="text-center mb-4 text-info">
                                        <i class="bi bi-person-plus-fill"></i> Create User
                                    </h4>
                                
                                    <!-- User name -->
                                    <div class="mb-3">
                                        <label for="hacker_name" class="form-label">
                                            <i class="bi bi-person"></i> Hacker Name *
                                        </label>
                                        <input type="text" class="form-control" id="hacker_name" name="hacker_name" 
                                               placeholder="Enter your hacker name" >
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="bi bi-key"></i> Password *
                                        </label>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Enter password" >
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">
                                            <i class="bi bi-key-fill"></i> Confirm Password *
                                        </label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                               placeholder="Re-enter password" >
                                    </div>

                                    <!-- Programming Languages (Multiple Select) -->
                                    <div class="mb-3">
                                        <label for="programming_lenguage" class="form-label">
                                            <i class="bi bi-code-slash"></i> Programming Languages *
                                        </label>
                                        <select class="form-select" id="programming_lenguage" name="programming_lenguage">
                                            <option value="">🔽 Choose your programming language</option>
                                            <?php foreach ($languages as $lang): ?>
                                                <option value="<?php echo $lang['id_lenguage']; ?>">
                                                    <?php echo htmlspecialchars($lang['lenguage_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Profession -->
                                    <div class="mb-3">
                                        <label for="profetion" class="form-label">
                                            <i class="bi bi-briefcase-fill"></i> Profession *
                                        </label>
                                        <select class="form-select" id="profetion" name="profetion">
                                            <option value="">🔽 Choose your profession</option>
                                            <?php foreach ($professions as $prof): ?>
                                                <option value="<?php echo $prof['id_profetion']; ?>">
                                                    <?php echo htmlspecialchars($prof['profetion_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Right column: Skills, Level, Academy, Image -->
                            <div class="col-md-6">
                                <div class="p-4 mt-3">
                                    
                                    <!-- Main Skills (Multiple Select) -->
                                    <div class="mb-3">
                                        <label for="skill" class="form-label">
                                            <i class="bi bi-bar-chart-fill"></i> Main Skills *
                                        </label>
                                        <select class="form-select" id="skill" name="skill"  >
                                            <option value="">🔽 Choose your skill</option>
                                            <?php foreach ($skills as $skill): ?>
                                                <option value="<?php echo $skill['id_skill']; ?>">
                                                    <?php echo htmlspecialchars($skill['skill_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Experience Level -->
                                    <div class="mb-3">
                                        <label for="level" class="form-label">
                                            <i class="bi bi-bar-chart-fill"></i> Experience Level *
                                        </label>
                                        <select name="level" id="level" class="form-select" >
                                            <option value="">🔽 Choose your level</option>
                                            <?php
                                                if ($Level && $Level->rowCount() > 0) {
                                                    while ($item = $Level->fetch(PDO::FETCH_ASSOC)):
                                            ?>
                                                <option value="<?php echo $item['id_level']; ?>">
                                                    <?php echo htmlspecialchars($item['level_name']); ?>
                                                </option>
                                            <?php endwhile;
                                                } else {
                                                    echo '<option value="">No levels available</option>';
                                            }?>
                                        </select>
                                    </div>

                                    <!-- Academy -->
                                    <div class="mb-3">
                                        <label for="academy" class="form-label">
                                            <i class="bi bi-journal-bookmark-fill"></i> Academy *
                                        </label>
                                        <select name="id_academy" id="id_academy" class="form-select" >
                                            <option value="">🔽 Choose your academy</option>
                                            <?php
                                                if ($Academy && $Academy->rowCount() > 0) {
                                                    while ($item = $Academy->fetch(PDO::FETCH_ASSOC)):
                                            ?>
                                                        <option value="<?php echo $item['id_academy']; ?>">
                                                            <?php echo htmlspecialchars($item['academy_name']); ?>
                                                        </option>
                                                    <?php endwhile;
                                                } else {
                                                    echo '<option value="">No academies available</option>';
                                            }?>
                                        </select>
                                    </div>

                                    <hr class="my-4" style="border-color: rgba(0, 255, 136, 0.3);">

                                    <!-- Image Upload -->
                                    <h5 class="text-center mb-3 text-success">
                                        <i class="bi bi-cloud-upload-fill"></i> Upload Image (Optional)
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <label for="fileToUpload" class="form-label">
                                            <i class="bi bi-image-fill"></i> Select profile image
                                        </label>
                                        <input type="file" name="fileToUpload" id="fileToUpload" 
                                               class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-center mt-4 mb-3">
                            <button type="submit" name="submit" class="btn btn-primary btn-lg px-5 py-3" 
                                    style="background: linear-gradient(135deg, #00bfff 0%, #00ff88 100%); 
                                           border: none; 
                                           box-shadow: 0 5px 20px rgba(0, 255, 136, 0.3);
                                           font-weight: bold;
                                           letter-spacing: 1px;">
                                <i class="bi bi-check-circle-fill"></i> CREATE USER & UPLOAD
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        <?php else: ?>
            
            <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
                <div class="alert alert-danger text-center p-4 rounded" style="max-width: 500px;">
                    <h4><i class="bi bi-shield-x"></i> Access Denied!</h4>
                    <p>You need to be logged in to view this page.</p>
                    <a href="../index.php" class="btn btn-light mt-2">
                        <i class="bi bi-arrow-left"></i> Go to Login
                    </a>
                </div>
            </div>

        <?php endif; ?>
            
    </div>
    
    <!-- Password Validation Script -->
    <script>
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
                this.style.borderColor = '#ff4444';
            } else {
                this.setCustomValidity('');
                this.style.borderColor = '#00bfff';
            }
        });

        // Form validation before submitting
        document.getElementById('complete-form').addEventListener('submit', function(e) {
            const languages = document.getElementById('programming_lenguage').selectedOptions;
            const skills = document.getElementById('skill').selectedOptions;
            
            if (languages.length === 0) {
                e.preventDefault();
                alert('⚠️ Please select at least one programming language');
                return false;
            }
            
            if (skills.length === 0) {
                e.preventDefault();
                alert('⚠️ Please select at least one skill');
                return false;
            }
        });
    </script>
    

</body>
</html>
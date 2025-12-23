<?php
    // 1. Include dependencies first
    include 'session.php';
    include_once '../db/conect_database.php';
    include_once '../db/crud.php';

    // 2. Create a new connection instance
    $SqlOperation = new Crud($Connection);

    // Variables
    $message = '';
    $message_type = '';
    $Alldata = null;
    $AcademyInfo = null;
    $UserDetailRow = null;
    $show_access_denied = false;

    // ============================================
    // PROCESS SESSION MESSAGES (if they exist)
    // ============================================
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_type = $_SESSION['message_type'];
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }

    // ============================================
    // 2. PROCESS FORM (POST)
    // ============================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user']) && isset($_SESSION['user_id'])) {
        $id_hacker = (int)$_POST['id_hacker'];
        $Table = 'hacker_users';
        $LevelTable = "skill_user";
        $ProgrammingLanguageItems = "programming_users_specialized";

        $current_data_query = $SqlOperation->detail_users($id_hacker);

        if ($current_data_query) {
            $current_data = $current_data_query->fetch(PDO::FETCH_ASSOC);
        } else {
            $_SESSION['message'] = '❌ Error: Unable to fetch user details.';
            $_SESSION['message_type'] = 'danger';
            header("Location: full_info.php?id=$id_hacker&action=view");
            exit();
        }

        if ($current_data) {
            $current_language = $current_data['id_lenguage'] ?? '';
            $current_profession_id = $SqlOperation->get_user_profetion_id($id_hacker);
            $current_skill = $current_data['id_skill'] ?? '';
            $current_level = $current_data['id_level'] ?? '';

            $new_language = trim($_POST['programming_lenguage'] ?? '');
            $new_profession = trim($_POST['profetion'] ?? '');
            $new_skill = trim($_POST['id_skill'] ?? '');
            $new_level = $_POST['level'] ?? '';

            $success = true;
            $changes = [];

            if ($current_language !== $new_language && !empty($new_language)) {
                $result = $SqlOperation->edit_user_info($id_hacker, 'id_lenguage', $new_language, $ProgrammingLanguageItems);
                if ($result) {
                    $changes[] = "Language updated";
                } else {
                    $success = false;
                }
            }

            if (!empty($new_profession)) {
                if (is_numeric($new_profession)) {
                    $new_prof_id = (int)$new_profession;
                    if ($current_profession_id !== $new_prof_id) {
                        $result = $SqlOperation->set_profetion_by_id($id_hacker, $new_prof_id);
                        if ($result) {
                            $changes[] = 'Profession updated';
                        } else {
                            $success = false;
                        }
                    }
                }
            }

            if ($current_skill !== $new_skill && !empty($new_skill)) {
                $result = $SqlOperation->edit_user_info($id_hacker, 'id_skill', $new_skill, $LevelTable);
                if ($result) {
                    $changes[] = "Skill updated";
                } else {
                    $success = false;
                }
            }

            if ($current_level != $new_level && !empty($new_level)) {
                $result = $SqlOperation->edit_user_info($id_hacker, 'id_level', $new_level, $LevelTable);
                if ($result) {
                    $changes[] = "Level updated";
                } else {
                    $success = false;
                }
            }

            if ($success) {
                if (!empty($changes)) {
                    $_SESSION['message'] = '✅ User updated successfully: ' . implode(', ', $changes);
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'ℹ️ No changes detected to update';
                    $_SESSION['message_type'] = 'info';
                }
            } else {
                $_SESSION['message'] = '❌ Error: Unable to update user details.';
                $_SESSION['message_type'] = 'danger';
            }

            header("Location: full_info.php?id=$id_hacker&action=view");
            exit();
        } else {
            $_SESSION['message'] = '❌ Error: User data not found.';
            $_SESSION['message_type'] = 'danger';
            header("Location: full_info.php?id=$id_hacker&action=view");
            exit();
        }
    } 

    if (!isset($_SESSION['user_id'])) { 
        $show_access_denied = true;
    }

    // ============================================
    // 3. FETCH DATA TO DISPLAY (GET)
    // ============================================
    if (isset($_GET['id']) && !empty($_GET['id']) && isset($_SESSION['user_id'])) {
        $id_hacker = (int)$_GET['id'];
        $Alldata = $SqlOperation->detail_users($id_hacker);
        $AcademyInfo = $SqlOperation->user_academies($id_hacker);

        if ($Alldata) {
            $UserDetailRow = $Alldata->fetch(PDO::FETCH_ASSOC);
        }
    }

    include_once 'header_nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #e0e0e0;
            min-height: 100vh;
            font-size: 14px;
        }

        .page-header {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
        }

        .page-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #00bfff;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Card Styles */
        .info-card {
            background: rgba(20, 20, 30, 0.9);
            border: 1px solid rgba(0, 191, 255, 0.3);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .card-header-view {
            background: linear-gradient(135deg, #0066cc, #0099ff);
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0, 191, 255, 0.3);
        }

        .card-header-view h4 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #fff;
        }

        .card-header-view small {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
        }

        /* Info List */
        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            padding: 12px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            font-size: 0.9rem;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list li:hover {
            background: rgba(0, 191, 255, 0.05);
        }

        .info-list strong {
            color: #00bfff;
            min-width: 160px;
            font-weight: 500;
        }

        .info-list span {
            color: #e0e0e0;
            flex: 1;
        }

        /* Buttons */
        .btn-custom {
            padding: 8px 20px;
            font-size: 0.9rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s ease;
            border: 1px solid;
        }

        .btn-edit {
            background: #ffc107;
            border-color: #ffc107;
            color: #000;
        }

        .btn-edit:hover {
            background: #ffb300;
            border-color: #ffb300;
            color: #000;
            transform: translateY(-2px);
        }

        .btn-back {
            background: transparent;
            border-color: #6c757d;
            color: #6c757d;
        }

        .btn-back:hover {
            background: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }

        .btn-update {
            background: #28a745;
            border-color: #28a745;
            color: #fff;
        }

        .btn-update:hover {
            background: #218838;
            border-color: #218838;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: transparent;
            border-color: #dc3545;
            color: #dc3545;
        }

        .btn-cancel:hover {
            background: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        /* Form Styles - Más compacto */
        .edit-header {
            background: linear-gradient(135deg, #5e60ce, #6930c3);
            padding: 25px 30px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .edit-header h3 {
            margin: 0 0 15px 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .user-info-badge {
            display: inline-block;
            background: rgba(0, 0, 0, 0.3);
            padding: 12px 25px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info-badge .user-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #00ff88;
            margin-right: 15px;
        }

        .user-info-badge .user-id {
            font-size: 0.95rem;
            color: #00bfff;
            font-weight: 600;
        }

        .form-section {
            padding: 35px 40px;
        }

        .form-label {
            color: #00bfff;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9rem;
            display: block;
        }

        .form-label i {
            margin-right: 8px;
            font-size: 1rem;
        }

        .form-select, .form-control {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(0, 191, 255, 0.2);
            color: #e0e0e0;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.95rem;
            height: auto;
            margin-bottom: 5px;
        }

        .form-select:focus, .form-control:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #00bfff;
            box-shadow: 0 0 0 0.2rem rgba(0, 191, 255, 0.15);
            color: #fff;
        }

        .form-select option {
            background: #1a1a2e;
            color: #e0e0e0;
            padding: 8px;
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border-width: 1px;
            font-size: 0.9rem;
            padding: 12px 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .info-list li {
                flex-direction: column;
                gap: 5px;
            }
            
            .info-list strong {
                min-width: auto;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<section class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">

                <?php if ($show_access_denied): ?>
                    <div class="alert alert-danger text-center">
                        <h5><i class="bi bi-shield-x"></i> Access Denied</h5>
                        <p class="mb-2">You need to be logged in to view this page.</p>
                        <a href="../index.php" class="btn btn-custom btn-back">← Go to Login</a>
                    </div>

                <?php elseif (isset($UserDetailRow) && $UserDetailRow): ?>
                    
                    <?php if ($message && $message_type): ?>
                        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="page-header">
                        <h2><?php echo isset($_GET['action']) && $_GET['action'] == 'edit' ? '<i class="bi bi-pencil-square"></i> Edit User' : '<i class="bi bi-person-badge"></i> User Profile'; ?></h2>
                    </div>

                    <div class="info-card">
                        <?php if (isset($_GET['action']) && $_GET['action'] == "view"): ?>
                            
                            <!-- VIEW MODE -->
                            <div class="card-header-view">
                                <h4><?php echo htmlspecialchars($UserDetailRow['hacker_name'] ?? 'Unknown'); ?></h4>
                                <small>ID: <?php echo htmlspecialchars($UserDetailRow['id_hacker'] ?? 'N/A'); ?></small>
                            </div>

                            <div class="p-3">
                                <ul class="info-list">
                                    <li>
                                        <strong><i class="bi bi-code-slash"></i> Programming Language </strong>
                                        <span><?php echo htmlspecialchars($UserDetailRow['programming_lenguage'] ?? 'N/A'); ?></span>
                                    </li>
                                    <li>
                                        <strong><i class="bi bi-briefcase"></i> Profession</strong>
                                        <span><?php echo htmlspecialchars($UserDetailRow['profetion'] ?? 'N/A'); ?></span>
                                    </li>
                                    <li>
                                        <strong><i class="bi bi-lightning"></i> Skill</strong>
                                        <span><?php echo htmlspecialchars($UserDetailRow['skill'] ?? 'N/A'); ?></span>
                                    </li>
                                    <li>
                                        <strong><i class="bi bi-bar-chart"></i> Level</strong>
                                        <span><?php echo htmlspecialchars($UserDetailRow['level_name'] ?? 'N/A'); ?></span>
                                    </li>

                                    <?php if (isset($AcademyInfo) && $AcademyInfo !== false): ?>
                                        <?php 
                                        $academyCount = 0;
                                        while ($academyRow = $AcademyInfo->fetch(PDO::FETCH_ASSOC)): 
                                            $academyCount++;
                                        ?>
                                            <li>
                                                <strong><i class="bi bi-mortarboard"></i> Academy <?php echo $academyCount; ?></strong>
                                                <span><?php echo htmlspecialchars($academyRow['academies_enrolled'] ?? 'N/A'); ?></span>
                                            </li>
                                        <?php endwhile; ?>
                                        
                                        <?php if ($academyCount === 0): ?>
                                            <li>
                                                <strong><i class="bi bi-mortarboard"></i> Academy</strong>
                                                <span>No academies enrolled</span>
                                            </li>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <li>
                                            <strong><i class="bi bi-mortarboard"></i> Academy</strong>
                                            <span>No academies found</span>
                                        </li>
                                    <?php endif; ?>
                                </ul>

                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <a href="full_info.php?id=<?php echo $UserDetailRow['id_hacker']; ?>&action=edit" 
                                       class="btn btn-custom btn-edit">
                                        <i class="bi bi-pencil"></i> Edit User
                                    </a>
                                    <a href="home.php" class="btn btn-custom btn-back">
                                        <i class="bi bi-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>

                        <?php elseif (isset($_GET['action']) && $_GET['action'] == "edit"): ?>
                            
                            <!-- EDIT MODE -->
                            <div class="info-card" style="padding: 20px;">
                                <div class="row">
                                    <!-- Static Info Section -->
                                    <div class="col-md-4">
                                        <div class="card-header-view">
                                            <h4><?php echo htmlspecialchars($UserDetailRow['hacker_name'] ?? 'Unknown'); ?></h4>
                                            <small>ID: <?php echo htmlspecialchars($UserDetailRow['id_hacker'] ?? 'N/A'); ?></small>
                                        </div>
                                        <ul class="info-list">
                                            <li>
                                                <strong><i class="bi bi-code-slash"></i> Programming Language </strong>
                                                <span><?php echo htmlspecialchars($UserDetailRow['programming_lenguage'] ?? 'N/A'); ?></span>
                                            </li>
                                            <li>
                                                <strong><i class="bi bi-briefcase"></i> Profession</strong>
                                                <span><?php echo htmlspecialchars($UserDetailRow['profetion'] ?? 'N/A'); ?></span>
                                            </li>
                                            <li>
                                                <strong><i class="bi bi-lightning"></i> Skill</strong>
                                                <span><?php echo htmlspecialchars($UserDetailRow['skill'] ?? 'N/A'); ?></span>
                                            </li>
                                            <li>
                                                <strong><i class="bi bi-bar-chart"></i> Level</strong>
                                                <span><?php echo htmlspecialchars($UserDetailRow['level_name'] ?? 'N/A'); ?></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Editable Form Section -->
                                    <div class="col-md-8">
                                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $UserDetailRow['id_hacker']; ?>&action=edit">
                                            <input type="hidden" name="id_hacker" value="<?php echo htmlspecialchars($UserDetailRow['id_hacker'] ?? ''); ?>">

                                            <div class="mb-3">
                                                <label class="form-label"><i class="bi bi-code-slash"></i> Programming Language </label>
                                                <select name="programming_lenguage" class="form-select">
                                                    <option value="">Select language</option>
                                                    <?php
                                                    $ProgrammingLanguageItems = $Connection->query("SELECT id_lenguage, lenguage_name FROM programming_lenguage_specialized ORDER BY lenguage_name");
                                                    foreach ($ProgrammingLanguageItems as $lang): ?>
                                                        <option value="<?php echo $lang['id_lenguage']; ?>">
                                                            <?php echo htmlspecialchars($lang['lenguage_name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><i class="bi bi-briefcase"></i> Profession</label>
                                                <select name="profetion" class="form-select" >
                                                    <option value="">Select profession</option>
                                                    <?php
                                                    $ProfetionItems = $SqlOperation->get_profetions_list();
                                                    while ($profRow = $ProfetionItems->fetch(PDO::FETCH_ASSOC)): ?>
                                                        <option value="<?php echo $profRow['id_profetion']; ?>">
                                                            <?php echo htmlspecialchars($profRow['profetion_name']); ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><i class="bi bi-lightning"></i> Skill</label>
                                                <select name="id_skill" class="form-select">
                                                    <option value="">Select skill</option>
                                                    <?php
                                                    $SkillItems = $Connection->query("SELECT id_skill, skill_name FROM skills ORDER BY skill_name");
                                                    foreach ($SkillItems as $skill): ?>
                                                        <option value="<?php echo $skill['id_skill']; ?>">
                                                            <?php echo htmlspecialchars($skill['skill_name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><i class="bi bi-bar-chart"></i> Level</label>
                                                <select name="level" class="form-select">
                                                    <option value="">Select level</option>
                                                    <?php
                                                    $LevelItems = $SqlOperation->level_table();
                                                    while ($levelRow = $LevelItems->fetch(PDO::FETCH_ASSOC)): ?>
                                                        <option value="<?php echo $levelRow['id_level']; ?>">
                                                            <?php echo htmlspecialchars($levelRow['level_name']); ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>

                                            <div class="d-flex gap-3 justify-content-end">
                                                <button type="submit" name="update_user" class="btn btn-custom btn-update">
                                                    <i class="bi bi-check-circle"></i> Update User
                                                </button>
                                                <a href="full_info.php?id=<?php echo $UserDetailRow['id_hacker']; ?>&action=view" 
                                                   class="btn btn-custom btn-cancel">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// This file shows complete information about a user

?>
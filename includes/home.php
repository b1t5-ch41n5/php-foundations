<style>
    /* 🎨 Table styles for better readability */
    table {
        border-collapse: separate !important;
        border-spacing: 0 10px;
    }

    table td, table th {
        border: none !important;
        background-color: rgba(20, 20, 20, 0.9) !important;
        border-radius: 8px;
        padding: 12px;
        vertical-align: middle;
    }

    table tbody tr:hover {
        background-color: rgba(0, 255, 136, 0.2) !important;
        box-shadow: 0 0 10px rgba(0, 255, 136, 0.3);
        transition: 0.2s ease;
    }

    body {
        /* 🖤 Dark theme background */
        background: black;
        color: white;
        overflow-x: hidden;
    }

    body::before {
        /* 🎥 Subtle animated background */
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('https://media.giphy.com/media/oEI9uBYSzLpBK/giphy.gif') center center / cover no-repeat;
        opacity: 0.1;
        z-index: -1;
    }



</style>
<?php
// 🏠 Home page setup
$Title = "Hacker Dashboard";
include "../db/conect_database.php"; 
include "session.php";
include "../db/crud.php";
include "../db/users.php";

$showContent = false;
$errorMessage = '';
$successMessage = '';
const TOGGLE_BUTTON = '<button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#dataTable" aria-expanded="false">Show/Hide Data</button>';
$SqlOperation = new Crud($Connection);


// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// PROCESS FORMS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['login'])) {
        if (isset($_POST['hacker_user']) && isset($_POST['password']) && 
            !empty(trim($_POST['hacker_user'])) && !empty($_POST['password'])) {
            
            $SqlOperationUser = new Users($Connection);
            $user = trim($_POST['hacker_user']);
            $password = $_POST['password'];

            // check autehntication
            
            try {
                $UserQuery = $SqlOperationUser->GetUser($user);
                
                if ($UserQuery && is_array($UserQuery)) {
                    // Verify user
                    if (isset($UserQuery['hacker_name']) && $UserQuery['hacker_name'] === $user) {
                    
                        // Hash the input password using SHA256 for security.
                        $hashedInputPassword = hash('sha256', $password);
                        
                        // Verify password
                        if (isset($UserQuery['password']) && $UserQuery['password'] === $hashedInputPassword) {
                            // ✅ LOGIN SUCCESSFUL
                            session_regenerate_id(true);
                            
                            $_SESSION['username'] = $user;
                            $_SESSION['user_id'] = $UserQuery['id_hacker']; // ✅ Correct field
                            $_SESSION['login_time'] = time();
                            $_SESSION['logged_in'] = true;
                            $_SESSION['login_success'] = true;
                            
                            header('Location: ' . $_SERVER['PHP_SELF']);
                            exit();
                            
                        } else {
                            $errorMessage = "❌ Incorrect password";
                        }
                    } else {
                        $errorMessage = "❌ User not found: " . htmlspecialchars($user);
                    }
                } else {
                    $errorMessage = "❌ No user data found";
                }
                
            } catch (Exception $e) {
                $errorMessage = "❌ System error: " . $e->getMessage();
            }
        } else {
            $errorMessage = "❌ Enter username and password";
        }

    } elseif (isset($_POST['delete_user']) && $isLoggedIn) {
        // DELETE USER via POST (more secure)
        if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
            $userId = intval($_POST['user_id']);
            $DeleteUser = $SqlOperation->delete_user($userId, 'user_credentials');
            
            if (!$DeleteUser) {
                $errorMessage = "Error deleting user";
            } else {
                $successMessage = "User deleted successfully";
                $showContent = true;
            }
        } else {
            $errorMessage = "Invalid user ID for deletion";
        }
    }
} 

// Handle successful login status and clear temporary flag
if ($isLoggedIn) {
    if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
        $successMessage = "✅ Login successful! Welcome " . htmlspecialchars($_SESSION['username']);
        $showContent = false; // Do not show content until the flag is processed
        // Clear the temporary flag after displaying it
        unset($_SESSION['login_success']);
    } else {
        // User already logged in - show normal dashboard
        $UserInfo = $SqlOperation->get_user_details_by_id($_SESSION['user_id']);
        if ($UserInfo) {
            $successMessage = "✅ Welcome back, " . htmlspecialchars($_SESSION['username']) . "!";
            $showContent = False;
        } else {
            $errorMessage = "Error retrieving user information";
        }
    }
}

// If not logged in and no specific errors, show access required message
if (!$isLoggedIn && empty($errorMessage)) {
    $errorMessage = "Please log in to access this page";
}

// FUNCTION TO DISPLAY RECORDS
function displayRecords($SqlOperation, $operation) { 
    $allData = $SqlOperation->get_user_list();
    ?>
    <div class="container mt-4">
        <p class="text-success">Data loaded successfully</p>
        <div class="hero-content">
            <h3 class="display-6 font-weight-bold text-warning mb-3 text-center">
                <i class="bi bi-person-plus"></i>
                <?php echo "Number of records found: " . $allData->rowCount(); ?>
            </h3>
        </div>
        
       
        <!-- show/hide data table -->

        <?php if(!$operation) {
            echo TOGGLE_BUTTON   ;
        }?>

        <div class="collapse mt-3 <?php echo $operation ? 'show' : ''; ?>" id="dataTable">
            <div class="card bg-dark bg-opacity-75 text-light shadow-lg border border-success rounded-4">
                <div class="card-header">
                    <h5 class="text-center">Hacker Users Data</h5>
                </div>
                <div class="card-body">
                    <?php if ($allData && $allData->rowCount() > 0): ?>
                        <table class="table table-dark table-hover table-striped text-center align-middle shadow rounded-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hacker Name</th>
                                    <th>Creation Date</th>
                                    <th>View Info</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $allData->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id_hacker'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars(trim($row['hacker_name'] ?? '')); ?></td> 
                                        <td><?php echo htmlspecialchars($row['created_at'] ?? ''); ?></td>                                   
                                        <td>
                                            <a href='full_info.php?id=<?php echo $row['id_hacker']; ?>&action=view' class="btn btn-primary btn-sm">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                        <td>
                                            <a href='full_info.php?id=<?php echo $row['id_hacker']; ?>&action=edit' class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </td>
                                        <td>
                                            <!-- This form is used to delete the user along with the confirmation message which is a JavaScript function -->

                                            <form method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                <input type="hidden" name="delete_user" value="1">
                                                <input type="hidden" name="user_id" value="<?php echo $row['id_hacker']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-warning">No data found in the database.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($Title); ?></title>
</head>

<body style="background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('../css/img/home.jpg') no-repeat center center fixed; background-size: cover; min-height: 100vh;">
    <?php include "header_nav.php"; ?>

<?php if (!empty($errorMessage)): ?>
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h4>⚠️ Error</h4>
            <p><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php if (!$isLoggedIn): ?>
                <hr>
                <p class="mb-0">
                    <a href="../index.php" class="btn btn-primary">← Go to Login</a>
                </p>
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($successMessage)): ?>
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p class="mb-0"><?php echo htmlspecialchars($successMessage); ?></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<?php
// Display content based on status
if ($isLoggedIn && $showContent) {
    displayRecords($SqlOperation, true);
} elseif ($isLoggedIn && !$showContent && empty($errorMessage)) {
    // User logged in but no specific content to display
    displayRecords($SqlOperation, False);
} elseif (!$isLoggedIn && empty($successMessage)) {
    // Not logged in and no success message
    ?>
    <div class="container mt-4">
        <div class="alert alert-info">
            <h4>🔐 Access Required</h4>
            <p>Please log in to access the dashboard.</p>
            <a href="../index.php" class="btn btn-primary">Go to Login</a>
        </div>
    </div>
    <?php
}
?>

</body>
</html>
<?php
    // 🌐 Database connection setup
    $ServerIp = "127.0.0.1";
    $Database = "hackers_test";
    $Port = "3306";
    $Password = 'Administrator123';
    $Charset = "utf8mb4";
    $Username = "root";

    // 🛠️ PDO connection string
    $Dsn = "mysql:host=$ServerIp;dbname=$Database;charset=$Charset";
    try {
        $Connection = new PDO($Dsn, $Username, $Password);
        // Enable error reporting for debugging
        $Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) { ?>
        <div class="container">
            <div class="card" style="width: 60rem;">
                <ul class="list-group list-group-flush">
                    <!-- Display error message -->
                    <li class="list-group-item text-primary text-center"> THE FOLLOWING ITEM IS CLEARER </li>
                    <li class="list-group-item text-success">  <?php echo htmlspecialchars($e->getMessage()); ?>  </li>

                    <!-- Throw detailed error for debugging -->
                    <li class="list-group-item text-primary text-center"> THE FOLLOWING ITEM IS LESS CLEAR </li>
                    <li class="list-group-item text-success"> <?php throw new PDOException($e->getMessage()); ?> </li>
                </ul>
            </div>
        </div>
    <?php  }
?>
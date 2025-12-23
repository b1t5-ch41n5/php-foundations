<?php
class Users {
    private $ConnectionObject;
    
    function __construct($Connection) {
        $this->ConnectionObject = $Connection;
    }
    
    // ➕ Create a new user
    public function NewUser($name, $password) {
        try {
            // Check if user already exists
            $CheckUser = $this->ExistingUser($name);
            
            if ($CheckUser['amount'] > 0) {
                return False; // User already exists
            } else {
                // Hash the password for security
                $password = hash('sha256', $password);
                
                // Insert user into the database
                $Sql = "INSERT INTO user_credentials (hacker_name, password) VALUES (:name, :password)";
                $Stmtm = $this->ConnectionObject->prepare($Sql);
                $Stmtm->bindParam(':name', $name);
                $Stmtm->bindParam(':password', $password);
                $Stmtm->execute();
                return True;
            }
        } catch (PDOException $e) {
            error_log("NewUser Error: " . $e->getMessage());
            return False;
        }
    }
    
    // 🔍 Retrieve user information
    public function GetUser($user) {
        try {
            $Sql = "SELECT * FROM user_credentials as u WHERE u.hacker_name = :user";
            
            $Stmtm = $this->ConnectionObject->prepare($Sql);
            $Stmtm->bindParam(":user", $user);
            $Stmtm->execute();
            
            $result = $Stmtm->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        } catch (PDOException $e) {
            error_log("GetUser Error: " . $e->getMessage());
            return null;
        }
    }
    
    // Method to check if user exists
    public function ExistingUser($user) {
        try {
            $Sql = "SELECT COUNT(*) as amount FROM user_credentials WHERE hacker_name = :username";
            $Stmtm = $this->ConnectionObject->prepare($Sql);
            $Stmtm->bindParam(":username", $user);
            $Stmtm->execute();
            return $Stmtm->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ExistingUser Error: " . $e->getMessage());
            return false;
        }
    }

    public function UserImages($id_hacker) {
        try {
            $Sql = "SELECT * FROM userimages WHERE id_hacker = :id_hacker";
            $Stmtm = $this->ConnectionObject->prepare($Sql);
            $Stmtm->bindParam(":id_hacker", $id_hacker);
            $Stmtm->execute();
            return $Stmtm;
        } catch (PDOException $e) {
            error_log("UserImages Error: " . $e->getMessage());
            return false;
        }
    }

    public function InsertUserImages($id_hacker, $image) {
        try {
            $Sql = "INSERT INTO userimages (id_hacker, image_path) VALUES (:id_hacker, :image)";
            $Stmtm = $this->ConnectionObject->prepare($Sql);
            $Stmtm->bindParam(':id_hacker', $id_hacker);
            $Stmtm->bindParam(':image', $image);
            $Stmtm->execute();
            return True;
        } catch (PDOException $e) {
            error_log("InsertUserImages Error: " . $e->getMessage());
            return false;
        }
    }

    
    public function UpdateUserImages($id_image, $image) {
        try {
            // ✅ Use the correct field name: id_image (according to your CREATE TABLE)
            $Sql = "UPDATE userimages SET image_path = :image WHERE id_image = :id_image";
            $Stmtm = $this->ConnectionObject->prepare($Sql);
            $Stmtm->bindParam(':id_image', $id_image);
            $Stmtm->bindParam(':image', $image);
            $Stmtm->execute();
            return True;
        } catch (PDOException $e) {
            error_log("UpdateUserImages Error: " . $e->getMessage());
            return false;
        }
    }

    public function DeleteUserImages($id_image = null) {
        try {
            if ($id_image) {

                // ✅ Use the correct field name: id_image
                $ImagePath = $this->ConnectionObject->prepare("SELECT image_path FROM userimages WHERE id_image = :id_image");
                $ImagePath->bindParam(':id_image', $id_image);
                $ImagePath->execute();
                $ImagePath = $ImagePath->fetch(PDO::FETCH_ASSOC);

                $Sql = "DELETE FROM userimages WHERE id_image = :id_image";
                $Stmtm = $this->ConnectionObject->prepare($Sql);
                $Stmtm->bindParam(':id_image', $id_image);
                $Stmtm->execute();
                return $ImagePath;
            }

        } catch (PDOException $e) {
            error_log("DeleteUserImages Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
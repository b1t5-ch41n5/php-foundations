<?php 

    class Crud {
        private $ConnectioObject;
        private $UserTable = 'hacker_users';
        public $id;
        private $column;
        private $value;

        function __construct($connection){
            $this->ConnectioObject = $connection;                   
        }

        // ✏️ Update user information in the database
        function edit_user_info($id, $column, $value, $table) {
            $this->id = $id;
            $this->column = $column;
            $this->value = $value;
            $this->UserTable = $table;
            
            try {
                $sql = "UPDATE " . $this->UserTable . " SET " . $this->column . " = :value WHERE id_hacker = :id";
                
                $Stmtm = $this->ConnectioObject->prepare($sql);
                $Stmtm->bindParam(':value', $this->value);
                $Stmtm->bindParam(':id', $this->id, PDO::PARAM_INT);
                $Stmtm->execute();
                
                return true;
                
            } catch (PDOException $e) {
                echo "Error updating: " . htmlspecialchars($e->getMessage());
                return false;
            }
        }

        // 🗑️ Delete a user from the database
        function delete_user($id, $table) {
            $this->id = $id;
            $this->UserTable = $table;

            try {
                $sql = "DELETE FROM " . $this->UserTable . " WHERE id_hacker = :id";
                $Stmtm = $this->ConnectioObject->prepare($sql);
                $Stmtm->bindParam(':id', $this->id, PDO::PARAM_INT);
                $Stmtm->execute();
                return true;
            } catch (PDOException $e) {
                echo "Error deleting: " . htmlspecialchars($e->getMessage());
                return false;
            }
        }


        function get_user_list() {
            try {
                $Query = "SELECT 
                            h.id_hacker as id_hacker,
                            h.hacker_name as hacker_name,
                            h.created_at as created_at
                        FROM user_credentials as h";

                $stmt = $this->ConnectioObject->query($Query);
                return $stmt;
            } catch (PDOException $e) {
                echo "Error fetching user list: " . htmlspecialchars($e->getMessage());
                return false;
            }
        }

        function get_user_details_by_id($id) {
            $this->id = $id;
            try {
                $Query = "SELECT 
                            id_hacker,
                            hacker_name,
                            created_at
                        FROM user_credentials
                        WHERE id_hacker = :id";

                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                error_log("Error in get_user_details_by_id: " . $e->getMessage());
                return false;
            }
        }


        function get_user_skills_by_id($id) {
            $this->id = $id;
            try {
                $Query = "SELECT s.skill_name, l.level_name
                        FROM skill_user AS su
                        INNER JOIN skills AS s ON su.id_skill = s.id_skill
                        INNER JOIN skill_level AS l ON su.id_level = l.id_level
                        WHERE su.id_hacker = :id
                        ORDER BY s.skill_name";

                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                error_log("Error in get_user_skills_by_id: " . $e->getMessage());
                return false;
            }
        }


        function get_profetions_list() {
            try {
                $Query = "SELECT id_profetion, profetion_name FROM profetions ORDER BY profetion_name";
                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                error_log("Error in get_profetions_list: " . $e->getMessage());
                return false;
            }
        }

        function get_user_profetion_id($id_hacker) {
            try {
                $Query = "SELECT id_profetion FROM profetion_users WHERE id_hacker = :id LIMIT 1";

                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->bindParam(':id', $id_hacker, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return ($row && isset($row['id_profetion'])) ? (int)$row['id_profetion'] : null;
            } catch (PDOException $e) {
                error_log("Error in get_user_profetion_id: " . $e->getMessage());
                return null;
            }
        }

        function set_profetion_by_id($id_hacker, $profetion_id) {
            try {
                $del = $this->ConnectioObject->prepare("DELETE FROM profetion_users WHERE id_hacker = :id");
                $del->bindParam(':id', $id_hacker, PDO::PARAM_INT);
                $del->execute();

                $insMap = $this->ConnectioObject->prepare("INSERT INTO profetion_users (id_profetion, id_hacker) VALUES (:prof, :id)");
                $insMap->bindParam(':prof', $profetion_id, PDO::PARAM_INT);
                $insMap->bindParam(':id', $id_hacker, PDO::PARAM_INT);
                return $insMap->execute();
            } catch (PDOException $e) {
                error_log("Error in set_profetion_by_id: " . $e->getMessage());
                return false;
            }
        }

        
        /**
         * ✅ FIXED: Now uses the correct column names
         */
        function detail_users($id) {
            $this->id = $id;
            try {
                $Query = "SELECT uc.id_hacker as id_hacker, 
                                 uc.hacker_name,
                                 pl.lenguage_name AS programming_lenguage, 
                                 p.profetion_name AS profetion, 
                                 s.skill_name AS skill, 
                                 su.id_skill AS id_skill,
                                 l.level_name AS level_name, 
                                 su.id_level
                          FROM user_credentials uc
                          LEFT JOIN programming_users_specialized pus ON uc.id_hacker = pus.id_hacker
                          LEFT JOIN programming_lenguage_specialized pl ON pus.id_lenguage = pl.id_lenguage
                          LEFT JOIN profetion_users pu ON uc.id_hacker = pu.id_hacker
                          LEFT JOIN profetions p ON pu.id_profetion = p.id_profetion
                          LEFT JOIN skill_user su ON uc.id_hacker = su.id_hacker
                          LEFT JOIN skills s ON su.id_skill = s.id_skill
                          LEFT JOIN skill_level l ON su.id_level = l.id_level
                          WHERE uc.id_hacker = :id";

                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                error_log("Error in detail_users: " . $e->getMessage());
                return false;
            }
        }
        
        /**
         * ✅ FIXED: Returns fetch() instead of statement
         */
        function get_skill_level_info($id_hacker) {
            try {
                $Query = "SELECT su.id_skill AS sk_id, 
                                 sk.skill_name AS sk_name, 
                                 su.id_level AS sk_level, 
                                 sl.level_name AS sl_name  
                          FROM skill_user su
                          INNER JOIN skills sk ON su.id_skill = sk.id_skill 
                          INNER JOIN skill_level sl ON su.id_level = sl.id_level 
                          WHERE su.id_hacker = :id 
                          ORDER BY su.id_skill
                          LIMIT 1";
                
                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->bindParam(':id', $id_hacker, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Error in get_skill_level_info: " . $e->getMessage());
                return false;
            }
        }

        function update_hacker_name($id, $new_name) {
            try {
                $sql = "UPDATE user_credentials SET hacker_name = :name WHERE id_hacker = :id";
                $stmt = $this->ConnectioObject->prepare($sql);
                $stmt->bindParam(':name', $new_name);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch (PDOException $e) {
                error_log("Error in update_hacker_name: " . $e->getMessage());
                return false;
            }
        }

        function set_programming_language_single($id_hacker, $language_name) {
            try {
                $sel = $this->ConnectioObject->prepare("SELECT id_lenguage FROM programming_lenguage_specialized WHERE lenguage_name = :name LIMIT 1");
                $sel->bindParam(':name', $language_name);
                $sel->execute();
                $row = $sel->fetch(PDO::FETCH_ASSOC);
                if ($row && isset($row['id_lenguage'])) {
                    $lang_id = $row['id_lenguage'];
                } else {
                    $ins = $this->ConnectioObject->prepare("INSERT INTO programming_lenguage_specialized (lenguage_name) VALUES (:name)");
                    $ins->bindParam(':name', $language_name);
                    $ins->execute();
                    $lang_id = $this->ConnectioObject->lastInsertId();
                }

                $insMap = $this->ConnectioObject->prepare("INSERT INTO programming_users_specialized (id_hacker, id_lenguage) VALUES (:id, :lang)");
                $insMap->bindParam(':id', $id_hacker, PDO::PARAM_INT);
                $insMap->bindParam(':lang', $lang_id, PDO::PARAM_INT);
                return $insMap->execute();

            } catch (PDOException $e) {
                error_log("Error in set_programming_language_single: " . $e->getMessage());
                return false;
            }
        }

        function set_profetion_single($id_hacker, $profetion_name) {
            try {
                $stmt = $this->ConnectioObject->prepare("SELECT id_profetion FROM profetions WHERE profetion_name = :name LIMIT 1");
                $stmt->bindParam(':name', $profetion_name, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    return $this->set_profetion_by_id($id_hacker, $row['id_profetion']);
                } else {
                    $insert = $this->ConnectioObject->prepare("INSERT INTO profetions (profetion_name) VALUES (:name)");
                    $insert->bindParam(':name', $profetion_name, PDO::PARAM_STR);
                    $insert->execute();
                    $new_id = $this->ConnectioObject->lastInsertId();
                    return $this->set_profetion_by_id($id_hacker, $new_id);
                }
            } catch (PDOException $e) {
                error_log("Error in set_profetion_single: " . $e->getMessage());
                return false;
            }
        }

        function set_skill_and_level_single($id_hacker, $skill_id, $level_id) {
            try {
                $sel = $this->ConnectioObject->prepare("SELECT id_skill FROM skills WHERE id_skill = :id LIMIT 1");
                $sel->bindParam(':id', $skill_id, PDO::PARAM_INT);
                $sel->execute();
                $row = $sel->fetch(PDO::FETCH_ASSOC);
                
                if ($row && isset($row['id_skill'])) {
                    $skill_id = $row['id_skill'];
                } else {
                    return false;
                }

                // Delete old mappings
                $del = $this->ConnectioObject->prepare("DELETE FROM skill_user WHERE id_hacker = :id");
                $del->bindParam(':id', $id_hacker, PDO::PARAM_INT);
                $del->execute();

                // Insert new mapping
                $insMap = $this->ConnectioObject->prepare("INSERT INTO skill_user (id_skill, id_level, id_hacker) VALUES (:skill, :level, :id)");
                $insMap->bindParam(':skill', $skill_id, PDO::PARAM_INT);
                $insMap->bindParam(':level', $level_id, PDO::PARAM_INT);
                $insMap->bindParam(':id', $id_hacker, PDO::PARAM_INT);
                return $insMap->execute();

            } catch (PDOException $e) {
                error_log("Error in set_skill_and_level_single: " . $e->getMessage());
                return false;
            }
        }

        function level_table(){
            try{
                $Query = "SELECT * FROM skill_level as l"; 
                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e){
                echo "Error: " . htmlspecialchars($e->getMessage());
                return false;
            }
        }

        function AcdemyTable(){
            try{
                $Query = "SELECT * FROM academy_names as a "; 
                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e){
                echo "Error: " . htmlspecialchars($e->getMessage());
                return false;
            }
        }
        
        function insert_academy($table, $academy_name) {
            $this->UserTable = $table;
            try{
                $sql = "INSERT INTO `$this->UserTable` (academy_name) VALUES (:academy_name)";
                $Stmtm = $this->ConnectioObject->prepare($sql);
                $Stmtm->bindParam(':academy_name', $academy_name);
                $Stmtm->execute();
                return true;
            } catch (PDOException $e) {
                echo "you might have made a mistake " . htmlspecialchars($e->getMessage());
                return false;
            }
        }

        function set_academy_by_name($id_hacker, $id_academy) {
            try {
                $sel = $this->ConnectioObject->prepare("SELECT id_academy FROM academy_names WHERE id_academy = :id_academy LIMIT 1");
                $sel->bindParam(':id_academy', $id_academy, PDO::PARAM_INT);
                $sel->execute();
                $row = $sel->fetch(PDO::FETCH_ASSOC);
                
                if ($row && isset($row['id_academy'])) {
                    $academy_id = $row['id_academy'];
                } else {
                    return false;
                }

                $insMap = $this->ConnectioObject->prepare("INSERT INTO academy_users (id_academy, id_hacker) VALUES (:academy, :id)");
                $insMap->bindParam(':academy', $academy_id, PDO::PARAM_INT);
                $insMap->bindParam(':id', $id_hacker, PDO::PARAM_INT);
                return $insMap->execute();
            } catch (PDOException $e) {
                error_log("Error in set_academy_by_name: " . $e->getMessage());
                return false;
            }
        }

        function user_academies($user_id) {
            $this->id = $user_id;
            try {
                $Query = "SELECT an.academy_name as academies_enrolled
                          FROM academy_users AS au
                          INNER JOIN academy_names AS an ON au.id_academy = an.id_academy
                          WHERE au.id_hacker = :user_id
                          ORDER BY 1";
                
                $stmt = $this->ConnectioObject->prepare($Query);
                $stmt->bindParam(':user_id', $this->id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt;
            } catch (PDOException $e) {
                echo "Error in user_academies: " . htmlspecialchars($e->getMessage());
                return false;
            }
        }

    }
?>
DROP DATABASE IF EXISTS hackers_test;

CREATE DATABASE IF NOT EXISTS hackers_test;
USE hackers_test;

-- ================================================================
-- MAIN TABLES
-- ================================================================

-- Credentials Table (MAIN)
CREATE TABLE user_credentials (
  id_hacker TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  hacker_name VARCHAR(20) NOT NULL UNIQUE,
  password VARCHAR(64) NOT NULL, -- Para hash SHA-256 (64 caracteres hex)
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Professions
CREATE TABLE profetions (
	id_profetion TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    profetion_name VARCHAR(20) NOT NULL 
);

INSERT INTO profetions (profetion_name) VALUES 
('Software Developer'), 
('Pentester'), 
('Digital Forensics'),  
('Malware Analyst'); 

-- Profession-User Relationship (WITH CASCADE DELETE)
CREATE TABLE profetion_users(
	id_profetion_record TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_profetion TINYINT NOT NULL, 
    id_hacker TINYINT NOT NULL,
    FOREIGN KEY (id_profetion) REFERENCES profetions(id_profetion) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_hacker) REFERENCES user_credentials(id_hacker) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Levels Table
CREATE TABLE skill_level (
  id_level TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  level_name VARCHAR(13) DEFAULT NULL
);

INSERT INTO skill_level (level_name) VALUES 
('Script kiddie'), 
('Beginner'), 
('Pro'), 
('Elite'), 
('Legendary');

-- Skills 
CREATE TABLE skills (
  id_skill TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  skill_name VARCHAR(20) NOT NULL
);

  

INSERT INTO skills (skill_name) VALUES 
('Programming'), 
('Penetration testing'), 
('Web development'), 
('Bilingual'), 
('Malware development'), 
('Android development');

-- Skill-User-Level Relationship (WITH CASCADE DELETE)
CREATE TABLE skill_user (
  id_skill_record TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_skill TINYINT NOT NULL, 
  id_level TINYINT NOT NULL,
  id_hacker TINYINT NOT NULL, 
  FOREIGN KEY (id_skill) REFERENCES skills(id_skill) ON UPDATE CASCADE ON DELETE CASCADE, 
  FOREIGN KEY (id_level) REFERENCES skill_level(id_level) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_hacker) REFERENCES user_credentials(id_hacker) ON UPDATE CASCADE ON DELETE CASCADE
);

-- PROGRAMMING LANGUAGES 
CREATE TABLE programming_lenguage_specialized (
  id_lenguage TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
  lenguage_name VARCHAR(20)
);

INSERT INTO programming_lenguage_specialized (lenguage_name) VALUES
('Python'),
('C'), 
('JavaScript'), 
('PHP'), 
('SQL'), 
('Ruby'), 
('Java'), 
('C#'), 
('Go'), 
('Rust'), 
('Swift'), 
('Kotlin'), 
('HTML/CSS'), 
('Bash/Shell'), 
('Perl'),
('Pseint'), 
('Raptor'),
('Assembly');

-- Language-User Relationship (WITH CASCADE DELETE)
CREATE TABLE programming_users_specialized (
	id_lenguage_source TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_hacker TINYINT NOT NULL,
    id_lenguage TINYINT NOT NULL,
    FOREIGN KEY (id_hacker) REFERENCES user_credentials(id_hacker) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_lenguage) REFERENCES programming_lenguage_specialized(id_lenguage) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Academy Names Table
CREATE TABLE academy_names (
  id_academy TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  academy_name VARCHAR(20) DEFAULT NULL
);

INSERT INTO academy_names (academy_name) VALUES 
('Freelancer'),
('Platzi'),
('Udemy'),
('Coursera'),
('edX'),
('FreeCodeCamp'),
('Codecademy');

-- Academy-User Relationship (WITH CASCADE DELETE)
CREATE TABLE academy_users (
	id_record TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_academy TINYINT NOT NULL,
    id_hacker TINYINT NOT NULL,
	FOREIGN KEY (id_hacker) REFERENCES user_credentials(id_hacker) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_academy) REFERENCES academy_names(id_academy) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Hacker Profile (WITH CASCADE DELETE)
CREATE TABLE hacker_users (
  id_record TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_hacker TINYINT NOT NULL,
  FOREIGN KEY (id_hacker) REFERENCES user_credentials(id_hacker) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Profile Images Table (WITH CASCADE DELETE)
CREATE TABLE profileimage (
  id_profile TINYINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id TINYINT NOT NULL,
  img_profile VARCHAR(100) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user_credentials(id_hacker) ON UPDATE CASCADE ON DELETE CASCADE
);

-- User Images Table (WITH CASCADE DELETE)
CREATE TABLE userimages (
  id_image INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_hacker TINYINT NOT NULL,
  image_path VARCHAR(100) DEFAULT NULL,
  FOREIGN KEY (id_hacker) REFERENCES user_credentials(id_hacker) ON UPDATE CASCADE ON DELETE CASCADE
);

-- ================================================================
-- TRIGGER: Automatically create hacker_user when credential is inserted
-- ================================================================

DELIMITER $$

CREATE TRIGGER auto_create_hacker_profile
AFTER INSERT ON user_credentials
FOR EACH ROW
BEGIN
    -- Automatically create hacker profile with default values
    INSERT INTO hacker_users (id_hacker) 
    VALUES (NEW.id_hacker);
END$$

DELIMITER ;

-- ================================================================
-- DATA INSERTION 
-- ================================================================
INSERT INTO user_credentials (hacker_name, password) 
VALUES ('b1t5', 'd74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1'),
('l33tH4ck3r', 'd74ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1');

INSERT INTO profetion_users(id_profetion, id_hacker) VALUES (1,1), (2,2);

INSERT INTO skill_user (id_skill, id_level, id_hacker) VALUES (1,2,1);

INSERT INTO academy_users (id_academy, id_hacker) VALUES (3,1);

INSERT INTO programming_users_specialized (id_hacker, id_lenguage) VALUES (1,1);

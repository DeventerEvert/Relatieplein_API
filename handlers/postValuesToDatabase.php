<?php
include_once('../config/DBconfig.php');

class postUserValues 
{
    private PDO $connection;
    public function __construct() {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function saveImage($data) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO image (Profile_User_idUser, image_file_path, image_file_name, image_file_type) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['Profile_User_idUser'],
                $data['image_file_path'],
                $data['image_file_name'],
                $data['image_file_type']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error saving image: " . $e->getMessage());
            echo "Error saving image: " . $e->getMessage(); 
            return false;
        }
    }

    public function saveHobbies($data) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO profile (user_id, description, fun_fact, province, fav_color, fav_animal, fav_season, starsign, hobby_description, occupation, green_flag, red_flag) VALUES (?, ?, ?, ?, ?, ? , ? , ? , ? , ? , ? , ?)");
            $stmt->execute([
                $data['user_id'],
                $data['description'],
                $data['fun_fact'],
                $data['province'],
                $data['fav_color'],
                $data['fav_animal'],
                $data['fav_season'],
                $data['starsign'],
                $data['hobby_description'],
                $data['occupation'],
                $data['green_flag'],
                $data['red_flag']
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error saving profile value: " . $e->getMessage());
            echo "Error saving profile value: " . $e->getMessage(); 
            return false;
        }
    }

    public function saveSpecialField($data) {
        try {
            $stmt = $this->connection->prepare("INSERT INTO special_field (Profile_User_idUser, field_title, field_content) VALUES (?, ?, ?)");
            $stmt->execute([
                $data['Profile_User_idUser'],
                $data['field_title'],
                $data['field_content'] 
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error saving special field: " . $e->getMessage());
            echo "Error saving special field: " . $e->getMessage(); 
            return false;
        }
    }
}
?>
<?php
require_once '../config/DBconfig.php';

class API {

    private PDO $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function registerSwipe($userId, $swipedUserId, $type) {
        if (!in_array($type, [0, 1])) {
            return ['status' => 400, 'message' => 'Invalid swipe direction'];
        }

        try {
            // Begin transactie
            $this->conn->beginTransaction();

            // Insert waardes van URL in swipe tabel
            $stmt = $this->conn->prepare('INSERT INTO swipe (swipe_swiper_user_id, swipe_swiped_user_id, swipe_type) VALUES (?, ?, ?)');
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->bindParam(2, $swipedUserId, PDO::PARAM_INT);
            $stmt->bindParam(3, $type, PDO::PARAM_INT);
            $stmt->execute();

  
            //We gaan kijken of 2 users matchen
            $matchFound = false;
            if ($type == 1) {
                $stmt = $this->conn->prepare('SELECT * FROM swipe WHERE swipe_swiped_user_id = ? AND swipe_swiper_user_id = ? AND swipe_type = ?');
                $stmt->bindParam(1, $userId, PDO::PARAM_INT);
                $stmt->bindParam(2, $swipedUserId, PDO::PARAM_INT);
                $stmt->bindParam(3, $type, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch();

                if ($result) {
                    $matchFound = true;
                }
            }

            // Als 2 users matchen word dit in de match tabel geplaatst
            if ($matchFound) {
                $stmt = $this->conn->prepare("INSERT INTO `match` (match_user_one_id, match_user_two_id) VALUES (?, ?)");
                $stmt->bindParam(1, $userId, PDO::PARAM_INT);
                $stmt->bindParam(2, $swipedUserId, PDO::PARAM_INT);
                $stmt->execute();

                $this->conn->commit();
                // Als er een match is gevonden
                return ['status' => 202, 'message' => 'Match gevonden!'];
            }

            // Als er een swipe is gemaakt
            $this->conn->commit();
            return ['status' => 201, 'message' => 'Swipe goed aangemaakt'];
        } catch (Exception $e) {
            // Als er geen swipe is gemaakt
            $this->conn->rollBack();
            return ['status' => 500, 'message' => 'Swipe niet goed aangemaakt: ' . $e->getMessage()];
        }
    }
}
?>

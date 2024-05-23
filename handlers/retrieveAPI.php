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

    public function retrieveFromDatabase($singleImage, $allImage, $singleLooking_for, $allLooking_for, $singleMatch, $allMatch, $singleMessage, $allMessage, $singleProfile, $allProfile, $singleReport, $allReport, 
    $singleSexual_preference, $allSexual_preference, $singleSpecial_field, $allSpecial_field, $singleSwipe, $allSwipe, $singleUser, $allUser, $allImageByUser, $allMessageByUser)
    {
        $result = [];
        
        if($_SERVER["REQUEST_METHOD"] === "GET")
        {
            

            if($singleImage != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM image WHERE image_id = ?');
                $stmt->bindParam(1, $singleImage, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleImage'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allImage != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM image');
                $stmt->execute();
                $result['allImage'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleLooking_for != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM looking_for WHERE profile_id = ?');
                $stmt->bindParam(1, $singleLooking_for, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleLooking_for'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allLooking_for != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM looking_for');
                $stmt->execute();
                $result['allLooking_for'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleMatch != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM `match` WHERE match_id = ?');
                $stmt->bindParam(1, $singleMatch, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleMatch'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allMatch != null)
            {
                $stmt = $this->conn->prepare("SELECT * FROM `match`");
                $stmt->execute();
                $result['allMatch'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleMessage != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM "message" WHERE message_id = ?');
                $stmt->bindParam(1, $singleMessage, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleMessage'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allMessage != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM "message"');
                $stmt->execute();
                $result['allMessage'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleProfile != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM "profile" WHERE user_id = ?');
                $stmt->bindParam(1, $singleProfile, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleProfile'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allProfile != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM "profile"');
                $stmt->execute();
                $result['allProfile'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleReport != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM report WHERE report_id = ?');
                $stmt->bindParam(1, $singleReport, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleReport'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allReport != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM report');
                $stmt->execute();
                $result['allReport'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleSexual_preference != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM sexual_preference WHERE profile_id = ?');
                $stmt->bindParam(1, $singleSexual_preference, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleSexual_preference'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allSexual_preference != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM sexual_preference');
                $stmt->execute();
                $result['allSexual_preference'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleSpecial_field != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM special_field WHERE field_id = ?');
                $stmt->bindParam(1, $singleSpecial_field, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleSpecial_field'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allSpecial_field != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM special_field');
                $stmt->execute();
                $result['allSpecial_field'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleSwipe != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM swipe WHERE swipe_swiper_user_id = ?');
                $stmt->bindParam(1, $singleSwipe, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleSwipe'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allSwipe != null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM swipe');
                $stmt->execute();
                $result['allSwipe'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($singleUser !== null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM user WHERE user_id = ?');
                $stmt->bindParam(1, $singleUser, PDO::PARAM_INT);
                $stmt->execute();
                $result['singleUser'] = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if($allUser !== null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM user');
                $stmt->execute();
                $result['allUser'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($allImageByUser !== null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM image WHERE Profile_User_idUser = ?');
                $stmt->bindParam(1, $allImageByUser, PDO::PARAM_INT);
                $stmt->execute();
                $result['allImageByUser'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if($allMessageByUser !== null)
            {
                $stmt = $this->conn->prepare('SELECT * FROM message WHERE message_id = ?');
                $stmt->bindParam(1, $allMessageByUser, PDO::PARAM_INT);
                $stmt->execute();
                $result['allMessageByUser'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

                return $result;
    }
}


}
?>

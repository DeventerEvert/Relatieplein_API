<?php
require_once '../config/DBconfig.php';

class API {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function registerSwipe($userId, $swipedUserId, $type) {
        if (!in_array($type, [0, 1])) {
            return ['status' => 400, 'message' => 'Invalid swipe direction'];
        } 

        try {
            //Van deze functie meerder functies maken om het netjes te houden
            // Begin transactie
            $this->conn->beginTransaction();

            // Insert values into swipe table
            $stmt = $this->conn->prepare('INSERT INTO swipe (swipe_swiper_user_id, swipe_swiped_user_id, swipe_type) VALUES (?, ?, ?)');
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->bindParam(2, $swipedUserId, PDO::PARAM_INT);
            $stmt->bindParam(3, $type, PDO::PARAM_INT);
            $stmt->execute();

            // Check if users match
            $matchFound = false;
            if ($type == 1) {
                $stmt = $this->conn->prepare('SELECT * FROM swipe WHERE swipe_swiped_user_id = ? AND swipe_swiper_user_id = ? AND swipe_type = 1');
                $stmt->bindParam(1, $userId, PDO::PARAM_INT);
                $stmt->bindParam(2, $swipedUserId, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch();

                if ($result) {
                    $matchFound = true;
                }
            }

            // If users match, insert into match table
            if ($matchFound) {
                $stmt = $this->conn->prepare("INSERT INTO `match` (match_user_one_id, match_user_two_id) VALUES (?, ?)");
                $stmt->bindParam(1, $userId, PDO::PARAM_INT);
                $stmt->bindParam(2, $swipedUserId, PDO::PARAM_INT);
                $stmt->execute();
                $this->conn->commit();
                return ['status' => 202, 'message' => 'Match found!'];
            }

            // Commit transaction if no match found
            $this->conn->commit();
            return ['status' => 201, 'message' => 'Swipe successfully registered'];
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollBack();
            return ['status' => 500, 'message' => 'Swipe registration failed: ' . $e->getMessage()];
        }
    }






    //Retrieve functions for database
    //In url try the following for results: view-source:http://localhost/Level 10/Relatieplein_API/Relatieplein_API/app/fetchAPI.php?allSwipe the part after the ? can be changed with any of the values below for different results..!



    public function retrieveImage($singleImage, $allImage, $allImageByUser)
    {
        $result = [];

        if($singleImage != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM image WHERE image_id = ?');
            $stmt->bindParam(1, $singleImage, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleImage'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allImage != false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM image');
            $stmt->execute();
            $result['allImage'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if($allImageByUser !== null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM image WHERE Profile_User_idUser = ?');
            $stmt->bindParam(1, $allImageByUser, PDO::PARAM_INT);
            $stmt->execute();
            $result['allImageByUser'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function retrieveLooking_for($singleLooking_for, $allLooking_for)
    {
        $result = [];

        if($singleLooking_for != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM looking_for WHERE profile_id = ?');
            $stmt->bindParam(1, $singleLooking_for, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleLooking_for'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allLooking_for != false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM looking_for');
            $stmt->execute();
            $result['allLooking_for'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function retrieveMatch($singleMatch, $allMatch)
    {
        $result = [];

        if($singleMatch != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM `match` WHERE match_id = ?');
            $stmt->bindParam(1, $singleMatch, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleMatch'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allMatch != false)
        {
            $stmt = $this->conn->prepare("SELECT * FROM `match`");
            $stmt->execute();
            $result['allMatch'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function retrieveMessage($singleMessage, $allMessage, $allMessageByUser)
    {
        $result = [];

        if($singleMessage != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM `message` WHERE message_id = ?');
            $stmt->bindParam(1, $singleMessage, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleMessage'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allMessage != false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM `message`');
            $stmt->execute();
            $result['allMessage'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        
        if($allMessageByUser !== null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM message WHERE sender_id = ?');
            $stmt->bindParam(1, $allMessageByUser, PDO::PARAM_INT);
            $stmt->execute();
            $result['allMessageByUser'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function retrieveProfile($singleProfile, $allProfile)
    {
        $result = [];

        if($singleProfile != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM `profile` WHERE user_id = ?');
            $stmt->bindParam(1, $singleProfile, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleProfile'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allProfile != false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM `profile`');
            $stmt->execute();
            $result['allProfile'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function retrieveReport($singleReport, $allReport)
    {
        $result = [];

        if($singleReport != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM report WHERE report_id = ?');
            $stmt->bindParam(1, $singleReport, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleReport'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allReport != false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM report');
            $stmt->execute();
            $result['allReport'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function retrieveSexual_preference($singleSexual_preference, $allSexual_preference)
    {
        $result = [];

        if($singleSexual_preference != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM sexual_preference WHERE profile_id = ?');
            $stmt->bindParam(1, $singleSexual_preference, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleSexual_preference'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allSexual_preference != false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM sexual_preference');
            $stmt->execute();
            $result['allSexual_preference'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function retrieveSpecial_field($singleSpecial_field, $allSpecial_field)
    {
        $result = [];

        if($singleSpecial_field != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM special_field WHERE field_id = ?');
            $stmt->bindParam(1, $singleSpecial_field, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleSpecial_field'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allSpecial_field != false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM special_field');
            $stmt->execute();
            $result['allSpecial_field'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function retrieveSwipe($singleSwipe, $allSwipe)
    {
        $result = [];

        if($singleSwipe != null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM swipe WHERE swipe_swiper_user_id = ?');
            $stmt->bindParam(1, $singleSwipe, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleSwipe'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allSwipe !== false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM swipe');
            $stmt->execute();
            $result['allSwipe'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        return $result;
    }

    public function retrieveUser($singleUser, $allUser)
    {
        if($singleUser !== null)
        {
            $stmt = $this->conn->prepare('SELECT * FROM user WHERE user_id = ?');
            $stmt->bindParam(1, $singleUser, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleUser'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if($allUser != false)
        {
            $stmt = $this->conn->prepare('SELECT * FROM user');
            $stmt->execute();
            $result['allUser'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }





    //Function for inserting a message into a database, this has been tried and tested with postman and as long as the necessary tables are filled will work!

            public function insertIntoDatabase($match_id, $sender_id, $message, $message_liked, $replied_message_at){

                try {
                    // Begin transactie
                    $this->conn->beginTransaction();
        
                    // Insert waardes van URL in de message tabel
                    $stmt = $this->conn->prepare('INSERT INTO message (match_id, sender_id, message, message_liked, replied_message_id) VALUES (?, ?, ?, ?, ?)');
                    $stmt->bindParam(1, $match_id, PDO::PARAM_INT);
                    $stmt->bindParam(2, $sender_id, PDO::PARAM_INT);
                    $stmt->bindParam(3, $message, PDO::PARAM_STR);
                    $stmt->bindParam(4, $message_liked, PDO::PARAM_INT);
                    $stmt->bindParam(5, $replied_message_at, PDO::PARAM_STR);
                    $stmt->execute();
        
                    // Als er een swipe is gemaakt
                    $this->conn->commit();
                    return ['status' => 201, 'message' => 'Message goed aangemaakt'];
                } catch (Exception $e) {
                    // Als er geen swipe is gemaakt
                    $this->conn->rollBack();
                    return ['status' => 500, 'message' => 'Message niet goed aangemaakt: ' . $e->getMessage()];
  
                }
            }

}

?>

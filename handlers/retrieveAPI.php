<?php
require_once '../config/DBconfig.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Expose-Headers: X-Custom-Header");
header("Access-Control-Max-Age: 86400");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
class API
{
    private $conn;
    private $last_inserted_id;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function registerSwipe($userId, $swipedUserId, $type)
    {
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

        if ($singleImage != null) {
            $stmt = $this->conn->prepare('SELECT * FROM image WHERE image_id = ?');
            $stmt->bindParam(1, $singleImage, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleImage'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allImage != false) {
            $stmt = $this->conn->prepare('SELECT * FROM image');
            $stmt->execute();
            $result['allImage'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if ($allImageByUser !== null) {
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

        if ($singleLooking_for != null) {
            $stmt = $this->conn->prepare('SELECT * FROM looking_for WHERE profile_id = ?');
            $stmt->bindParam(1, $singleLooking_for, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleLooking_for'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allLooking_for != false) {
            $stmt = $this->conn->prepare('SELECT * FROM looking_for');
            $stmt->execute();
            $result['allLooking_for'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function retrieveMatch($singleMatch, $allMatch)
    {
        $result = [];

        if ($singleMatch != null) {
            $stmt = $this->conn->prepare('SELECT * FROM `match` WHERE match_id = ?');
            $stmt->bindParam(1, $singleMatch, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleMatch'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allMatch != false) {
            $stmt = $this->conn->prepare("SELECT * FROM `match`");
            $stmt->execute();
            $result['allMatch'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function retrieveMessage($singleMessage, $allMessage, $allMessageByUser)
    {
        $result = [];

        if ($singleMessage != null) {
            $stmt = $this->conn->prepare('SELECT * FROM `message` WHERE message_id = ?');
            $stmt->bindParam(1, $singleMessage, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleMessage'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allMessage != false) {
            $stmt = $this->conn->prepare('SELECT * FROM `message`');
            $stmt->execute();
            $result['allMessage'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        if ($allMessageByUser !== null) {
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

        if ($singleProfile != null) {
            $stmt = $this->conn->prepare('SELECT * FROM `profile` WHERE user_id = ?');
            $stmt->bindParam(1, $singleProfile, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleProfile'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allProfile != false) {
            $stmt = $this->conn->prepare('SELECT * FROM `profile`');
            $stmt->execute();
            $result['allProfile'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function retrieveReport($singleReport, $allReport)
    {
        $result = [];

        if ($singleReport != null) {
            $stmt = $this->conn->prepare('SELECT * FROM report WHERE report_id = ?');
            $stmt->bindParam(1, $singleReport, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleReport'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allReport != false) {
            $stmt = $this->conn->prepare('SELECT * FROM report');
            $stmt->execute();
            $result['allReport'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function retrieveSexual_preference($singleSexual_preference, $allSexual_preference)
    {
        $result = [];

        if ($singleSexual_preference != null) {
            $stmt = $this->conn->prepare('SELECT * FROM sexual_preference WHERE profile_id = ?');
            $stmt->bindParam(1, $singleSexual_preference, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleSexual_preference'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allSexual_preference != false) {
            $stmt = $this->conn->prepare('SELECT * FROM sexual_preference');
            $stmt->execute();
            $result['allSexual_preference'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function retrieveSpecial_field($singleSpecial_field, $allSpecial_field)
    {
        $result = [];

        if ($singleSpecial_field != null) {
            $stmt = $this->conn->prepare('SELECT * FROM special_field WHERE field_id = ?');
            $stmt->bindParam(1, $singleSpecial_field, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleSpecial_field'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allSpecial_field != false) {
            $stmt = $this->conn->prepare('SELECT * FROM special_field');
            $stmt->execute();
            $result['allSpecial_field'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function retrieveSwipe($singleSwipe, $allSwipe)
    {
        $result = [];

        if ($singleSwipe != null) {
            $stmt = $this->conn->prepare('SELECT * FROM swipe WHERE swipe_swiper_user_id = ?');
            $stmt->bindParam(1, $singleSwipe, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleSwipe'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allSwipe !== false) {
            $stmt = $this->conn->prepare('SELECT * FROM swipe');
            $stmt->execute();
            $result['allSwipe'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function retrieveUser($singleUser, $allUser)
    {
        if ($singleUser !== null) {
            $stmt = $this->conn->prepare('SELECT * FROM user WHERE user_id = ?');
            $stmt->bindParam(1, $singleUser, PDO::PARAM_INT);
            $stmt->execute();
            $result['singleUser'] = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if ($allUser != false) {
            $stmt = $this->conn->prepare('SELECT * FROM user');
            $stmt->execute();
            $result['allUser'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $result;
    }

    //function for returning true or false on a database email check
    function sanitize($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    public function login($email, $password)
    {
        $email = $this->sanitize($email);
        $password = $this->sanitize($password);

        $stmt = $this->conn->prepare("SELECT user_id, password FROM user WHERE email = ?");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                return ["success" => true, "user_id" => $user['user_id']];
            } else {
                http_response_code(401);
                return ["success" => false];
            }
        } else {
            http_response_code(401);
            return ["success" => false];
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);

            if ($this->isValidInput($input)) {
                $firstName = $input['firstName'];
                $lastName = $input['lastName'];
                $birthdate = $input['birthdate'];
                $email = $input['emailRegister'];
                $password = $input['passwordRegister'];
                $gender = $input['gender'];
                $active = $input['active'];
                $ghost_mode = $input['ghost_mode'];

                // Hash the password before storing
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                try {
                    // Begin transaction
                    $this->conn->beginTransaction();

                    // Prepare SQL statement
                    $stmt = $this->conn->prepare('INSERT INTO user (firstname, lastname, birthdate, email, password, gender, active, ghost_mode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                    $stmt->bindParam(1, $firstName, PDO::PARAM_STR);
                    $stmt->bindParam(2, $lastName, PDO::PARAM_STR);
                    $stmt->bindParam(3, $birthdate, PDO::PARAM_STR);
                    $stmt->bindParam(4, $email, PDO::PARAM_STR);
                    $stmt->bindParam(5, $hashedPassword, PDO::PARAM_STR);
                    $stmt->bindParam(6, $gender, PDO::PARAM_STR);
                    $stmt->bindParam(7, $active, PDO::PARAM_BOOL);
                    $stmt->bindParam(8, $ghost_mode, PDO::PARAM_BOOL);
                    $stmt->execute();

                    // Commit transaction
                    $this->conn->commit();
                    $this->sendResponse(201, ['status' => 201, 'message' => 'Register successful']);
                } catch (Exception $e) {
                    // Rollback transaction
                    $this->conn->rollBack();
                    $this->sendResponse(500, ['status' => 500, 'message' => 'Register failed: ' . $e->getMessage()]);
                }
            } else {
                $this->sendResponse(400, ['status' => 400, 'message' => 'All fields are required']);
            }
        } else {
            $this->sendResponse(405, ['status' => 405, 'message' => 'Method not allowed']);
        }
    }

    private function isValidInput($input)
    {
        return isset($input['firstName'], $input['lastName'], $input['birthdate'], $input['emailRegister'], $input['passwordRegister'], $input['gender'], $input['active'], $input['ghost_mode']);
    }

    private function sendResponse($statusCode, $response)
    {
        http_response_code($statusCode);
        echo json_encode($response);
    }


    //Function for inserting a message into a database, this has been tried and tested with postman and as long as the necessary tables are filled will work!
    public function insertMessageIntoDatabase($match_id, $sender_id, $receiver_id, $message, $message_liked)
    {
        try {
            // Begin transactie
            $this->conn->beginTransaction();

            // Insert waardes van URL in de message tabel
            $stmt = $this->conn->prepare('INSERT INTO message (match_id, sender_id, receiver_id, message, message_liked) VALUES (?, ?, ?, ?, ?)');
            $stmt->bindParam(1, $match_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $sender_id, PDO::PARAM_INT);
            $stmt->bindParam(3, $receiver_id, PDO::PARAM_INT);
            $stmt->bindParam(4, $message, PDO::PARAM_STR);
            $stmt->bindParam(5, $message_liked, PDO::PARAM_INT);
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

    //New function for onboarding as by David
    public function insertOnboardingValues(
        $first_name,
        $last_name,
        $birthdate,
        $email,
        $hashedPassword,
        $gender,
        $selectedGenderPreferences,
        $description,
        $fun_fact,
        $province,
        $fav_color,
        $fav_animal,
        $fav_season,
        $emoji_description,
        $starsign,
        $hobby_description,
        $occupation,
        $green_flag,
        $red_flag,
        $selectedLookingFors,
        $image_file_path,
        $image_file_name,
        $image_file_type
    ) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->conn->beginTransaction();
    
                $stmtUser = $this->conn->prepare('INSERT INTO user (firstname, lastname, birthdate, email, password, gender) VALUES (?, ?, ?, ?, ?, ?)');
                $stmtUser->bindParam(1, $first_name, PDO::PARAM_STR);
                $stmtUser->bindParam(2, $last_name, PDO::PARAM_STR);
                $stmtUser->bindParam(3, $birthdate, PDO::PARAM_STR);
                $stmtUser->bindParam(4, $email, PDO::PARAM_STR);
                $stmtUser->bindParam(5, $hashedPassword, PDO::PARAM_STR);
                $stmtUser->bindParam(6, $gender, PDO::PARAM_STR);
                $stmtUser->execute();
    
                if ($stmtUser->rowCount() === 0) {
                    $this->conn->rollBack();
                    $this->sendResponse(500, ['status' => 500, 'message' => 'Failed to add onboarding']);
                    return;
                }
    
                $user_id = $this->conn->lastInsertId();
                $this->last_inserted_id = $user_id;
    
                $stmtProfile = $this->conn->prepare('INSERT INTO profile (user_id, description, fun_fact, province, fav_color, fav_animal, fav_season, emoji_description, starsign, hobby_description, occupation, green_flag, red_flag) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                $stmtProfile->bindParam(1, $this->last_inserted_id, PDO::PARAM_INT);
                $stmtProfile->bindParam(2, $description, PDO::PARAM_STR);
                $stmtProfile->bindParam(3, $fun_fact, PDO::PARAM_STR);
                $stmtProfile->bindParam(4, $province, PDO::PARAM_STR);
                $stmtProfile->bindParam(5, $fav_color, PDO::PARAM_STR);
                $stmtProfile->bindParam(6, $fav_animal, PDO::PARAM_STR);
                $stmtProfile->bindParam(7, $fav_season, PDO::PARAM_STR);
                $stmtProfile->bindParam(8, $emoji_description, PDO::PARAM_STR);
                $stmtProfile->bindParam(9, $starsign, PDO::PARAM_STR);
                $stmtProfile->bindParam(10, $hobby_description, PDO::PARAM_STR);
                $stmtProfile->bindParam(11, $occupation, PDO::PARAM_STR);
                $stmtProfile->bindParam(12, $green_flag, PDO::PARAM_STR);
                $stmtProfile->bindParam(13, $red_flag, PDO::PARAM_STR);
                $stmtProfile->execute();
    
                if ($stmtProfile->rowCount() === 0) {
                    $this->conn->rollBack();
                    $this->sendResponse(500, ['status' => 500, 'message' => 'Failed to add onboarding']);
                    return;
                }
    
                $male = $female = $non_binary = $male_to_female = $female_to_male = $other = 0;
    
                foreach ($selectedGenderPreferences as $preference) {
                    switch ($preference) {
                        case 'male':
                            $male = 1;
                            break;
                        case 'female':
                            $female = 1;
                            break;
                        case 'non-binary':
                            $non_binary = 1;
                            break;
                        case 'male_to_female':
                            $male_to_female = 1;
                            break;
                        case 'female_to_male':
                            $female_to_male = 1;
                            break;
                        case 'other':
                            $other = 1;
                            break;
                    }
                }
    
                // Insert into `sexual_preference` table
                $stmtPreference = $this->conn->prepare('INSERT INTO sexual_preference (profile_id, male, female, `non-binary`, male_to_female, female_to_male, other) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmtPreference->bindParam(1, $this->last_inserted_id, PDO::PARAM_INT);
                $stmtPreference->bindParam(2, $male, PDO::PARAM_INT);
                $stmtPreference->bindParam(3, $female, PDO::PARAM_INT);
                $stmtPreference->bindParam(4, $non_binary, PDO::PARAM_INT);
                $stmtPreference->bindParam(5, $male_to_female, PDO::PARAM_INT);
                $stmtPreference->bindParam(6, $female_to_male, PDO::PARAM_INT);
                $stmtPreference->bindParam(7, $other, PDO::PARAM_INT);
                $stmtPreference->execute();
    
                $relationship = $fwb = $pen_pal = $friends = 0;
    
                foreach ($selectedLookingFors as $lookingFor) {
                    switch ($lookingFor) {
                        case 'relationship':
                            $relationship = 1;
                            break;
                        case 'fwb':
                            $fwb = 1;
                            break;
                        case 'pen-pal':
                            $pen_pal = 1;
                            break;
                        case 'friends':
                            $friends = 1;
                            break;
                    }
                }
    
                // Insert into `looking_for` table
                $stmtLookingFor = $this->conn->prepare('INSERT INTO looking_for (profile_id, relationship, fwb, pen_pal, friends) VALUES (?, ?, ?, ?, ?)');
                $stmtLookingFor->bindParam(1, $this->last_inserted_id, PDO::PARAM_INT);
                $stmtLookingFor->bindParam(2, $relationship, PDO::PARAM_INT);
                $stmtLookingFor->bindParam(3, $fwb, PDO::PARAM_INT);
                $stmtLookingFor->bindParam(4, $pen_pal, PDO::PARAM_INT);
                $stmtLookingFor->bindParam(5, $friends, PDO::PARAM_INT);
                $stmtLookingFor->execute();
    
                // Insert into `image` table
                $stmtImage = $this->conn->prepare('INSERT INTO image (Profile_User_idUser, image_file_path, image_file_name, image_file_type) VALUES (?, ?, ?, ?)');
                $stmtImage->bindParam(1, $this->last_inserted_id, PDO::PARAM_INT);
                $stmtImage->bindParam(2, $image_file_path, PDO::PARAM_STR);
                $stmtImage->bindParam(3, $image_file_name, PDO::PARAM_STR);
                $stmtImage->bindParam(4, $image_file_type, PDO::PARAM_STR);
                $stmtImage->execute();

                $this->conn->commit();
                $this->sendResponse(201, ['status' => 201, 'message' => 'Onboarding successfully added', 'Last inserted id:' => $this->last_inserted_id]);
            } catch (Exception $e) {
                $this->conn->rollBack();
                $this->sendResponse(500, ['status' => 500, 'message' => 'Failed to add onboarding: ' . $e->getMessage()]);
            }
        } else {
            $this->sendResponse(400, ['status' => 400, 'message' => 'Invalid request method']);
        }
    }



    //New function for adding profile into the database

    public function insertProfileIntoDatabase($user_id, $description, $fun_fact, $province, $fav_color, $fav_animal, $fav_season, $emoji_description, $starsign, $hobby_description, $occupation, $green_flag, $red_flag)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->conn->beginTransaction();
                $stmt = $this->conn->prepare('INSERT INTO profile (`user_id`, `description`, `fun_fact`, `province`, `fav_color`, `fav_animal`, `fav_season`, `emoji_description`, `starsign`, `hobby_description`, `occupation`, `green_flag`, `red_flag`) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

                $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $description, PDO::PARAM_STR);
                $stmt->bindParam(3, $fun_fact, PDO::PARAM_STR);
                $stmt->bindParam(4, $province, PDO::PARAM_STR);
                $stmt->bindParam(5, $fav_color, PDO::PARAM_STR);
                $stmt->bindParam(6, $fav_animal, PDO::PARAM_STR);
                $stmt->bindParam(7, $fav_season, PDO::PARAM_STR);
                $stmt->bindParam(8, $emoji_description, PDO::PARAM_STR);
                $stmt->bindParam(9, $starsign, PDO::PARAM_STR);
                $stmt->bindParam(10, $hobby_description, PDO::PARAM_STR);
                $stmt->bindParam(11, $occupation, PDO::PARAM_STR);
                $stmt->bindParam(12, $green_flag, PDO::PARAM_STR);
                $stmt->bindParam(13, $red_flag, PDO::PARAM_STR);
                $stmt->execute();

                $this->conn->commit();

                $this->sendResponse(201, ['status' => 201, 'message' => 'Profiel succesvol toegevoegd']);
            } catch (Exception $e) {
                $this->conn->rollBack();
                $this->sendResponse(500, ['status' => 500, 'message' => 'Kon profiel niet toevoegen: ' . $e->getMessage()]);
            }
        }
    }

    //Function for editing user
    public function editUser($firstname, $lastname, $birthdate, $email, $hashedPassword, $gender, $user_id){
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            try {
                $this->conn->beginTransaction();
                $stmt = $this->conn->prepare('UPDATE user SET firstname = ?, lastname = ?, birthdate = ?, email = ?, password = ?, gender = ?, changed_at = NOW() WHERE user_id = ?;');
                $stmt->bindParam(1, $firstname, PDO::PARAM_STR);
                $stmt->bindParam(2, $lastname, PDO::PARAM_STR);
                $stmt->bindParam(3, $birthdate, PDO::PARAM_STR);
                $stmt->bindParam(4, $email, PDO::PARAM_STR);
                $stmt->bindParam(5, $hashedPassword, PDO::PARAM_STR);
                $stmt->bindParam(6, $gender, PDO::PARAM_STR);
                $stmt->bindParam(7, $user_id, PDO::PARAM_STR);
                $stmt->execute();
    
                $this->conn->commit(); // Commit the transaction after successful delete
    
                $this->sendResponse(200, ['status' => 200, 'message' => 'user geupdate']);
            } catch (Exception $e) {
                $this->conn->rollBack();
                $this->sendResponse(500, ['status' => 500, 'message' => 'Kon user niet updaten: ' . $e->getMessage()]);
            }
        } else {
            $this->sendResponse(405, ['status' => 405, 'message' => 'Method Not Allowed']);
        }
    }
    //Function for editing profiles
    public function editProfile($description, $fun_fact, $province, $fav_color, $fav_animal, $fav_season, $emoji_description, $starsign, $hobby_description, $occupation, $green_flag, $red_flag, $user_id){
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            try {
                $this->conn->beginTransaction();
                $stmt = $this->conn->prepare('UPDATE profile SET `description` = ?, fun_fact = ?, province = ?, fav_color = ?, fav_animal = ?, fav_season = ?, emoji_description = ?,
                starsign = ?, hobby_description = ?, occupation = ?, green_flag = ?, red_flag = ?  WHERE user_id = ?;');
                $stmt->bindParam(1, $description, PDO::PARAM_INT);
                $stmt->bindParam(2, $fun_fact, PDO::PARAM_STR);
                $stmt->bindParam(3, $province, PDO::PARAM_STR);
                $stmt->bindParam(4, $fav_color, PDO::PARAM_STR);
                $stmt->bindParam(5, $fav_animal, PDO::PARAM_STR);
                $stmt->bindParam(6, $fav_season, PDO::PARAM_STR);
                $stmt->bindParam(7, $emoji_description, PDO::PARAM_STR);
                $stmt->bindParam(8, $starsign, PDO::PARAM_STR);
                $stmt->bindParam(9, $hobby_description, PDO::PARAM_STR);
                $stmt->bindParam(10, $occupation, PDO::PARAM_STR);
                $stmt->bindParam(11, $green_flag, PDO::PARAM_STR);
                $stmt->bindParam(12, $red_flag, PDO::PARAM_STR);
                $stmt->bindParam(13, $user_id, PDO::PARAM_STR);
                $stmt->execute();
    
                $this->conn->commit(); // Commit the transaction after successful delete
    
                $this->sendResponse(200, ['status' => 200, 'message' => 'Profiel geupdate']);
            } catch (Exception $e) {
                $this->conn->rollBack();
                $this->sendResponse(500, ['status' => 500, 'message' => 'Kon profiel niet updaten: ' . $e->getMessage()]);
            }
        } else {
            $this->sendResponse(405, ['status' => 405, 'message' => 'Method Not Allowed']);
        }
    }
    //Function for uploading into image
    public function uploadImage($Profile_User_idUser, $image_path, $image_name, $image_type){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->conn->beginTransaction();
                $stmt = $this->conn->prepare('INSERT INTO `image` (Profile_User_idUser, image_file_path, image_file_name, image_file_type) VALUES (?, ?, ?, ?)');
                $stmt->bindParam(1, $Profile_User_idUser, PDO::PARAM_INT);
                $stmt->bindParam(2, $image_path, PDO::PARAM_STR);
                $stmt->bindParam(3, $image_name, PDO::PARAM_STR);
                $stmt->bindParam(4, $image_type, PDO::PARAM_STR);
                $stmt->execute();
    
                $this->conn->commit(); // Commit the transaction after successful delete
    
                $this->sendResponse(200, ['status' => 200, 'message' => 'Image geupload']);
            } catch (Exception $e) {
                $this->conn->rollBack();
                $this->sendResponse(500, ['status' => 500, 'message' => 'Kon image niet uploaden: ' . $e->getMessage()]);
            }
        } else {
            $this->sendResponse(405, ['status' => 405, 'message' => 'Method Not Allowed']);
        }
    }
    //Function for deleting image
    public function deleteImage($image_id) {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            try {
                $this->conn->beginTransaction();
                $stmt = $this->conn->prepare('DELETE FROM `image` WHERE image_id = ?');
                $stmt->bindParam(1, $image_id, PDO::PARAM_INT);
                $stmt->execute();
    
                $this->conn->commit(); // Commit the transaction after successful delete
    
                $this->sendResponse(200, ['status' => 200, 'message' => 'Image verwijderd']);
            } catch (Exception $e) {
                $this->conn->rollBack();
                $this->sendResponse(500, ['status' => 500, 'message' => 'Kon image niet verwijderen: ' . $e->getMessage()]);
            }
        } else {
            $this->sendResponse(405, ['status' => 405, 'message' => 'Method Not Allowed']);
        }
    }
}

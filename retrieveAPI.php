<?php
require_once '../config/DBconfig.php';

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

    public function insertOnboardingValues(
        $first_name,
        $last_name,
        $birthdate,
        $email,
        $hashedPassword,
        $gender,
        $selectedGenderPreference,
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
        $selectedLookingFor,
        $image_file_path,
        $image_file_name,
        $image_file_type
    ) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Insert into `user` table
                $stmtUser = $this->conn->prepare('INSERT INTO user (firstname, lastname, birthdate, email, password, gender) VALUES (?, ?, ?, ?, ?, ?)');
                $stmtUser->bindParam(1, $first_name, PDO::PARAM_STR);
                $stmtUser->bindParam(2, $last_name, PDO::PARAM_STR);
                $stmtUser->bindParam(3, $birthdate, PDO::PARAM_STR);
                $stmtUser->bindParam(4, $email, PDO::PARAM_STR);
                $stmtUser->bindParam(5, $hashedPassword, PDO::PARAM_STR);
                $stmtUser->bindParam(6, $gender, PDO::PARAM_STR);
                $stmtUser->execute();

                // Check if user insert was successful
                if ($stmtUser->rowCount() === 0) {
                    $this->sendResponse(500, ['status' => 500, 'message' => 'Failed to add onboarding']);
                    return;
                }

                // Get the last inserted user_id
                $user_id = $this->conn->lastInsertId();
                $this->last_inserted_id = $user_id;

                // Insert into `profile` table
                $stmtProfile = $this->conn->prepare('INSERT INTO profile (`user_id`, `description`, `fun_fact`, `province`, `fav_color`, `fav_animal`, `fav_season`, `emoji_description`, `starsign`, `hobby_description`, `occupation`, `green_flag`, `red_flag`) 
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
                // Check if user insert was successful
                if ($stmtProfile->rowCount() === 0) {
                    $this->sendResponse(500, ['status' => 500, 'message' => 'Failed to add onboarding']);
                    return;
                }


                //Insert into sexual preference table
                // Determine column name for sexual_preference table
                switch ($selectedGenderPreference) {
                    case 'male':
                        $columnToUpdate = 'male';
                        break;
                    case 'female':
                        $columnToUpdate = 'female';
                        break;
                    case 'non-binary':
                        $columnToUpdate = 'non_binary';
                        break;
                    case 'male_to_female':
                        $columnToUpdate = 'male_to_female';
                        break;
                    case 'female_to_male':
                        $columnToUpdate = 'female_to_male';
                        break;
                    case 'other':
                        $columnToUpdate = 'other';
                        break;
                    default:
                        // Handle invalid input if necessary
                        $this->sendResponse(400, ['status' => 400, 'message' => 'Invalid gender preference']);
                        return;
                }

                // Insert into `sexual_preference` table
                $stmtPreference = $this->conn->prepare("INSERT INTO sexual_preference (profile_id, $columnToUpdate) VALUES (?, 1)");
                $stmtPreference->bindParam(1, $this->last_inserted_id, PDO::PARAM_INT);
                $stmtPreference->execute();


                switch ($selectedLookingFor) {
                    case 'relationship':
                        $LFcolumnToUpdate = 'relationship';
                        break;
                    case 'fwb':
                        $LFcolumnToUpdate = 'fwb';
                        break;
                    case 'pen-pal':
                        $LFcolumnToUpdate = 'pen_pal';
                        break;
                    case 'friends':
                        $LFcolumnToUpdate = 'friends';
                        break;
                    default:
                        // Handle invalid input if necessary
                        $this->sendResponse(400, ['status' => 400, 'message' => 'Invalid looking for preference']);
                        return;
                }
    
                // Insert into `looking_for` table
                $stmtLookingFor = $this->conn->prepare("INSERT INTO looking_for (profile_id, $LFcolumnToUpdate) VALUES (?, 1)");
                $stmtLookingFor->bindParam(1, $this->last_inserted_id, PDO::PARAM_INT);
                $stmtLookingFor->execute();

                // Insert into `image` table
                $stmtImage = $this->conn->prepare('INSERT INTO image (Profile_User_idUser, image_file_path, image_file_name, image_file_type) VALUES (?, ?, ?, ?)');
                $stmtImage->bindParam(1, $this->last_inserted_id, PDO::PARAM_INT);
                $stmtImage->bindParam(2, $image_file_path, PDO::PARAM_STR);
                $stmtImage->bindParam(3, $image_file_name, PDO::PARAM_STR);
                $stmtImage->bindParam(4, $image_file_type, PDO::PARAM_STR);
                $stmtImage->execute();

                // Send success response
                $this->sendResponse(201, ['status' => 201, 'message' => 'Onboarding successfully added']);
            } catch (Exception $e) {
                // Rollback the transaction on failure
                $this->sendResponse(500, ['status' => 500, 'message' => 'Failed to add onboarding: ' . $e->getMessage()]);
            }
        } else {
            // Handle invalid request method
            $this->sendResponse(400, ['status' => 400, 'message' => 'Invalid request method']);
        }
    }




    //New function for adding profile into the database

    public function insertProfileIntoDatabase($user_id, $description, $fun_fact, $province, $fav_color, $fav_animal, $fav_season, $emoji_description, $starsign, $hobby_description, $occupation, $green_flag, $red_flag)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Begin transaction
                $this->conn->beginTransaction();

                // Prepare the SQL query
                $stmt = $this->conn->prepare('INSERT INTO profile (`user_id`, `description`, `fun_fact`, `province`, `fav_color`, `fav_animal`, `fav_season`, `emoji_description`, `starsign`, `hobby_description`, `occupation`, `green_flag`, `red_flag`) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

                // Bind parameters
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
                // Execute the statement
                $stmt->execute();

                // Commit transaction if successful
                $this->conn->commit();

                $this->sendResponse(201, ['status' => 201, 'message' => 'Profiel succesvol toegevoegd']);
            } catch (Exception $e) {
                // Rollback transaction on failure
                $this->conn->rollBack();
                $this->sendResponse(500, ['status' => 500, 'message' => 'Kon profiel niet toevoegen: ' . $e->getMessage()]);
            }
        }
    }

    //New function for onboarding as by David
}

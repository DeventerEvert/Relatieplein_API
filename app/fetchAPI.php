<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Expose-Headers: X-Custom-Header");
header("Access-Control-Max-Age: 86400");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require '../config/DBconfig.php';
require '../handlers/retrieveAPI.php';

header('Content-Type: application/json');

//Underneath values refer to registerSwipe function of retrieveAPI.php and insertIntoDatabase function of retrieveAPI.php
$input = json_decode(file_get_contents('php://input'), true);

$userId = isset($input['user_id']) ? (int) $input['user_id'] : null;
$swipedUserId = isset($input['swiped_user_id']) ? (int) $input['swiped_user_id'] : null;
$type = isset($input['type']) ? (int) $input['type'] : null;

$match_id = isset($input['match_id']) ? (int) $input['match_id'] : null;
$sender_id = isset($input['sender_id']) ? (int) $input['sender_id'] : null;
$receiver_id = isset($input['receiver_id']) ? (int) $input['receiver_id'] : null;
$message = isset($input['message']) ? $input['message'] : null;
$message_liked = isset($input['message_liked']) ? (int) $input['message_liked'] : null;
// $replied_message_id = isset($input['replied_message_id']) ? (int) $input['replied_message_id'] : null;

$swipeService = new API();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!is_null($userId) && !is_null($swipedUserId) && !is_null($type)) {
        $response = $swipeService->registerSwipe($userId, $swipedUserId, $type);
        http_response_code($response['status']);
        echo json_encode(['message' => $response['message']]);
    } elseif (!is_null($match_id) && !is_null($sender_id) && !is_null($receiver_id) && !is_null($message)) {
        $response = $swipeService->insertMessageIntoDatabase($match_id, $sender_id, $receiver_id, $message, $message_liked);
        http_response_code($response['status']);
        echo json_encode(['message' => $response['message']]);
    } elseif (isset($input['email']) && isset($input['password'])) {
        $input = json_decode(file_get_contents('php://input'), true);
        $response = $swipeService->login($input['email'], $input['password']);
        echo json_encode($response);
    } else {
        // http_response_code(400);
        // echo json_encode(['message' => 'Invalid input']);
    }
}

//Underneath values all refer to the retrieve functions from fetchAPI.php
//Voor ophalen van de Image waardes van de database
$singleImage = isset($_GET['singleImage']) ? (int) $_GET['singleImage'] : null;
$allImage = isset($_GET['allImage']);
$allImageByUser = isset($_GET['allImageByUser']) ? (int) $_GET['allImageByUser'] : null;


if (!is_null($singleImage) || ($allImage != false) || !is_null($allImageByUser)) {
    $result = $swipeService->retrieveImage($singleImage, $allImage, $allImageByUser); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Voor ophalen van de looking_for waardes van de database
$singleLooking_for = isset($_GET['singleLooking_for']) ? (int) $_GET['singleLooking_for'] : null;
$allLooking_for = isset($_GET['allLooking_for']);

if (!is_null($singleLooking_for) || ($allLooking_for != false)) {
    $result = $swipeService->retrieveLooking_for($singleLooking_for, $allLooking_for); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Voor ophalen van de match waardes van de database
$singleMatch = isset($_GET['singleMatch']) ? (int) $_GET['singleMatch'] : null;
$allMatch = isset($_GET['allMatch']);

if (!is_null($singleMatch) || ($allMatch != false)) {
    $result = $swipeService->retrieveMatch($singleMatch, $allMatch); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Voor ophalen van message waardes van de database
$singleMessage = isset($_GET['singleMessage']) ? (int) $_GET['singleMessage'] : null;
$allMessage = isset($_GET['allMessage']);
$allMessageByUser = isset($_GET['allMessageByUser']) ? (int) $_GET['allMessageByUser'] : null;

if (!is_null($singleMessage) || ($allMessage != false) || !is_null($allMessageByUser)) {
    $result = $swipeService->retrieveMessage($singleMessage, $allMessage, $allMessageByUser); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Voor ophalen van profile waardes van de database
$singleProfile = isset($_GET['singleProfile']) ? (int) $_GET['singleProfile'] : null;
$allProfile = isset($_GET['allProfile']);

if (!is_null($singleProfile) || ($allProfile != false)) {
    $result = $swipeService->retrieveProfile($singleProfile, $allProfile); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Voor ophalen van report waardes van de database
$singleReport = isset($_GET['singleReport']) ? (int) $_GET['singleReport'] : null;
$allReport = isset($_GET['allReport']);

if (!is_null($singleReport) || ($allReport != false)) {
    $result = $swipeService->retrieveReport($singleReport, $allReport); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Voor ophalen van de sexual_preference van de database
$singleSexual_preference = isset($_GET['singleSexual_preference']) ? (int) $_GET['singleSexual_preference'] : null;
$allSexual_preference = isset($_GET['allSexual_preference']);

if (!is_null($singleSexual_preference) || ($allSexual_preference != false)) {
    $result = $swipeService->retrieveSexual_preference($singleSexual_preference, $allSexual_preference); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Voor ophalen van de special_field values uit de database
$singleSpecial_field = isset($_GET['singleSpecial_field']) ? (int) $_GET['singleSpecial_field'] : null;
$allSpecial_field = isset($_GET['allSpecial_field']);

if (!is_null($singleSpecial_field) || ($allSpecial_field != false)) {
    $result = $swipeService->retrieveSpecial_field($singleSpecial_field, $allSpecial_field); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}


//Voor ophalen van de swipe values uit de database
$singleSwipe = isset($_GET['singleSwipe']) ? (int) $_GET['singleSwipe'] : null;
$allSwipe = isset($_GET['allSwipe']);

if (!is_null($singleSwipe) || ($allSwipe != false)) {
    $result = $swipeService->retrieveSwipe($singleSwipe, $allSwipe); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Voor ophalen van de user values uit de database
$singleUser = isset($_GET['singleUser']) ? (int) $_GET['singleUser'] : null;
$allUser = isset($_GET['allUser']);

if (!is_null($singleUser) || ($allUser != false)) {
    $result = $swipeService->retrieveUser($singleUser, $allUser); {
        http_response_code(200);
        if ($result != null || $result != false) {
            echo json_encode($result);
        }
        exit;
    }
}

//Register functie voor relatieplein
if (
    isset($input['firstName']) && isset($input['lastName']) && isset($input['birthdate']) && isset($input['emailRegister'])
    && isset($input['passwordRegister']) && isset($input['gender']) && isset($input['active']) && isset($input['ghost_mode'])
) {
    $response = $swipeService->register();
}

//Login functie voor relatieplein.
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $input = json_decode(file_get_contents('php://input'), true);

//     if (isset($input['email']) && isset($input['password'])) {
//         $response = $swipeService->login($input['email'], $input['password']);
//         echo json_encode($response);
//     }
// }

//Maak profile functie voor relatieplein.
if (
    isset($input['description']) && isset($input['fun_fact']) && isset($input['province']) && isset($input['fav_color'])
    && isset($input['fav_animal']) && isset($input['fav_season']) && isset($input['emoji_description']) && isset($input['starsign'])
    && isset($input['hobby_description']) && isset($input['occupation']) && isset($input['green_flag']) && isset($input['red_flag'])
    && isset($input['user_id'])
) {
    $user_id = $input['user_id'];
    $description = $input['description'];
    $fun_fact = $input['fun_fact'];
    $province = $input['province'];
    $fav_color = $input['fav_color'];
    $fav_animal = $input['fav_animal'];
    $fav_season = $input['fav_season'];
    $emoji_description = $input['emoji_description'];
    $starsign = $input['starsign'];
    $hobby_description = $input['hobby_description'];
    $occupation = $input['occupation'];
    $green_flag = $input['green_flag'];
    $red_flag = $input['red_flag'];

    // Now call the insertProfileIntoDatabase method
    $response = $swipeService->insertProfileIntoDatabase($user_id, $description, $fun_fact, $province, $fav_color, $fav_animal, $fav_season, $emoji_description, $starsign, $hobby_description, $occupation, $green_flag, $red_flag);
}

//Voor insertOnboardingValues in database

// if (
//     isset($input['firstNameOnboarding']) && isset($input['lastNameOnboarding']) && isset($input['birthdayOnboarding']) && isset($input['emailOnboarding']) &&
//     isset($input['passwordOnboarding']) && isset($input['genderOnboarding']) && isset($input['selectedGenderPreferences']) && isset($input['description']) &&
//     isset($input['fun_fact']) && isset($input['province']) && isset($input['fav_color']) && isset($input['fav_animal']) && isset($input['fav_season']) &&
//     isset($input['emoji_description']) && isset($input['starsign']) && isset($input['hobby_description']) && isset($input['occupation']) && isset($input['green_flag'])
//     && isset($input['red_flag']) && isset($input['selectedLookingFors']) && isset($input['imgFileName']) && isset($input['imgFilePath']) && isset($input['imgFileType'])
// ) {
//     // For onboarding user
//     $first_name = $input['firstNameOnboarding'];
//     $last_name = $input['lastNameOnboarding'];
//     $birthdate = $input['birthdayOnboarding'];
//     $email = $input['emailOnboarding'];
//     $password = $input['passwordOnboarding'];
//     $gender = $input['genderOnboarding'];
//     $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

//     // For onboarding profile
//     $description = $input['description'];
//     $fun_fact = $input['fun_fact'];
//     $province = $input['province'];
//     $fav_color = $input['fav_color'];
//     $fav_animal = $input['fav_animal'];
//     $fav_season = $input['fav_season'];
//     $emoji_description = $input['emoji_description'];
//     $starsign = $input['starsign'];
//     $hobby_description = $input['hobby_description'];
//     $occupation = $input['occupation'];
//     $green_flag = $input['green_flag'];
//     $red_flag = $input['red_flag'];

//     // For onboarding sexPref
//     $selectedGenderPreferences = $input['selectedGenderPreferences'];

//     // For onboarding looking for
//     $selectedLookingFors = $input['selectedLookingFors'];

//     // For onboarding image
//     $image_file_name = $input['imgFileName'];
//     $image_file_path = $input['imgFilePath'];
//     $image_file_type = $input['imgFileType'];

//     $response = $swipeService->insertOnboardingValues(
//         $first_name,
//         $last_name,
//         $birthdate,
//         $email,
//         $hashedPassword,
//         $gender,
//         $selectedGenderPreferences,
//         $description,
//         $fun_fact,
//         $province,
//         $fav_color,
//         $fav_animal,
//         $fav_season,
//         $emoji_description,
//         $starsign,
//         $hobby_description,
//         $occupation,
//         $green_flag,
//         $red_flag,
//         $selectedLookingFors,
//         $image_file_path,
//         $image_file_name,
//         $image_file_type
//     );
// }

//Voor delete image
if (isset($input['image_id'])) {
    $image_id = $input['image_id']; // Missing semicolon added

    $response = $swipeService->deleteImage($image_id);
}

//Voor delete user
if (isset($input['deleteUser_id'])) {
    $user_id = $input['deleteUser_id']; // Missing semicolon added

    $response = $swipeService->deleteUser($user_id);
}

//Voor upload image
if (isset($input['prof_user_id']) && isset($input['img_file_name']) && isset($input['img_file_type']) && isset($input['base64_image'])) {
    $Profile_User_idUser = $input['prof_user_id'];
    $image_name = $input['img_file_name'];
    $image_type = $input['img_file_type'];
    $base64_image = $input['base64_image'];

    // Call the uploadImage method from $swipeService
    $response = $swipeService->uploadImage($Profile_User_idUser, $base64_image, $image_name, $image_type);
} else {
    // Handle case where input parameters are missing
    // $response = ['status' => 400, 'message' => 'Missing required parameters'];
    // You may want to send a response or log an error here
}


if (
    isset($input['editDescription']) && isset($input['editFun_fact']) && isset($input['editProvince']) && isset($input['editFav_color']) && isset($input['editFav_animal']) && isset($input['editFav_season']) &&
    isset($input['editEmoji_description']) && isset($input['editStarsign']) && isset($input['editHobby_description']) && isset($input['editOccupation']) && isset($input['editGreen_flag']) && isset($input['editRed_flag'])
    && isset($input['editProfile_id'])
) {
    $description = $input['editDescription'];
    $fun_fact = $input['editFun_fact'];
    $province = $input['editProvince'];
    $fav_color = $input['editFav_color'];
    $fav_animal = $input['editFav_animal'];
    $fav_season = $input['editFav_season'];
    $emoji_description = $input['editEmoji_description'];
    $starsign = $input['editStarsign'];
    $hobby_description = $input['editHobby_description'];
    $occupation = $input['editOccupation'];
    $green_flag = $input['editGreen_flag'];
    $red_flag = $input['editRed_flag'];
    $user_id = $input['editProfile_id'];

    $response = $swipeService->editProfile($description, $fun_fact, $province, $fav_color, $fav_animal, $fav_season, $emoji_description, $starsign, $hobby_description, $occupation, $green_flag, $red_flag, $user_id);
}

//Voor insert sexual_preference
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['selectedGenderPreferences']) && isset($input['SPprofile_id'])) {
        $selectedGenderPreferences = $input['selectedGenderPreferences'];
        $profile_id = $input['SPprofile_id'];
        $swipeService->insertSexual_preference($profile_id, $selectedGenderPreferences);
    }
}

//Voor insert Looking_for
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['selectedLookingFor']) && isset($input['LFprofile_id'])) {
        $selectedLookingFor = $input['selectedLookingFor'];
        $profile_id = $input['LFprofile_id'];
        $swipeService->insertLooking_For($profile_id, $selectedLookingFor);
    }
}

//Voor update Looking_for en sexPref
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Check if necessary inputs are set
    if (isset($input['edit_profile_id']) && isset($input['editLookingFor'])) {
        $profile_id = $input['edit_profile_id'];
        $editLookingFor = $input['editLookingFor']; // Ensure this is correctly named and structured

        $swipeService->editLookingFor($profile_id, $editLookingFor);
    }
}

//Voor update Looking_for en sexPref
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Check if necessary inputs are set
    if (isset($input['editGenderPreferences']) && isset($input['edit_profile_id'])) {
        $editGenderPreferences = $input['editGenderPreferences'];
        $profile_id = $input['edit_profile_id'];

        // Invoke editSexual_preference
        $swipeService->editSexual_preference($profile_id, $editGenderPreferences);

        // Respond with success message or handle further operations
        // Optionally, you can return a JSON response indicating success or failure
        echo json_encode(['status' => 200, 'message' => 'Sexual preferences updated successfully']);
    } else {
        // Handle missing parameters error
        echo json_encode(['status' => 400, 'message' => 'Missing parameters']);
    }
}

//Voor update Looking_for en sexPref
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['activateUser_id'])) {
        $activateUser_id = $input['activateUser_id'];

        // Invoke editSexual_preference
        $swipeService->activateAccount($activateUser_id);

        echo json_encode(['status' => 200, 'message' => 'Account succesfully activated']);
    } else {
        // Handle missing parameters error
        echo json_encode(['status' => 400, 'message' => 'Missing parameters']);
    }
}



//Voor edit user
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $fieldsToUpdate = [];
    $params = [];

    // Check each field and add it to the update array if it is set
    if (isset($input['editFirstname'])) {
        $fieldsToUpdate[] = 'firstname = ?';
        $params[] = $input['editFirstname'];
    }
    if (isset($input['editLastname'])) {
        $fieldsToUpdate[] = 'lastname = ?';
        $params[] = $input['editLastname'];
    }
    if (isset($input['editBirthdate'])) {
        $fieldsToUpdate[] = 'birthdate = ?';
        $params[] = $input['editBirthdate'];
    }
    if (isset($input['editEmail'])) {
        $fieldsToUpdate[] = 'email = ?';
        $params[] = $input['editEmail'];
    }
    if (isset($input['editPassword']) && !empty($input['editPassword'])) {
        $fieldsToUpdate[] = 'password = ?';
        $params[] = password_hash($input['editPassword'], PASSWORD_DEFAULT);
    }
    if (isset($input['editGender'])) {
        $fieldsToUpdate[] = 'gender = ?';
        $params[] = $input['editGender'];
    }
    if (isset($input['editActive'])) {
        $fieldsToUpdate[] = 'active = ?';
        $params[] = $input['editActive'];
    }
    if (isset($input['editGhost'])) {
        $fieldsToUpdate[] = 'ghost_mode = ?';
        $params[] = $input['editGhost'];
    }

    // Always include user_id in the params for the WHERE clause
    $params[] = $input['editUser_id'];
    $response = $swipeService->editUser($fieldsToUpdate, $params);
}
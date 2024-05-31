<?php


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
$message = isset($input['message']) ? $input['message'] : null;  
$message_liked = isset($input['message_liked']) ? (int) $input['message_liked'] : null;
$replied_message_id = isset($input['replied_message_id']) ? (int) $input['replied_message_id'] : null;

$swipeService = new API();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!is_null($userId) && !is_null($swipedUserId) && !is_null($type)) {
        $response = $swipeService->registerSwipe($userId, $swipedUserId, $type);
        http_response_code($response['status']);    
        echo json_encode(['message' => $response['message']]);
    } elseif (!is_null($match_id) && !is_null($sender_id) && !is_null($message) && !is_null($message_liked) && !is_null($replied_message_id)) {
        $response = $swipeService->insertIntoDatabase($match_id, $sender_id, $message, $message_liked, $replied_message_id);
        http_response_code($response['status']);
        echo json_encode(['message' => $response['message']]);
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
    }
}



//Underneath values all refer to the retrieve functions from fetchAPI.php
//Voor ophalen van de Image waardes van de database
$singleImage = isset($_GET['singleImage']) ? (int) $_GET['singleImage'] : null;
$allImage = isset($_GET['allImage']);
$allImageByUser = isset($_GET['allImageByUser']) ? (int) $_GET['allImageByUser'] : null;


if(!is_null($singleImage) || ($allImage != false) || !is_null($allImageByUser))
{
    $result = $swipeService->retrieveImage($singleImage, $allImage, $allImageByUser);
    {
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

if(!is_null($singleLooking_for) || ($allLooking_for != false) )
{
    $result = $swipeService->retrieveLooking_for($singleLooking_for, $allLooking_for);
    {
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

if(!is_null($singleMatch) || ($allMatch != false) )
{
    $result = $swipeService->retrieveMatch($singleMatch, $allMatch);
    {
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

if(!is_null($singleMessage) || ($allMessage != false) || !is_null($allMessageByUser) )
{
    $result = $swipeService->retrieveMessage($singleMessage, $allMessage, $allMessageByUser);
    {
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

if(!is_null($singleProfile) || ($allProfile != false) )
{
    $result = $swipeService->retrieveProfile($singleProfile, $allProfile);
    {
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

if(!is_null($singleReport) || ($allReport != false) )
{
    $result = $swipeService->retrieveReport($singleReport, $allReport);
    {
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

if(!is_null($singleSexual_preference) || ($allSexual_preference != false) )
{
    $result = $swipeService->retrieveSexual_preference($singleSexual_preference, $allSexual_preference);
    {
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

if(!is_null($singleSpecial_field) || ($allSpecial_field != false) )
{
    $result = $swipeService->retrieveSpecial_field($singleSpecial_field, $allSpecial_field);
    {
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

if(!is_null($singleSwipe) || ($allSwipe != false) )
{
    $result = $swipeService->retrieveSwipe($singleSwipe, $allSwipe);
    {
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

if(!is_null($singleUser) || ($allUser != false) )
{
    $result = $swipeService->retrieveUser($singleUser, $allUser);
    {
    http_response_code(200);
    if ($result != null || $result != false) {
        echo json_encode($result);
    }
    exit;
    }
}
?>
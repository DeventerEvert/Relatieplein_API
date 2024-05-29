<?php


require '../config/DBconfig.php';
require '../handlers/retrieveAPI.php';

header('Content-Type: application/json');

parse_str(file_get_contents("php://input"), $_PUT);

$userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : null;
$swipedUserId = isset($_GET['swiped_user_id']) ? (int) $_GET['swiped_user_id'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;


$swipeService = new API();
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!is_null($userId) && !is_null($swipedUserId) && !is_null($type)) {
        $response = $swipeService->registerSwipe($userId, $swipedUserId, $type);
        http_response_code($response['status']);    
        echo json_encode(['message' => $response['message']]);
    }
}

$singleImage = isset($_GET['singleImage']) ? (int) $_GET['singleImage'] : null;
$allImage = isset($_GET['allImage']);
$singleLooking_for = isset($_GET['singleLooking_for']) ? (int) $_GET['singleLooking_for'] : null;
$allLooking_for = isset($_GET['allLooking_for']);
$singleMatch = isset($_GET['singleMatch']) ? (int) $_GET['singleMatch'] : null;
$allMatch = isset($_GET['allMatch']);
$singleMessage = isset($_GET['singleMessage']) ? (int) $_GET['singleMessage'] : null;
$allMessage = isset($_GET['allMessage']);
$singleProfile = isset($_GET['singleProfile']) ? (int) $_GET['singleProfile'] : null;
$allProfile = isset($_GET['allProfile']);
$singleReport = isset($_GET['singleReport']) ? (int) $_GET['singleReport'] : null;
$allReport = isset($_GET['allReport']);
$singleSexual_preference = isset($_GET['singleSexual_preference']) ? (int) $_GET['singleSexual_preference'] : null;
$allSexual_preference = isset($_GET['allSexual_preference']);
$singleSpecial_field = isset($_GET['singleSpecial_field']) ? (int) $_GET['singleSpecial_field'] : null;
$allSpecial_field = isset($_GET['allSpecial_field']);
$singleSwipe = isset($_GET['singleSwipe']) ? (int) $_GET['singleSwipe'] : null;
$allSwipe = isset($_GET['allSwipe']);
$singleUser = isset($_GET['singleUser']) ? (int) $_GET['singleUser'] : null;
$allUser = isset($_GET['allUser']);
$allMessageByUser = isset($_GET['allMessageByUser']) ? (int) $_GET['allMessageByUser'] : null;
$allImageByUser = isset($_GET['allImageByUser']) ? (int) $_GET['allImageByUser'] : null;


if (!is_null($singleImage) || ($allImage != false) || !is_null($singleLooking_for) || ($allLooking_for != false) || !is_null($singleMatch) || ($allMatch != false) || !is_null($singleMessage) || ($allMessage != false) ||
    !is_null($singleProfile) || ($allProfile != false) || !is_null($singleReport) || ($allReport != false) || !is_null($singleSexual_preference) || ($allSexual_preference != false) || !is_null($singleSpecial_field) ||
    ($allSpecial_field != false) || !is_null($singleSwipe) || ($allSwipe != false) || !is_null($singleUser) || ($allUser != false) || !is_null($allImageByUser) || !is_null($allMessageByUser)) 
{
    $result = $swipeService->retrieveFromDatabase($singleImage, $allImage, $singleLooking_for, $allLooking_for, $singleMatch, $allMatch, $singleMessage, $allMessage, $singleProfile, $allProfile, $singleReport, $allReport, 
    $singleSexual_preference, $allSexual_preference, $singleSpecial_field, $allSpecial_field, $singleSwipe, $allSwipe, $singleUser, $allUser, $allImageByUser, $allMessageByUser);
    http_response_code(200);
    if ($result != null) {
        echo json_encode($result);
    }
    exit;
}






$input = json_decode(file_get_contents('php://input'), true);

$match_id = isset($input['match_id']) ? (int) $input['match_id'] : null;
$message = isset($input['message']) ? $input['message'] : null;  // Changed to STRING
$message_liked = isset($input['message_liked']) ? (int) $input['message_liked'] : null;
$replied_message_id = isset($input['replied_message_id']) ? (int) $input['replied_message_id'] : null;
$sent_at = isset($input['sent_at']) ? $input['sent_at'] : null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!is_null($match_id) && !is_null($message) && !is_null($message_liked) && !is_null($replied_message_id)) {
        $response = $swipeService->insertIntoDatabase($match_id, $message, $message_liked, $replied_message_id);
        http_response_code($response['status']);
        echo json_encode(['message' => $response['message']]);
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['message' => 'Invalid input']);
    }
}

//Bij allMessageByUser Select * veranderen van Message_Id naar Sender_id TODO:
?>
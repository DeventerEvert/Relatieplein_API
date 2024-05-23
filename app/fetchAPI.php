// Testing the workflow
<?php
require '../config/DBconfig.php';
require '../handlers/retrieveAPI.php';

header('Content-Type: application/json');

parse_str(file_get_contents("php://input"), $_PUT);

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$swipedUserId = isset($_GET['swiped_user_id']) ? (int)$_GET['swiped_user_id'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;

if (is_null($userId) || is_null($swipedUserId) || is_null($type)) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing required parameters']);
    exit();
}
$swipeService = new API();
$response = $swipeService->registerSwipe($userId, $swipedUserId, $type);

http_response_code($response['status']);
echo json_encode(['message' => $response['message']]);
?>

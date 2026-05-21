<?php
header('Content-Type: application/json');
include '../config.php';

$room_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$room_id) {
    echo json_encode(['error' => 'Room ID not provided']);
    exit;
}

$sql = "SELECT r.*, rc.name as category_name FROM rooms r 
        JOIN room_categories rc ON r.category_id = rc.id 
        WHERE r.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'Room not found']);
}
?>

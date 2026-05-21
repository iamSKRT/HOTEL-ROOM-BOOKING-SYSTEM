<?php
header('Content-Type: application/json');
include '../config.php';

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

if ($category_id) {
    $sql = "SELECT r.*, rc.name as category_name FROM rooms r 
            JOIN room_categories rc ON r.category_id = rc.id 
            WHERE r.category_id = ? AND r.available = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT r.*, rc.name as category_name FROM rooms r 
            JOIN room_categories rc ON r.category_id = rc.id 
            WHERE r.available = 1";
    $result = $conn->query($sql);
}

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

echo json_encode($rooms);
?>

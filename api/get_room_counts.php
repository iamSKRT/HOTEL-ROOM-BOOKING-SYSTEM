<?php
header('Content-Type: application/json');
include '../config.php';

$sql = "SELECT rc.name as category_name, COUNT(r.id) as room_count
        FROM room_categories rc
        LEFT JOIN rooms r ON rc.id = r.category_id AND r.available = 1
        GROUP BY rc.id, rc.name
        ORDER BY rc.id";

$result = $conn->query($sql);

$room_counts = [];
while ($row = $result->fetch_assoc()) {
    $room_counts[] = $row;
}

echo json_encode($room_counts);
?>
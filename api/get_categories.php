<?php
header('Content-Type: application/json');
include '../config.php';

$sql = "SELECT * FROM room_categories";
$result = $conn->query($sql);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode($categories);
?>

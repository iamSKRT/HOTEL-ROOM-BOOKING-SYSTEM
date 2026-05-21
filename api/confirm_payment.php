<?php
header('Content-Type: application/json');
include '../config.php';

$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

if (!$booking_id) {
    echo json_encode(['success' => false, 'message' => 'No booking ID provided']);
    exit;
}

// Update booking and payment status
$payment_status = 'paid';
$booking_status = 'confirmed';

$stmt = $conn->prepare("UPDATE bookings SET payment_status = ?, status = ? WHERE id = ?");
$stmt->bind_param("ssi", $payment_status, $booking_status, $booking_id);

if ($stmt->execute()) {
    $stmt = $conn->prepare("UPDATE payment_transactions SET status = ? WHERE booking_id = ?");
    $status = 'completed';
    $stmt->bind_param("si", $status, $booking_id);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE rooms r JOIN bookings b ON r.id = b.room_id SET r.available = 0 WHERE b.id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    
    echo json_encode(['success' => true, 'message' => 'Payment confirmed successfully', 'booking_id' => $booking_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error confirming payment']);
}
?>

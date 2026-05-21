<?php
header('Content-Type: application/json');
include '../config.php';

$data = json_decode(file_get_contents("php://input"), true);

$booking_id = (int)($data['booking_id'] ?? 0);
$payment_method = sanitize($data['payment_method'] ?? '');
$reference_number = sanitize($data['reference_number'] ?? '');

if (!$booking_id || !$payment_method || !$reference_number) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment details or missing reference number']);
    exit;
}

$reference_number = preg_replace('/\D/', '', $reference_number);
if (strlen($reference_number) !== 15) {
    echo json_encode(['success' => false, 'message' => 'Reference number must be exactly 15 digits']);
    exit;
}

// Get booking details
$stmt = $conn->prepare("SELECT total_price FROM bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit;
}

$booking = $result->fetch_assoc();
$amount = $booking['total_price'];

// Create payment transaction
$transaction_id = 'TRX-' . time() . '-' . $booking_id;
$status = 'pending';

$stmt = $conn->prepare("INSERT INTO payment_transactions (booking_id, amount, payment_method, transaction_id, status, reference_number) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("idssss", $booking_id, $amount, $payment_method, $transaction_id, $status, $reference_number);

if ($stmt->execute()) {
    $updateBookingMethod = $conn->prepare("UPDATE bookings SET payment_method = ? WHERE id = ?");
    $updateBookingMethod->bind_param("si", $payment_method, $booking_id);
    $updateBookingMethod->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Payment reference submitted. Awaiting admin verification.',
        'transaction_id' => $transaction_id,
        'booking_id' => $booking_id,
        'amount' => $amount,
        'payment_method' => $payment_method,
        'redirect_url' => 'pages/payment_pending.html?booking_id=' . $booking_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error processing payment: ' . $stmt->error]);
}
?>


<?php
header('Content-Type: application/json');
include '../config.php';

$data = json_decode(file_get_contents("php://input"), true);

$customer_name = sanitize($data['customer_name'] ?? '');
$customer_email = sanitize($data['customer_email'] ?? '');
$customer_phone = sanitize($data['customer_phone'] ?? '');
$room_id = (int)($data['room_id'] ?? 0);
$check_in = sanitize($data['check_in_date'] ?? '');
$check_out = sanitize($data['check_out_date'] ?? '');
$num_guests = (int)($data['num_guests'] ?? 0);
$payment_method = sanitize($data['payment_method'] ?? '');

// Validation
if (!$customer_name || !$customer_email || !$customer_phone || !$room_id || !$check_in || !$check_out || !$num_guests) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!validateEmail($customer_email)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Get room details
$stmt = $conn->prepare("SELECT price FROM rooms WHERE id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Room not found']);
    exit;
}

$room = $result->fetch_assoc();
$price_per_night = $room['price'];

// Calculate total price
$check_in_obj = new DateTime($check_in);
$check_out_obj = new DateTime($check_out);
$nights = $check_out_obj->diff($check_in_obj)->days;

if ($nights <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid check-out date']);
    exit;
}

$total_price = $price_per_night * $nights;

// Check if customer exists or create new
$stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer_id = $result->fetch_assoc()['id'];
} else {
    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $customer_name, $customer_email, $customer_phone);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error creating customer']);
        exit;
    }
    $customer_id = $conn->insert_id;
}

// Create booking
$status = 'pending';
$stmt = $conn->prepare("INSERT INTO bookings (customer_id, room_id, check_in_date, check_out_date, number_of_guests, total_price, payment_method, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissidss", $customer_id, $room_id, $check_in, $check_out, $num_guests, $total_price, $payment_method, $status);

if ($stmt->execute()) {
    $booking_id = $conn->insert_id;
    echo json_encode([
        'success' => true,
        'message' => 'Booking created successfully',
        'booking_id' => $booking_id,
        'total_price' => $total_price
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error creating booking']);
}
?>

<?php
require_once '../config.php';
requireAdminLogin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_action']) && isset($_POST['booking_id'])) {
    $booking_id = (int)$_POST['booking_id'];
    $action = $_POST['booking_action'];

    if ($action === 'delete') {
        $stmt = $conn->prepare("SELECT room_id, status FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $bookingResult = $stmt->get_result();

        if ($bookingResult->num_rows > 0) {
            $bookingData = $bookingResult->fetch_assoc();
            $room_id = $bookingData['room_id'];
            $booking_status = $bookingData['status'];

            $deletePayment = $conn->prepare("DELETE FROM payment_transactions WHERE booking_id = ?");
            $deletePayment->bind_param("i", $booking_id);
            $deletePayment->execute();

            $deleteBooking = $conn->prepare("DELETE FROM bookings WHERE id = ?");
            $deleteBooking->bind_param("i", $booking_id);
            $deleteBooking->execute();

            if ($booking_status === 'confirmed') {
                $updateRoom = $conn->prepare("UPDATE rooms SET available = 1 WHERE id = ?");
                $updateRoom->bind_param("i", $room_id);
                $updateRoom->execute();
            }

            $message = 'Booking removed successfully.';
        } else {
            $message = 'Booking not found.';
        }
    } else {
        $stmt = $conn->prepare("SELECT id FROM payment_transactions WHERE booking_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $payment_id = $result->fetch_assoc()['id'];
            if ($action === 'verify') {
                $updatePayment = $conn->prepare("UPDATE payment_transactions SET status = 'completed' WHERE id = ?");
                $updatePayment->bind_param("i", $payment_id);
                $updatePayment->execute();

                $payment_status = 'paid';
                $booking_status = 'confirmed';
                $updateBooking = $conn->prepare("UPDATE bookings SET payment_status = ?, status = ? WHERE id = ?");
                $updateBooking->bind_param("ssi", $payment_status, $booking_status, $booking_id);
                $updateBooking->execute();

                $updateRoom = $conn->prepare("UPDATE rooms r JOIN bookings b ON r.id = b.room_id SET r.available = 0 WHERE b.id = ?");
                $updateRoom->bind_param("i", $booking_id);
                $updateRoom->execute();

                $message = 'Payment verified successfully and room availability updated.';
            } elseif ($action === 'reject') {
                $updatePayment = $conn->prepare("UPDATE payment_transactions SET status = 'rejected' WHERE id = ?");
                $updatePayment->bind_param("i", $payment_id);
                $updatePayment->execute();

                $payment_status = 'rejected';
                $booking_status = 'cancelled';
                $updateBooking = $conn->prepare("UPDATE bookings SET payment_status = ?, status = ? WHERE id = ?");
                $updateBooking->bind_param("ssi", $payment_status, $booking_status, $booking_id);
                $updateBooking->execute();

                $message = 'Payment rejected and booking cancelled.';
            }
        } else {
            $message = 'No payment record found for this booking.';
        }
    }
}

// Get all bookings
$stmt = $conn->prepare("SELECT b.*, c.name as customer_name, c.email, c.phone, r.name as room_name,
                        (SELECT payment_method FROM payment_transactions pt WHERE pt.booking_id = b.id ORDER BY pt.created_at DESC LIMIT 1) as payment_method,
                        (SELECT reference_number FROM payment_transactions pt WHERE pt.booking_id = b.id ORDER BY pt.created_at DESC LIMIT 1) as reference_number,
                        (SELECT status FROM payment_transactions pt WHERE pt.booking_id = b.id ORDER BY pt.created_at DESC LIMIT 1) as payment_txn_status
                        FROM bookings b 
                        LEFT JOIN customers c ON b.customer_id = c.id 
                        LEFT JOIN rooms r ON b.room_id = r.id 
                        ORDER BY b.booking_date DESC");
$stmt->execute();
$bookings = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Palmwave Resort & Suites</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" href="../assets/palm-wave-icon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body class="text-gray-900 bg-gray-100">

<div class="flex h-screen">
    <!-- SIDEBAR -->
    <div class="w-64 bg-gray-800 text-white shadow-lg fixed h-full overflow-y-auto">
        <div class="p-4 border-b border-gray-700">
            <h1 class="text-2xl font-light px-7" style="font-family: 'Playfair Display', serif;">
                admin
            </h1>
        </div>

        <nav class="p-6 space-y-2">
            <a href="admin_dashboard.php" class="block px-4 py-2 flex gap-4 rounded text-white-500 hover:bg-gray-600 transition font-mono">
                <!----------------------------- DASHBOARD ICON ----------------------------->

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-star-icon lucide-user-star"><path d="M16.051 12.616a1 1 0 0 1 1.909.024l.737 1.452a1 1 0 0 0 .737.535l1.634.256a1 1 0 0 1 .588 1.806l-1.172 1.168a1 1 0 0 0-.282.866l.259 1.613a1 1 0 0 1-1.541 1.134l-1.465-.75a1 1 0 0 0-.912 0l-1.465.75a1 1 0 0 1-1.539-1.133l.258-1.613a1 1 0 0 0-.282-.866l-1.156-1.153a1 1 0 0 1 .572-1.822l1.633-.256a1 1 0 0 0 .737-.535z"/><path d="M8 15H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/></svg>

            Dashboard
            </a>
            <!----------------------------- MANAGE BOOKINGS ICON -----------------------------> 

            <a href="manage_bookings.php" class="sidebar-item flex gap-4  block px-4 py-2 rounded text-white bg-gray-600 hover:bg-gray-600 transition font-mono">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-clock-icon lucide-clipboard-clock"><path d="M16 14v2.2l1.6 1"/><path d="M16 4h2a2 2 0 0 1 2 2v.832"/><path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2"/><circle cx="16" cy="16" r="6"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>    
            Manage Bookings
            </a>
            <!----------------------------- MANAGE ROOMS ICON -----------------------------> 
            <a href="manage_rooms.php" class="sidebar-item flex gap-4  block px-4 py-2 rounded text-white hover:bg-gray-600  transition font-mono">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark"><path d="M10 18v-7"/><path d="M11.12 2.198a2 2 0 0 1 1.76.006l7.866 3.847c.476.233.31.949-.22.949H3.474c-.53 0-.695-.716-.22-.949z"/><path d="M14 18v-7"/><path d="M18 18v-7"/><path d="M3 22h18"/><path d="M6 18v-7"/></svg>    
            Manage Rooms
            </a>
            <!----------------------------- CUSTOMERS ICON -----------------------------> 
            <a href="manage_customers.php" class="sidebar-item flex gap-4  block px-4 py-2 rounded text-white hover:bg-gray-600  transition font-mono">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>    
            Customers
            </a>
            <a href="manage_payments.php" class="sidebar-item flex gap-4  block px-4 py-2 rounded text-white hover:bg-gray-600  transition font-mono">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card-icon lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>    
            Payments
            </a>
        </nav>

        <div class="absolute bottom-6 left-6 right-6 border-t border-gray-700 pt-6">
            <p class="text-sm text-gray-400 mb-4">
                Logged in as: <strong><?php echo $_SESSION['admin_username']; ?></strong>
            </p>
           <a href="logout.php" class="block w-full text-center px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded text-white font-semibold transition font-mono">
                Logout
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="ml-64 flex-1 overflow-auto">
        <!-- TOP BAR -->
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4">
            <h2 class="text-2xl font-semibold text-gray-800 font-serif  ">Manage Bookings</h2>
            <p class="text-gray-600 text-sm font-mono">Track and manage customer reservations (Total: <?php echo $bookings->num_rows; ?>)</p>
        </div>

        <!-- CONTENT -->
        <div class="p-8">
            <?php if ($message): ?>
                <div class="mb-6 p-4 rounded <?php echo strpos($message, 'successfully') !== false ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-yellow-50 border border-yellow-200 text-yellow-800'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- BOOKINGS TABLE -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Booking ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Check-out</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Payment</th>
                                <th class="px-20 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php while ($booking = $bookings->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 text-sm">
                                    <td class="px-6 py-4 text-gray-900 font-medium">#<?php echo $booking['id']; ?></td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <div><?php echo $booking['customer_name'] ?? 'Unknown Customer'; ?></div>
                                        <div class="text-xs text-gray-500"><?php echo $booking['email'] ?? 'N/A'; ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo $booking['room_name'] ?? 'Unknown Room'; ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo date('M d, Y', strtotime($booking['check_in_date'])); ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo date('M d, Y', strtotime($booking['check_out_date'])); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?php 
                                            echo ($booking['status'] == 'confirmed') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; 
                                        ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?php 
                                            echo ($booking['payment_status'] == 'paid') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; 
                                        ?>">
                                            <?php echo ucfirst($booking['payment_status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex gap-2 items-center justify-center">
                                            <button type="button" class="inline-flex items-center justify-center px-3 py-2 rounded-full text-black text-xs font-medium text-center" title="View details" onclick="openModal('<?php echo addslashes($booking['payment_method'] ?? 'N/A'); ?>', <?php echo $booking['number_of_guests']; ?>, <?php echo $booking['total_price']; 
                                            ?>, '<?php echo addslashes($booking['reference_number'] ?? '—'); ?>')">View</button>

                                            <?php if ($booking['payment_txn_status'] === 'pending'): ?>
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="booking_action" value="verify">
                                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-600 text-white hover:bg-green-700" title="Confirm payment">✓</button>
                                                </form>
                                                <form method="post" class="inline">
                                                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                    <input type="hidden" name="booking_action" value="reject">
                                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-600 text-white hover:bg-red-700" title="Reject payment">✕</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-gray-500 text-xs"><?php echo $booking['payment_txn_status'] ? ucfirst($booking['payment_txn_status']) : 'No payment'; ?></span>
                                            <?php endif; ?>
                                            <form method="post" class="inline" onsubmit="return confirm('Are you sure you want to remove this booking?');">
                                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                                <input type="hidden" name="booking_action" value="delete">
                                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1 rounded-full text-red-500 font-medium" title="Remove booking">Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Booking Details</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 text-left">
                    Payment Method: <span id="modal-method" class="font-medium"></span><br>
                    Guests: <span id="modal-guests" class="font-medium"></span><br>
                    Total: ₱<span id="modal-total" class="font-medium"></span><br>
                    Reference: <span id="modal-reference" class="font-medium font-mono"></span>
                </p>
            </div>
            <div class="flex items-center px-4 py-3">
                <button id="closeModal" class="px-4 py-2 bg-gray-700 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>



<script>
function openModal(method, guests, total, reference) {
    document.getElementById('modal-method').textContent = method;
    document.getElementById('modal-guests').textContent = guests;
    document.getElementById('modal-total').textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('modal-reference').textContent = reference;
    document.getElementById('viewModal').classList.remove('hidden');
}

document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('viewModal').classList.add('hidden');
});

// Close modal when clicking outside
document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>

</body>
</html>

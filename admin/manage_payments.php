<?php
require_once '../config.php';
requireAdminLogin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_payment_id'])) {
    $payment_id = (int)$_POST['verify_payment_id'];
    if ($payment_id) {
        $stmt = $conn->prepare("SELECT booking_id FROM payment_transactions WHERE id = ? AND status = 'pending'");
        $stmt->bind_param("i", $payment_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $booking_id = $result->fetch_assoc()['booking_id'];

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

            $message = 'Payment verified successfully.';
        } else {
            $message = 'Payment not found or already verified.';
        }
    }
}

// Get all payment transactions
$stmt = $conn->prepare("SELECT pt.*, b.id as booking_id, c.name as customer_name, r.name as room_name 
                        FROM payment_transactions pt 
                        JOIN bookings b ON pt.booking_id = b.id 
                        JOIN customers c ON b.customer_id = c.id 
                        JOIN rooms r ON b.room_id = r.id 
                        ORDER BY pt.created_at DESC");
$stmt->execute();
$payments = $stmt->get_result();

// Get payment statistics
$stmt = $conn->prepare("SELECT 
                        COUNT(*) as total_transactions,
                        SUM(amount) as total_amount,
                        SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as completed_amount
                        FROM payment_transactions");
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments - Palmwave Resort & Suites</title>
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
            <a href="admin_dashboard.php" class="block flex gap-4 px-4 py-2 rounded text-white hover:bg-gray-600 transition font-mono">
                <!----------------------------- DASHBOARD ICON ----------------------------->

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-star-icon lucide-user-star"><path d="M16.051 12.616a1 1 0 0 1 1.909.024l.737 1.452a1 1 0 0 0 .737.535l1.634.256a1 1 0 0 1 .588 1.806l-1.172 1.168a1 1 0 0 0-.282.866l.259 1.613a1 1 0 0 1-1.541 1.134l-1.465-.75a1 1 0 0 0-.912 0l-1.465.75a1 1 0 0 1-1.539-1.133l.258-1.613a1 1 0 0 0-.282-.866l-1.156-1.153a1 1 0 0 1 .572-1.822l1.633-.256a1 1 0 0 0 .737-.535z"/><path d="M8 15H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/></svg>

            Dashboard
            </a>
            <!----------------------------- MANAGE BOOKINGS ICON -----------------------------> 

            <a href="manage_bookings.php" class="sidebar-item flex gap-4  block px-4 py-2 rounded text-white hover:bg-gray-600 transition font-mono">

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
            <a href="manage_payments.php" class="sidebar-item flex gap-4  block px-4 py-2 rounded text-white bg-gray-600 hover:bg-gray-600  transition font-mono">
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
            <h2 class="text-2xl font-semibold text-gray-800 font-serif">Payment Management</h2>
            <p class="text-gray-600 text-sm font-mono">Track and manage all payment transactions</p>
        </div>

        <!-- CONTENT -->
        <div class="p-8">
            <!-- STATS GRID -->
            <?php if (!empty($message)): ?>
                <div class="mb-6 rounded-lg border px-6 py-4 text-sm <?php echo strpos($message, 'successfully') !== false ? 'border-green-300 bg-green-50 text-green-800' : 'border-gray-300 bg-gray-50 text-gray-800'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-600">
                    <p class="text-gray-600 text-sm font-semibold">Total Transactions</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $stats['total_transactions']; ?></h3>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
                    <p class="text-gray-600 text-sm font-semibold">Total Amount</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">₱<?php echo number_format($stats['total_amount'] ?? 0, 2); ?></h3>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
                    <p class="text-gray-600 text-sm font-semibold">Completed Payments</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">₱<?php echo number_format($stats['completed_amount'] ?? 0, 2); ?></h3>
                </div>
            </div>

            <!-- PAYMENTS TABLE -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Transaction ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php while ($payment = $payments->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 text-sm">
                                    <td class="px-6 py-4 text-gray-900 font-mono text-xs"><?php echo substr($payment['transaction_id'], 0, 15); ?>...</td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo $payment['customer_name']; ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo $payment['room_name']; ?></td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">₱<?php echo number_format($payment['amount'], 2); ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo ucfirst($payment['payment_method']); ?></td>
                                    <td class="px-6 py-4 text-gray-700 font-mono text-xs"><?php echo htmlspecialchars($payment['reference_number']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?php 
                                            echo ($payment['status'] == 'completed') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; 
                                        ?>">
                                            <?php echo ucfirst($payment['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if ($payment['status'] == 'pending'): ?>
                                            <form method="post">
                                                <input type="hidden" name="verify_payment_id" value="<?php echo $payment['id']; ?>">
                                                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700">Verify</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-gray-500 text-xs">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600"><?php echo date('M d, Y H:i', strtotime($payment['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

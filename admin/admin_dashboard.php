<?php
require_once '../config.php';
requireAdminLogin();

// Get dashboard statistics
$stmt = $conn->prepare("SELECT COUNT(*) as total_bookings FROM bookings");
$stmt->execute();
$bookings_result = $stmt->get_result();
$total_bookings = $bookings_result->fetch_assoc()['total_bookings'];

$stmt = $conn->prepare("SELECT COUNT(*) as confirmed_bookings FROM bookings WHERE status = 'confirmed'");
$stmt->execute();
$bookings_result = $stmt->get_result();
$confirmed_bookings = $bookings_result->fetch_assoc()['confirmed_bookings'];

$stmt = $conn->prepare("SELECT COUNT(*) as total_rooms FROM rooms");
$stmt->execute();
$rooms_result = $stmt->get_result();
$total_rooms = $rooms_result->fetch_assoc()['total_rooms'];

$stmt = $conn->prepare("SELECT COUNT(*) as total_customers FROM customers");
$stmt->execute();
$customers_result = $stmt->get_result();
$total_customers = $customers_result->fetch_assoc()['total_customers'];

// Get recent bookings
$stmt = $conn->prepare("SELECT b.id, c.name, r.name as room_name, b.check_in_date, b.status, b.total_price 
                        FROM bookings b 
                        JOIN customers c ON b.customer_id = c.id 
                        JOIN rooms r ON b.room_id = r.id 
                        ORDER BY b.booking_date DESC LIMIT 10");
$stmt->execute();
$recent_bookings = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Palmwave Resort & Suites</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../assets/palm-wave-icon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        .sidebar-item.active {
            @apply bg-yellow-600;
        }
    </style>
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
            <a href="admin_dashboard.php" class="sidebar-item flex gap-4 active block px-4 py-2 rounded text-white-500 sfont-semibold hover:bg-gray-600 bg-gray-600  font-mono">
                            
            <!----------------------------- DASHBOARD ICON ----------------------------->

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-star-icon lucide-user-star"><path d="M16.051 12.616a1 1 0 0 1 1.909.024l.737 1.452a1 1 0 0 0 .737.535l1.634.256a1 1 0 0 1 .588 1.806l-1.172 1.168a1 1 0 0 0-.282.866l.259 1.613a1 1 0 0 1-1.541 1.134l-1.465-.75a1 1 0 0 0-.912 0l-1.465.75a1 1 0 0 1-1.539-1.133l.258-1.613a1 1 0 0 0-.282-.866l-1.156-1.153a1 1 0 0 1 .572-1.822l1.633-.256a1 1 0 0 0 .737-.535z"/><path d="M8 15H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/></svg>

            Dashboard
            </a>
            <!----------------------------- MANAGE BOOKINGS ICON -----------------------------> 

            <a href="manage_bookings.php" class="sidebar-item flex gap-4 block px-4 py-2 rounded text-white hover:bg-gray-600 transition font-mono">

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
            <h2 class=" text-2xl font-semibold text-gray-800 font-serif">Dashboard</h2>
            <p class="text-gray-600 text-sm font-mono">Welcome back! Here's your hotel overview.</p>
        </div>

        <!-- CONTENT -->
        <div class="p-8">
            <!-- STATS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold font-mono">Total Bookings</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $total_bookings; ?></h3>
                        </div>
                        <div class="text-4xl text-yellow-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-clock-icon lucide-clipboard-clock"><path d="M16 14v2.2l1.6 1"/><path d="M16 4h2a2 2 0 0 1 2 2v.832"/><path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h2"/><circle cx="16" cy="16" r="6"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>    

                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold font-mono">Confirmed Bookings</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $confirmed_bookings; ?></h3>
                        </div>
                        <div class="text-4xl text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check-icon lucide-calendar-check"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold font-mono">Total Rooms</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $total_rooms; ?></h3>
                        </div>
                        <div class="text-4xl text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark"><path d="M10 18v-7"/><path d="M11.12 2.198a2 2 0 0 1 1.76.006l7.866 3.847c.476.233.31.949-.22.949H3.474c-.53 0-.695-.716-.22-.949z"/><path d="M14 18v-7"/><path d="M18 18v-7"/><path d="M3 22h18"/><path d="M6 18v-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold font-mono">Total Customers</p>
                            <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $total_customers; ?></h3>
                        </div>
                        <div class="text-4xl text-purple-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>    

                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT BOOKINGS -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800 font-mono">Recent Bookings</h3>
                    <a href="manage_bookings.php" class="text-gray-600 hover:text-gray-700 font-semibold text-sm font-mono">View All →</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase font-sans">Booking ID</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase font-sans  ">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase font-sans">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase font-sans">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase font-sans">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase font-sans">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">#<?php echo $booking['id']; ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo $booking['name']; ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo $booking['room_name']; ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($booking['check_in_date'])); ?></td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">₱<?php echo number_format($booking['total_price'], 2); ?></td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?php 
                                            echo ($booking['status'] == 'confirmed') ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; 
                                        ?>">
                                            <?php echo ucfirst($booking['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 font-mono">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="manage_rooms.php" class="block px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded transition font-mono">
                            → Add New Room
                        </a>
                        <a href="manage_bookings.php" class="block px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded transition font-mono">
                            → View All Bookings
                        </a>
                        <a href="manage_customers.php" class="block px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded transition font-mono">
                            → Manage Customers
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">System Info</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Version:</strong> 1.0.0</p>
                        <p><strong>Database:</strong> Connected ✓</p>
                        <p><strong>Last Updated:</strong> Just now</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




</body>
</html>

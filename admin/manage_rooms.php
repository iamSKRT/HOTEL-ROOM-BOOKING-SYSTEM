<?php
require_once '../config.php';
requireAdminLogin();

$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$success_message = '';
$error_message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($action == 'add' || $action == 'edit') {
        $category_id = sanitize($_POST['category_id'] ?? '');
        $name = sanitize($_POST['name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $capacity = intval($_POST['capacity'] ?? 0);
        $amenities = sanitize($_POST['amenities'] ?? '');
        $photo_url = sanitize($_POST['photo_url'] ?? '');
        $photo_url_2 = sanitize($_POST['photo_url_2'] ?? '');
        $photo_url_3 = sanitize($_POST['photo_url_3'] ?? '');
        $photo_url_4 = sanitize($_POST['photo_url_4'] ?? '');
        $uploadedPhoto = '';

        if (isset($_FILES['room_image']) && $_FILES['room_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['room_image']['error'] === UPLOAD_ERR_OK) {
                $validExt = ['png', 'jpg', 'jpeg'];
                $fileName = basename($_FILES['room_image']['name']);
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExt, $validExt)) {
                    $error_message = 'Only PNG and JPG/JPEG images are allowed.';
                } else {
                    $uploadDir = __DIR__ . '/../uploads/room_images/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', pathinfo($fileName, PATHINFO_FILENAME));
                    $newFileName = $safeName . '-' . time() . '.' . $fileExt;
                    $targetPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($_FILES['room_image']['tmp_name'], $targetPath)) {
                        $uploadedPhoto = 'uploads/room_images/' . $newFileName;
                    } else {
                        $error_message = 'Error uploading the image file.';
                    }
                }
            } else {
                $error_message = 'Error uploading the image file.';
            }
        }

        if ($uploadedPhoto) {
            $photo_url = $uploadedPhoto;
        }

        if (!$category_id || !$name || !$price || !$capacity) {
            $error_message = 'Please fill in all required fields';
        } else {
            if ($action == 'add') {
                $stmt = $conn->prepare("INSERT INTO rooms (category_id, name, description, price, capacity, photo_url, photo_url_2, photo_url_3, photo_url_4, amenities) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isiiidsssss", $category_id, $name, $description, $price, $capacity, $photo_url, $photo_url_2, $photo_url_3, $photo_url_4, $amenities);
                
                if ($stmt->execute()) {
                    $success_message = 'Room added successfully!';
                    $action = 'list';
                } else {
                    $error_message = 'Error adding room';
                }
            } elseif ($action == 'edit') {
                $room_id = intval($_POST['room_id'] ?? 0);
                $stmt = $conn->prepare("UPDATE rooms SET category_id=?, name=?, description=?, price=?, capacity=?, photo_url=?, photo_url_2=?, photo_url_3=?, photo_url_4=?, amenities=? WHERE id=?");
                $stmt->bind_param("isiiidsssssi", $category_id, $name, $description, $price, $capacity, $photo_url, $photo_url_2, $photo_url_3, $photo_url_4, $amenities, $room_id);
                
                if ($stmt->execute()) {
                    $success_message = 'Room updated successfully!';
                    $action = 'list';
                } else {
                    $error_message = 'Error updating room';
                }
            }
        }
    } elseif ($action == 'delete') {
        $room_id = intval($_POST['room_id'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $room_id);
        
        if ($stmt->execute()) {
            $success_message = 'Room deleted successfully!';
            $action = 'list';
        } else {
            $error_message = 'Error deleting room';
        }
    }
}

// Get rooms for listing
if ($action == 'list') {
    $stmt = $conn->prepare("SELECT r.*, rc.name as category_name FROM rooms r JOIN room_categories rc ON r.category_id = rc.id ORDER BY rc.name, r.name");
    $stmt->execute();
    $rooms_result = $stmt->get_result();
}

// Get categories for dropdown
$stmt = $conn->prepare("SELECT * FROM room_categories ORDER BY id");
$stmt->execute();
$categories_result = $stmt->get_result();

// Get single room for edit
$room_to_edit = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $room_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $room_to_edit = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - Palmwave Resort & Suites</title>
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
            <a href="manage_rooms.php" class="sidebar-item flex gap-4  block px-4 py-2 rounded text-white bg-gray-600 hover:bg-gray-600  transition font-mono">
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
        <div class="bg-white shadow-sm border-b border-gray-200 px-8 py-4 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 font-serif">Manage Rooms</h2>  
                <p class="text-gray-600 text-sm font-mono">Add, edit, or delete hotel rooms</p>
            </div>
            <?php if ($action == 'list'): ?>
                <a href="?action=add" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded transition">
                    + Add New Room
                </a>
            <?php endif; ?>
        </div>

        <!-- CONTENT -->
        <div class="p-8">
            <?php if ($success_message): ?>
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded text-green-800">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded text-red-800">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($action == 'list'): ?>
                <!-- ROOMS TABLE -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Room Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Capacity</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php while ($room = $rooms_result->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?php echo $room['name']; ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo $room['category_name']; ?></td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">₱<?php echo number_format($room['price'], 2); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo $room['capacity']; ?> guests</td>
                                        <td class="px-6 py-4 text-sm space-x-2 flex">
                                            <a href="?action=edit&id=<?php echo $room['id']; ?>" class="inline-flex items-center gap-2 px-4 py-2  text-black font-medium rounded-lg ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                Edit
                                            </a>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                                <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                                <button type="submit" name="action" value="delete" class="inline-flex items-center gap-2 px-4 py-2 text-red-500 font-medium rounded-lg 5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif ($action == 'add' || $action == 'edit'): ?>
                <!-- ROOM FORM -->
                <div class="bg-white rounded-lg shadow p-8 max-w-3xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">
                        <?php echo ($action == 'add') ? 'Add New Room' : 'Edit Room'; ?>
                    </h3>

                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <?php if ($action == 'edit'): ?>
                            <input type="hidden" name="room_id" value="<?php echo $room_to_edit['id']; ?>">
                        <?php endif; ?>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Category*</label>
                                <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none">
                                    <option value="">Select a category</option>
                                    <?php 
                                    $categories_result->data_seek(0);
                                    while ($cat = $categories_result->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $cat['id']; ?>" 
                                            <?php echo ($action == 'edit' && $room_to_edit['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                            <?php echo $cat['name']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Room Name*</label>
                                <input type="text" name="name" required value="<?php echo $action == 'edit' ? $room_to_edit['name'] : ''; ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none"><?php echo $action == 'edit' ? $room_to_edit['description'] : ''; ?></textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Price per Night (₱)*</label>
                                <input type="number" name="price" step="0.01" required value="<?php echo $action == 'edit' ? $room_to_edit['price'] : ''; ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Capacity (Guests)*</label>
                                <input type="number" name="capacity" min="1" required value="<?php echo $action == 'edit' ? $room_to_edit['capacity'] : '1'; ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Photo File Name</label>
                                <input type="text" name="photo_url" placeholder="e.g., room.png or uploads/room_images/room.png" value="<?php echo $action == 'edit' ? $room_to_edit['photo_url'] : ''; ?>" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none">
                                <p class="text-xs text-gray-500 mt-2">Type a PNG/JPEG filename or upload an image below.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Room Image</label>
                            <input type="file" name="room_image" accept="image/png,image/jpeg" class="w-full text-sm text-gray-600" />
                            <?php if ($action == 'edit' && !empty($room_to_edit['photo_url'])): ?>
                                <div class="mt-4">
                                    <span class="text-sm font-semibold text-gray-700">Current Image:</span>
                                    <div class="mt-2">
                                        <img src="<?php echo htmlspecialchars($room_to_edit['photo_url']); ?>" alt="Room image" class="h-28 object-cover rounded-lg border border-gray-200" />
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Gallery Image 2 -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gallery Image 2 (Filename)</label>
                            <input type="text" name="photo_url_2" placeholder="e.g., room_2.png or uploads/room_images/room_2.png" value="<?php echo $action == 'edit' ? $room_to_edit['photo_url_2'] : ''; ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Gallery Image 2</label>
                            <input type="file" name="room_image_2" accept="image/png,image/jpeg" class="w-full text-sm text-gray-600" />
                            <?php if ($action == 'edit' && !empty($room_to_edit['photo_url_2'])): ?>
                                <div class="mt-2">
                                    <img src="<?php echo htmlspecialchars($room_to_edit['photo_url_2']); ?>" alt="Gallery image 2" class="h-20 object-cover rounded-lg border border-gray-200" />
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Gallery Image 3 -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gallery Image 3 (Filename)</label>
                            <input type="text" name="photo_url_3" placeholder="e.g., room_3.png or uploads/room_images/room_3.png" value="<?php echo $action == 'edit' ? $room_to_edit['photo_url_3'] : ''; ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Gallery Image 3</label>
                            <input type="file" name="room_image_3" accept="image/png,image/jpeg" class="w-full text-sm text-gray-600" />
                            <?php if ($action == 'edit' && !empty($room_to_edit['photo_url_3'])): ?>
                                <div class="mt-2">
                                    <img src="<?php echo htmlspecialchars($room_to_edit['photo_url_3']); ?>" alt="Gallery image 3" class="h-20 object-cover rounded-lg border border-gray-200" />
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Gallery Image 4 -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gallery Image 4 (Filename)</label>
                            <input type="text" name="photo_url_4" placeholder="e.g., room_4.png or uploads/room_images/room_4.png" value="<?php echo $action == 'edit' ? $room_to_edit['photo_url_4'] : ''; ?>" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Gallery Image 4</label>
                            <input type="file" name="room_image_4" accept="image/png,image/jpeg" class="w-full text-sm text-gray-600" />
                            <?php if ($action == 'edit' && !empty($room_to_edit['photo_url_4'])): ?>
                                <div class="mt-2">
                                    <img src="<?php echo htmlspecialchars($room_to_edit['photo_url_4']); ?>" alt="Gallery image 4" class="h-20 object-cover rounded-lg border border-gray-200" />
                                </div>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Amenities</label>
                            <textarea name="amenities" rows="3" placeholder="List amenities separated by commas" class="w-full px-4 py-2 border border-gray-300 rounded focus:border-gray-400 outline-none"><?php echo $action == 'edit' ? $room_to_edit['amenities'] : ''; ?></textarea>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded transition">
                                <?php echo ($action == 'add') ? 'Create Room' : 'Update Room'; ?>
                            </button>
                            <a href="manage_rooms.php" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded transition">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>

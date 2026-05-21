<?php
include '../config.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $login_error = 'Please enter both username and password';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            // For this demo, we'll use simple password check (in production, use password_verify)
            if ($password === 'admin123' || password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $login_error = 'Invalid username or password';
            }
        } else {
            $login_error = 'Invalid username or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Palmwave Resort & Suites</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="../assets/palm-wave-icon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body class="text-white bg-gray-900">

<!-- LOGIN PAGE -->
<div class="min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-light mb-2" style="font-family: 'Playfair Display', serif;">
                Palmwave Resort & Suites 
            </h1>
            <p class="text-gray-400">Admin Panel Login</p>
        </div>

        <!-- Login Form -->
        <div class="bg-gray-800 rounded-lg p-8 shadow-lg">
            <?php if ($login_error): ?>
                <div class="mb-4 p-4 bg-red-900 border border-red-600 rounded text-red-300">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold mb-2">Username</label>
                    <input type="text" name="username" required autofocus 
                           class="w-full px-4 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:border-gray-400 outline-none"
                           placeholder="Enter your username">
                    <p class="text-xs text-gray-500 mt-1">Demo: admin</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-2 bg-gray-700 text-white rounded border border-gray-600 focus:border-gray-400 outline-none"
                           placeholder="Enter your password">
                    <p class="text-xs text-gray-500 mt-1">Demo: admin123</p>
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded transition">
                    Login
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-700">
                <p class="text-gray-400 text-sm text-center">
                    <a href="../pages/index.html" class="text-gray-400 hover:text-gray-300">Back to Website</a>
                </p>
            </div>
        </div>

        <!-- Demo Credentials Info -->
        <div class="mt-6 bg-blue-900 border border-blue-600 rounded p-4">
            <p class="text-sm text-blue-200">
                <strong>Demo Login Credentials:</strong><br>
                Username: <code class="bg-blue-800 px-2 py-1 rounded">admin</code><br>
                Password: <code class="bg-blue-800 px-2 py-1 rounded">admin123</code>
            </p>
        </div>
    </div>
</div>

</body>
</html>


<?php
session_start();

// Hardcoded admin credentials (for demonstration purposes)
$admin_username = "admin";
$admin_password = "admin";

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // User is logged in, display the dashboard
} else {
    // User is not logged in, check login credentials
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate credentials
        if ($username === $admin_username && $password === $admin_password) {
            // Credentials are correct, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
        } else {
            // Invalid credentials, show error message
            $error_message = "Invalid username or password.";
        }
    }

    // If not logged in, display the login form
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .login-container {
                    max-width: 400px;
                    margin: 100px auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    background-color: #f8f9fa;
                }
                .login-container h2 {
                    text-align: center;
                    margin-bottom: 20px;
                }
                .error-message {
                    color: red;
                    text-align: center;
                    margin-bottom: 15px;
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <h2>Admin Login</h2>
                <?php if (isset($error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit(); // Stop further execution of the dashboard code
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        .dashboard-card {
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            margin: 10px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .dashboard-card:hover {
            background-color: #e9ecef;
        }
        .dashboard-card i {
            font-size: 50px;
            margin-bottom: 15px;
        }
        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .card-container .card {
            width: 18rem;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Admin Dashboard</h1>
    
    <!-- Cards for Car, Flight, Hotel, Vendor, and User Management -->
    <div class="card-container">
        <!-- Car Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/car'">
            <i class="fas fa-car"></i>
            <h4>Manage Cars</h4>
            <p>Click to manage the cars in your system.</p>
        </div>
        
        <!-- Flight Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/flight'">
            <i class="fas fa-plane"></i> <!-- Changed to plane icon for flight -->
            <h4>Manage Flights</h4>
            <p>Click to manage the flight details in your system.</p>
        </div>
        
        <!-- Hotel Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/hotel'">
            <i class="fas fa-hotel"></i> <!-- Changed to hotel icon for clarity -->
            <h4>Manage Hotels</h4>
            <p>Click to manage the hotels in your system.</p>
        </div>

        <!-- Vendor Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/vendor'">
            <i class="fas fa-briefcase"></i> <!-- Changed to briefcase icon for vendor -->
            <h4>Manage Vendors</h4>
            <p>Click to manage the vendors in your system.</p>
        </div>

        <!-- User Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/user'">
            <i class="fas fa-users"></i>
            <h4>Manage Users</h4>
            <p>Click to manage the users in your system.</p>
        </div>

        <!-- Location Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/location'">
            <i class="fas fa-map-marker-alt"></i> <!-- Changed to map marker icon for location -->
            <h4>Manage Location</h4>
            <p>Click to manage the locations in your system.</p>
        </div>        
    </div>
</div>
<center><a href=<?php echo BASE_URL;?>/login>Logout</a></center>
<!-- Bootstrap JS (Optional, but recommended for dynamic content) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
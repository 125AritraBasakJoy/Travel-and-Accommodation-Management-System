<?php
session_start();
if (!isset($_SESSION['vendor_id'])) {
    // Redirect to login if not logged in
    header("Location: login");
    exit();
}

$vendor_name = $_SESSION['vendor_name'];
$vendor_email = $_SESSION['email'];
$profile_status = $_SESSION['profile_status'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom keyframe for bounce effect */
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Dashboard Container -->
    <div class="max-w-4xl mx-auto bg-white p-8 mt-10 rounded-lg shadow-lg">
        <h2 class="text-3xl font-semibold text-center mb-6">Welcome, <?php echo htmlspecialchars($vendor_name); ?>!</h2>

        <!-- Vendor Profile Info -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold">Profile Information</h3>
            <p class="text-gray-700 mt-2">Email: <?php echo htmlspecialchars($vendor_email); ?></p>
            <p class="text-gray-700">Profile Status: 
                <span class="<?php echo $profile_status === 'Approved' ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo $profile_status; ?>
                </span>
            </p>
        </div>

        <!-- Dashboard Actions -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Manage Your Account</h3>
            <div class="flex space-x-4">
                <!-- Edit Profile Button -->
                <a href="edit_profile" class="w-full bg-blue-600 text-white py-2 rounded text-center transform transition duration-300 hover:bg-blue-700 hover:scale-105 hover:shadow-lg hover:animate-bounce">Edit Profile</a>

                <!-- Change Password Button -->
                <a href="change_password" class="w-full bg-gray-600 text-white py-2 rounded text-center transform transition duration-300 hover:bg-gray-700 hover:scale-105 hover:shadow-lg hover:animate-bounce">Change Password</a>
            </div>
        </div>

        <div class="flex space-x-4 mt-4">
            <a href="panel" class="w-full bg-blue-600 text-white py-2 rounded text-center transform transition duration-300 hover:bg-blue-700 hover:scale-105 hover:shadow-lg hover:animate-bounce">Panel</a>
        </div>
        
        <div class="flex space-x-4 mt-4">
            <a href="approver" class="w-full bg-blue-600 text-white py-2 rounded text-center transform transition duration-300 hover:bg-blue-700 hover:scale-105 hover:shadow-lg hover:animate-bounce">Manage Order</a>
        </div>        

        <!-- Log out Button --> 
        <div class="mt-6">
            <a href="login" class="w-full bg-red-600 text-white py-2 rounded text-center transform transition duration-300 hover:bg-red-700 hover:scale-105 hover:shadow-lg hover:animate-bounce">Log Out</a>
        </div>
    </div>

</body>
</html>
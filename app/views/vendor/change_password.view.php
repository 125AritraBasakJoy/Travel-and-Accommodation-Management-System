<?php session_start(); 
require "../app/models/VendorModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    // Check if user is logged in
    if (!isset($_SESSION['vendor_id'])) {
        $_SESSION['error_message'] = "You must be logged in to change your password.";
        header("Location: login");
        exit();
    }

    // Get vendor ID from session
    $vendor_id = $_SESSION['vendor_id'];

    // Create Vendor object
    $vendor = new Vendor();

    // Change password
    $result = $vendor->changePassword($vendor_id, $current_password, $new_password);

    if ($result === true) {
        $_SESSION['success_message'] = "Password changed successfully.";
    } else {
        $_SESSION['error_message'] = $result;
    }

    // Redirect to the change password page 
    header("Location: change_password");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white p-8 mt-20 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-center mb-6">Change Password</h2>

        <?php
            // Display error or success message
            if (isset($_SESSION['error_message'])) {
                echo "<div class='bg-red-500 text-white p-4 mb-4 rounded'>" . $_SESSION['error_message'] . "</div>";
                unset($_SESSION['error_message']);
            }
            if (isset($_SESSION['success_message'])) {
                echo "<div class='bg-green-500 text-white p-4 mb-4 rounded'>" . $_SESSION['success_message'] . "</div>";
                unset($_SESSION['success_message']);
            }
        ?>

        <form action="" method="POST">
            <div class="mb-4">
                <label for="current_password" class="block text-gray-700">Current Password</label>
                <input type="password" id="current_password" name="current_password" required class="w-full p-2 border border-gray-300 rounded mt-2">
            </div>
            <div class="mb-4">
                <label for="new_password" class="block text-gray-700">New Password</label>
                <input type="password" id="new_password" name="new_password" required class="w-full p-2 border border-gray-300 rounded mt-2">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Change Password</button>
        </form>
    </div>
</body>
</html>

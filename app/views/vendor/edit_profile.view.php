<?php
session_start();
require "../app/models/VendorModel.php";

// Ensure the user is logged in
if (!isset($_SESSION['vendor_id'])) {
    header("Location: login");
    exit();
}

$vendor_id = $_SESSION['vendor_id'];

// Create Vendor object to fetch current details
$vendor = new Vendor();
$currentVendor = $vendor->getVendorById($vendor_id);

// If vendor doesn't exist, redirect
if (!$currentVendor) {
    header("Location: login");
    exit();
}

$name = $currentVendor['name'];
$email = $currentVendor['email'];
$phone = $currentVendor['phone'];
$type = $currentVendor['type'];  // User cannot change this field
$profile_status = $currentVendor['profile_status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the updated data from the form
    $updatedName = $_POST['name'];
    $updatedEmail = $_POST['email'];
    $updatedPhone = $_POST['phone'];

    // Update vendor information
    $updateResult = $vendor->updateVendor($vendor_id, $updatedName, $updatedEmail, $updatedPhone, $type, $profile_status);

    if ($updateResult) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: dashboard");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto bg-white p-8 mt-10 rounded-lg shadow-lg">
        <h2 class="text-3xl font-semibold text-center mb-6">Edit Profile</h2>

        <?php
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
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required class="w-full p-2 border border-gray-300 rounded mt-2">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required class="w-full p-2 border border-gray-300 rounded mt-2" readonly>
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required class="w-full p-2 border border-gray-300 rounded mt-2">
            </div>

            <!-- Type field is not editable -->
            <div class="mb-4">
                <label for="type" class="block text-gray-700">Type</label>
                <input type="text" id="type" value="<?php echo htmlspecialchars($type); ?>" class="w-full p-2 border border-gray-300 rounded mt-2" readonly>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Save Changes</button>
        </form>
    </div>

</body>
</html>

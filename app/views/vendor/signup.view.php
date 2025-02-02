<?php
session_start();
require "../app/models/VendorModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $type = $_POST['type'] ?? '';

    // Create Vendor object
    $vendor = new Vendor();

    // Validate form data
    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($type)) {
        $_SESSION['error_message'] = 'All fields are required!';
        header("Location: signup");
        exit();
    }

    // Create new vendor
    $isCreated = $vendor->createVendor($name, $email, $password, $phone, $type);

    if ($isCreated) {
        // Vendor created successfully
        $_SESSION['success_message'] = 'Vendor account created successfully! Please wait for approval.';
        header("Location: login");
        exit();
    } else {
        // Vendor already exists
        $_SESSION['error_message'] = 'This email is already registered!';
        header("Location: signup");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .error-message {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white p-8 mt-20 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-center mb-6">Vendor Signup</h2>
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
<form action="" method="POST" onsubmit="return validateForm()">
    <div class="mb-4">
        <label for="name" class="block text-gray-700">Full Name</label>
        <input type="text" id="name" name="name" required class="w-full p-2 border border-gray-300 rounded mt-2">
        <div id="name-error" class="error-message"></div>
    </div>
    <div class="mb-4">
        <label for="email" class="block text-gray-700">Email</label>
        <input type="email" id="email" name="email" required class="w-full p-2 border border-gray-300 rounded mt-2">
        <div id="email-error" class="error-message"></div>
    </div>
    <div class="mb-4">
        <label for="password" class="block text-gray-700">Password</label>
        <input type="password" id="password" name="password" required class="w-full p-2 border border-gray-300 rounded mt-2">
        <div id="password-error" class="error-message"></div>
    </div>
    <div class="mb-4">
        <label for="confirm_password" class="block text-gray-700">Rewrite Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required class="w-full p-2 border border-gray-300 rounded mt-2">
        <div id="confirm-password-error" class="error-message"></div>
    </div>
    <div class="mb-4">
        <label for="phone" class="block text-gray-700">Phone</label>
        <input type="text" id="phone" name="phone" required class="w-full p-2 border border-gray-300 rounded mt-2">
        <div id="phone-error" class="error-message"></div>
    </div>
    <div class="mb-4">
        <label for="type" class="block text-gray-700">Vendor Type</label>
        <select id="type" name="type" required class="w-full p-2 border border-gray-300 rounded mt-2">
            <option value="">Select Vendor Type</option>
            <option value="hotel">Hotel</option>
            <option value="car">Car</option>
            <option value="flight">Flight</option>
        </select>
        <div id="type-error" class="error-message"></div>
    </div>
    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Sign Up</button>
</form>

        <p class="mt-4 text-center">
            Already have an account? <a href="login" class="text-blue-600">Login here</a>
        </p>
    </div>

    <script>
    function validateForm() {
    // Reset error messages
    document.querySelectorAll('.error-message').forEach(function(el) {
        el.textContent = '';
    });

    // Get form inputs
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const type = document.getElementById('type').value;

    let isValid = true;

    // Validate Name
    if (name === '') {
        document.getElementById('name-error').textContent = 'Full Name is required.';
        isValid = false;
    }

    // Validate Email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        document.getElementById('email-error').textContent = 'Email is required.';
        isValid = false;
    } else if (!emailPattern.test(email)) {
        document.getElementById('email-error').textContent = 'Please enter a valid email address.';
        isValid = false;
    }

    // Validate Password
    if (password === '') {
        document.getElementById('password-error').textContent = 'Password is required.';
        isValid = false;
    } else if (password.length < 6) {
        document.getElementById('password-error').textContent = 'Password must be at least 6 characters long.';
        isValid = false;
    }

    // Validate Confirm Password
    if (confirmPassword === '') {
        document.getElementById('confirm-password-error').textContent = 'Please rewrite your password.';
        isValid = false;
    } else if (password !== confirmPassword) {
        document.getElementById('confirm-password-error').textContent = 'Passwords do not match.';
        isValid = false;
    }

    // Validate Phone
    const phonePattern = /^\d{11}$/; // Assumes an 11-digit phone number
    if (phone === '') {
        document.getElementById('phone-error').textContent = 'Phone is required.';
        isValid = false;
    } else if (!phonePattern.test(phone)) {
        document.getElementById('phone-error').textContent = 'Please enter a valid 11-digit phone number.';
        isValid = false;
    }

    // Validate Vendor Type
    if (type === '') {
        document.getElementById('type-error').textContent = 'Vendor Type is required.';
        isValid = false;
    }

    return isValid;
}

    </script>
</body>
</html>
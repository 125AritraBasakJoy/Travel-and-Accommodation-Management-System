<?php session_start();
require "../app/models/VendorModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... (PHP code remains the same)
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white p-8 mt-20 rounded-lg shadow-lg">
        <center><a href="<?php echo BASE_URL ?>">Go Back to dashboard</a></center>
        <h2 class="text-2xl font-semibold text-center mb-6">Vendor Login</h2>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<div class='bg-red-500 text-white p-4 mb-4 rounded'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="" method="POST" id="loginForm">
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" required class="w-full p-2 border border-gray-300 rounded mt-2">
                <div id="emailError" class="text-red-500 text-sm mt-1"></div> </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="w-full p-2 border border-gray-300 rounded mt-2">
                <div id="passwordError" class="text-red-500 text-sm mt-1"></div>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
        </form>
        New ?<a href='<?php echo BASE_URL?>vendor/signup'>Create An Account</a>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        form.addEventListener('submit', (event) => {
    emailError.textContent = '';
    passwordError.textContent = '';

    let isValid = true;

    // Validate Email
    if (!emailInput.value.trim()) {
        emailError.textContent = 'Email is required.';
        isValid = false;
    } else if (!isValidEmail(emailInput.value)) {
        emailError.textContent = 'Invalid email format.';
        isValid = false;
    }

    // Validate Password
    if (!passwordInput.value.trim()) {
        passwordError.textContent = 'Password is required.';
        isValid = false;
    } else if (passwordInput.value.length < 6) {
        passwordError.textContent = 'Password must be at least 6 characters long.';
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault(); // Prevent form submission if validation fails
    }
});

function isValidEmail(email) {
    // Basic email validation regex
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

    </script>
</body>
</html>
<?php
// Done by Ashik Ibadullah
require "../app/models/UserModel.php";


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup Page</title>
  <script src="https://cdn.tailwindcss.com"></script>

   <script>
    // JavaScript validation for the signup form
    function validateForm() 
    {
    var password = document.getElementById("password").value;
    var retypePassword = document.getElementById("retype_password").value;
    
    // Check if passwords match
    if (password !== retypePassword) {
        alert("Passwords do not match!");
        return false; // Prevent form submission
    }

    // Check if password is at least 8 characters
    if (password.length < 8) {
        alert("Password must be at least 8 characters long!");
        return false; // Prevent form submission
    }

    // Check if all fields are filled
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;

    if (name === "" || email === "" || password === "" || phone === "") {
        alert("All fields must be filled out!");
        return false; // Prevent form submission
    }

    // Phone number validation: check for +88 prefix and 11 digits
    if (phone.startsWith("+88")) {
        phone = phone.slice(3); // Remove the "+88" prefix
    }

    // Check if the phone number is exactly 11 digits
    if (phone.length !== 11 || !/^\d+$/.test(phone)) {
        alert("Phone number must be exactly 11 digits!");
        return false; // Prevent form submission
    }

    return true; // Proceed with form submission if valid
   }

  </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<!-- Forms -->
  <!-- Signup Form Card -->
  <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-8">
    <h2 class="text-2xl font-bold text-center text-gray-700">Create an Account</h2>
    <p class="mt-2 text-sm text-center text-gray-600">Sign up to access your account</p>

    <!-- Success or Error Message -->
    <?php if ($message): ?>
    <div class="mt-4 p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800'; ?>">
      <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <form action="" method="POST" onsubmit="return validateForm()" class="mt-6 space-y-4">
      <!-- Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input
          id="name"
          name="name"
          type="text"
          required
          class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          placeholder="Enter your full name"
        />
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input
          id="email"
          name="email"
          type="email"
          required
          class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          placeholder="Enter your email"
        />
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input
          id="password"
          name="password"
          type="password"
          required
          class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          placeholder="Enter your password"
        />
      </div>

      <!-- Retype Password-->
      <div>
        <label for="retype_password" class="block text-sm font-medium text-gray-700">Retype Password</label>
        <input
          id="retype_password"
          name="retype_password"
          type="password"
          required
          class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          placeholder="Retype your password"
        />
      </div>

      <!-- Phone Field -->
      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
        <input
          id="phone"
          name="phone"
          type="text"
          required
          class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          placeholder="Enter your phone number"
        />
      </div>

      <!-- Signup Button -->
      <button
        type="submit"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-400 focus:outline-none"
      >
        Sign Up
      </button>
    </form>

    <!-- Redirect to Login -->
    <div class="mt-6 text-sm text-center text-gray-500">
      Already have an account? 
      <a href="login" class="text-blue-600 hover:underline">Log in</a>
    </div>
  </div>

</body>
</html>

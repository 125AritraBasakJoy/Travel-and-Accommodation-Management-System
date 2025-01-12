<?php
require "../app/models/UserModel.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <!-- Login Card -->
  <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold text-center text-gray-700">Login</h2>
    <p class="mt-2 text-center text-sm text-gray-600">Welcome back! Please log in to your account.</p>
<?php // Example usage
// Example usage
if(isset($_POST['email'])&&isset($_POST['password'])){$userModel = new UserModel();

$email = $_POST['email'];
$password = $_POST['password'];

$user = $userModel->validateLogin($email, $password);

if ($user) {
    echo "Login successful. Welcome, " . $user['name'];
} else {
    echo "Invalid email or password.";
}}
?>
    <form class="mt-6 space-y-4" method="POST">
      <!-- Email Input -->
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

      <!-- Password Input -->
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

      <!-- Remember Me Checkbox -->
      <div class="flex items-center justify-between">
        <label class="flex items-center space-x-2 text-sm">
          <input
            type="checkbox"
            class="rounded border-gray-300 text-blue-500 focus:ring-blue-400"
          />
          <span>Remember me</span>
        </label>
        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
      </div>

      <!-- Login Button -->
      <button
        type="submit"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-400 focus:outline-none"
      >
        Login
      </button>
    </form>

    <!-- Divider -->
    <div class="mt-6 text-sm text-center text-gray-500">
      Don't have an account? 
      <a href="signup" class="text-blue-600 hover:underline">Sign up</a>
    </div>
  </div>

</body>
</html>

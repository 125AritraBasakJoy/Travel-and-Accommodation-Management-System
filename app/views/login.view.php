<?php
session_start(); // Start the session

require "../app/models/UserModel.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $userModel = new UserModel();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $userModel->validateLogin($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: home"); // Redirect to home page
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold text-center text-gray-700">Login</h2>
    <p class="mt-2 text-center text-sm text-gray-600">Welcome back! Please log in to your account.</p>
    
    <?php if (!empty($error)): ?>
      <p class="mt-4 text-center text-red-500"> <?= htmlspecialchars($error) ?> </p>
    <?php endif; ?>
    
    <form class="mt-6 space-y-4" method="POST">
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" required class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your email" />
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input id="password" name="password" type="password" required class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your password" />
      </div>

      <div class="flex items-center justify-between">
        <label class="flex items-center space-x-2 text-sm">
          <input type="checkbox" class="rounded border-gray-300 text-blue-500 focus:ring-blue-400" />
          <span>Remember me</span>
        </label>
        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
      </div>

      <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-400 focus:outline-none">Login</button>
    </form>
    
    <div class="mt-6 text-sm text-center text-gray-500">
      Don't have an account? <a href="signup.php" class="text-blue-600 hover:underline">Sign up</a>
    </div>
  </div>
</body>
</html>

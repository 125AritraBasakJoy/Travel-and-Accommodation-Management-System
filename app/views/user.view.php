

<?php include('../public/inc/header.php'); ?>
<?php

require "../app/models/UserModel.php";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}

$userModel = new UserModel();
$user = $userModel->getUserById($_SESSION['user_id']);

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle form submission for updating profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $updateStatus = $userModel->updateUser($_SESSION['user_id'], $name, $email, $phone);

    if ($updateStatus) {
        $_SESSION['user_name'] = $name; // Update session data
        header("Location: user?success=1");
        exit();
    } else {
        $error = "Failed to update profile.";
    }
}
?>
<div class="container mx-auto my-10 p-6 bg-white shadow-lg rounded-lg w-full max-w-lg">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">Edit Profile</h2>

    <?php if (isset($_GET['success'])): ?>
        <p class="text-green-500 text-center mt-2">Profile updated successfully!</p>
    <?php elseif (isset($error)): ?>
        <p class="text-red-500 text-center mt-2"><?= $error ?></p>
    <?php endif; ?>

    <form action="" method="POST" class="mt-4">
        <div class="mb-4">
            <label class="block text-gray-600 text-sm font-semibold mb-2">Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600 text-sm font-semibold mb-2">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="mb-4">
            <label class="block text-gray-600 text-sm font-semibold mb-2">Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <button type="submit"
            class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
            Save Changes
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="home" class="text-blue-500 hover:underline">Back to Dashboard</a>
    </div>
</div>

</body>
</html>

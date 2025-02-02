<?php
require "../app/models/UserModel.php";

$userModel = new UserModel();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'create') {
            $userModel->createUser(
                $_POST['name'],
                $_POST['email'],
                $_POST['password'],
                $_POST['phone']
            );
            $message = "User created successfully!";
        } elseif ($action === 'update') {
            $userModel->updateUser(
                $_POST['user_id'],
                $_POST['name'],
                $_POST['email'],
                $_POST['phone']
            );
            $message = "User updated successfully!";
        } elseif ($action === 'delete') {
            $userModel->deleteUser($_POST['user_id']);
            $message = "User deleted successfully!";
        }
    }
}

$users = $userModel->getAllUsers();
$message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <div class="container mx-auto my-5">
        <h1 class="text-2xl font-bold mb-5">Manage Users</h1>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- User Form -->
        <form method="POST" class="mb-5 p-5 border rounded">
            <input type="hidden" id="form-action" name="action" value="create">
            <input type="hidden" id="user_id" name="user_id">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-gray-700">Name</label>
                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="phone" class="block text-gray-700">Phone</label>
                    <input type="text" id="phone" name="phone" class="w-full px-3 py-2 border rounded" required>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="resetForm()">Reset</button>
            </div>
        </form>

        <!-- User List -->
        <table class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">User ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Phone</th>
                    <th class="px-4 py-2 border">Created At</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($user['user_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($user['name']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($user['phone']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($user['created_at']) ?></td>
                        <td class="px-4 py-2 border">
                            <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                                    onclick="editUser(<?= htmlspecialchars(json_encode($user)) ?>)">
                                Edit
                            </button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editUser(user) {
            document.getElementById('form-action').value = 'update';
            document.getElementById('user_id').value = user.user_id;
            document.getElementById('name').value = user.name;
            document.getElementById('email').value = user.email;
            document.getElementById('phone').value = user.phone;
        }

        function resetForm() {
            document.getElementById('form-action').value = 'create';
            document.getElementById('user_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            document.getElementById('phone').value = '';
        }
    </script>
</body>
</html>

<?php
require "../app/models/VendorModel.php";

$vendorModel = new Vendor();
$message = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create') {
            $result = $vendorModel->createVendor(
                $_POST['name'],
                $_POST['email'],
                $_POST['password'],
                $_POST['phone'],
                $_POST['type']
            );
            $message = $result ? "Vendor created successfully!" : "Vendor creation failed (email may already exist).";
        } elseif ($action === 'update') {
            $result = $vendorModel->updateVendor(
                $_POST['vendor_id'],
                $_POST['name'],
                $_POST['email'],
                $_POST['phone'],
                $_POST['type'],
                $_POST['profile_status']
            );
            $message = $result ? "Vendor updated successfully!" : "Vendor update failed (email may already exist).";
        } elseif ($action === 'delete') {
            $result = $vendorModel->deleteVendor($_POST['vendor_id']);
            $message = $result ? "Vendor deleted successfully!" : "Vendor deletion failed.";
        }
    }
}

$vendors = $vendorModel->getAllVendors();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vendors</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
<div class="container mx-auto my-5">
    <h1 class="text-2xl font-bold mb-5">Manage Vendors</h1>

    <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Vendor Form -->
    <form method="POST" class="mb-5 p-5 border rounded">
        <input type="hidden" id="form-action" name="action" value="create">
        <input type="hidden" id="vendor_id" name="vendor_id">

        <div class="grid grid-cols-3 gap-4">
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
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label for="phone" class="block text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label for="type" class="block text-gray-700">Type</label>
                <input type="text" id="type" name="type" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label for="profile_status" class="block text-gray-700">Profile Status</label>
                <select id="profile_status" name="profile_status" class="w-full px-3 py-2 border rounded">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="resetForm()">Reset</button>
        </div>
    </form>

    <!-- Vendor List -->
    <table class="table-auto w-full border">
        <thead>
        <tr>
            <th class="px-4 py-2 border">Vendor ID</th>
            <th class="px-4 py-2 border">Name</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Phone</th>
            <th class="px-4 py-2 border">Type</th>
            <th class="px-4 py-2 border">Profile Status</th>
            <th class="px-4 py-2 border">Created At</th>
            <th class="px-4 py-2 border">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($vendors as $vendor): ?>
            <tr>
                <td class="px-4 py-2 border"><?= htmlspecialchars($vendor['vendor_id']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($vendor['name']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($vendor['email']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($vendor['phone']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($vendor['type']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($vendor['profile_status']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($vendor['created_at']) ?></td>
                <td class="px-4 py-2 border">
                    <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                            onclick="editVendor(<?= htmlspecialchars(json_encode($vendor)) ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="vendor_id" value="<?= htmlspecialchars($vendor['vendor_id']) ?>">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function editVendor(vendor) {
        document.getElementById('form-action').value = 'update';
        document.getElementById('vendor_id').value = vendor.vendor_id;
        document.getElementById('name').value = vendor.name;
        document.getElementById('email').value = vendor.email;
        document.getElementById('phone').value = vendor.phone;
        document.getElementById('type').value = vendor.type;
        document.getElementById('profile_status').value = vendor.profile_status;
    }

    function resetForm() {
        document.getElementById('form-action').value = 'create';
        document.getElementById('vendor_id').value = '';
        document.getElementById('name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
        document.getElementById('phone').value = '';
        document.getElementById('type').value = '';
        document.getElementById('profile_status').value = 'Pending';
    }
</script>
</body>
</html>

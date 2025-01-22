<?php
require "../app/models/Location.php";

$Location = new Location();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'create') {
            $locationPhoto = $_FILES['locationPhoto']['name'];
            move_uploaded_file($_FILES['locationPhoto']['tmp_name'], "../public/uploads/" . $locationPhoto);

            $Location->createLocation(
                $_POST['city'],
                $_POST['state'],
                $_POST['country'],
                $locationPhoto
            );
            $message = "Location created successfully!";
        } elseif ($action === 'update') {
            $locationPhoto = $_FILES['locationPhoto']['name'];
            if ($locationPhoto) {
                move_uploaded_file($_FILES['locationPhoto']['tmp_name'], "../public/uploads/" . $locationPhoto);
            } else {
                $locationPhoto = $_POST['existingPhoto'];
            }

            $Location->updateLocation(
                $_POST['location_id'],
                $_POST['city'],
                $_POST['state'],
                $_POST['country'],
                $locationPhoto
            );
            $message = "Location updated successfully!";
        } elseif ($action === 'delete') {
            $Location->deleteLocation($_POST['location_id']);
            $message = "Location deleted successfully!";
        }
    }
}

$locations = $Location->getAllLocations();
$message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Locations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <div class="container mx-auto my-5">
        <h1 class="text-2xl font-bold mb-5">Manage Locations</h1>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Location Form -->
        <form method="POST" enctype="multipart/form-data" class="mb-5 p-5 border rounded">
            <input type="hidden" id="form-action" name="action" value="create">
            <input type="hidden" id="location_id" name="location_id">
            <input type="hidden" id="existingPhoto" name="existingPhoto">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="city" class="block text-gray-700">City</label>
                    <input type="text" id="city" name="city" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="state" class="block text-gray-700">State</label>
                    <input type="text" id="state" name="state" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="country" class="block text-gray-700">Country</label>
                    <input type="text" id="country" name="country" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="locationPhoto" class="block text-gray-700">Location Photo</label>
                    <input type="file" id="locationPhoto" name="locationPhoto" class="w-full px-3 py-2 border rounded">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="resetForm()">Reset</button>
            </div>
        </form>

        <!-- Location List -->
        <table class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Location ID</th>
                    <th class="px-4 py-2 border">City</th>
                    <th class="px-4 py-2 border">State</th>
                    <th class="px-4 py-2 border">Country</th>
                    <th class="px-4 py-2 border">Photo</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $location): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($location['location_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($location['city']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($location['state']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($location['country']) ?></td>
                        <td class="px-4 py-2 border">
                            <img src="../uploads/<?= htmlspecialchars($location['locationPhoto']) ?>" alt="Location Photo" class="w-16 h-16 object-cover">
                        </td>
                        <td class="px-4 py-2 border">
                            <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                                    onclick="editLocation(<?= htmlspecialchars(json_encode($location)) ?>)">
                                Edit
                            </button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="location_id" value="<?= htmlspecialchars($location['location_id']) ?>">
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
        function editLocation(location) {
            document.getElementById('form-action').value = 'update';
            document.getElementById('location_id').value = location.location_id;
            document.getElementById('city').value = location.city;
            document.getElementById('state').value = location.state;
            document.getElementById('country').value = location.country;
            document.getElementById('existingPhoto').value = location.locationPhoto;
        }

        function resetForm() {
            document.getElementById('form-action').value = 'create';
            document.getElementById('location_id').value = '';
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            document.getElementById('country').value = '';
            document.getElementById('existingPhoto').value = '';
            document.getElementById('locationPhoto').value = '';
        }
    </script>
</body>
</html>

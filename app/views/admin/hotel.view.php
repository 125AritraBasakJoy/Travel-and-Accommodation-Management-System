<?php
require "../app/models/HotelModel.php";

$hotelModel = new HotelModel();


// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'create') {
            $hotelPhoto = $_FILES['hotelPhoto']['name'];
            move_uploaded_file($_FILES['hotelPhoto']['tmp_name'], "../public/uploads/" . $hotelPhoto);

            $hotelModel->createHotel(
                $_POST['vendor_id'],
                $_POST['name'],
                $_POST['location_id'],
                $_POST['description'],
                $_POST['price_per_night'],
                $_POST['capacity'],
                $hotelPhoto
            );
            $message = "Hotel created successfully!";
        } elseif ($action === 'update') {
            $hotelPhoto = $_FILES['hotelPhoto']['name']; 
            if ($hotelPhoto) {
                move_uploaded_file($_FILES['hotelPhoto']['tmp_name'], "../public/uploads/" . $hotelPhoto);
            } else {
                $hotelPhoto = $_POST['existingPhoto'];
            }

            $hotelModel->updateHotel(
                $_POST['hotel_id'],
                $_POST['vendor_id'],
                $_POST['name'],
                $_POST['location_id'],
                $_POST['description'],
                $_POST['price_per_night'],
                $_POST['capacity'],
                $hotelPhoto
            );
            $message = "Hotel updated successfully!";
        } elseif ($action === 'delete') {
            $hotelModel->deleteHotel($_POST['hotel_id']);
            $message = "Hotel deleted successfully!";
        }
    }
}

$hotels = $hotelModel->getAllHotels();
$message = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hotels</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <div class="container mx-auto my-5">
        <h1 class="text-2xl font-bold mb-5">Manage Hotels</h1>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Hotel Form -->
        <form method="POST" enctype="multipart/form-data" class="mb-5 p-5 border rounded">
            <input type="hidden" id="form-action" name="action" value="create">
            <input type="hidden" id="hotel_id" name="hotel_id">
            <input type="hidden" id="existingPhoto" name="existingPhoto">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="vendor_id" class="block text-gray-700">Vendor ID</label>
                    <input type="text" id="vendor_id" name="vendor_id" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="name" class="block text-gray-700">Hotel Name</label>
                    <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="location_id" class="block text-gray-700">Location ID</label>
                    <input type="text" id="location_id" name="location_id" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea id="description" name="description" class="w-full px-3 py-2 border rounded" required></textarea>
                </div>
                <div>
                    <label for="price_per_night" class="block text-gray-700">Price Per Night</label>
                    <input type="number" id="price_per_night" name="price_per_night" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="capacity" class="block text-gray-700">Capacity</label>
                    <input type="number" id="capacity" name="capacity" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="hotelPhoto" class="block text-gray-700">Hotel Photo</label>
                    <input type="file" id="hotelPhoto" name="hotelPhoto" class="w-full px-3 py-2 border rounded">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="resetForm()">Reset</button>
            </div>
        </form>

        <!-- Hotel List -->
        <table class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Hotel ID</th>
                    <th class="px-4 py-2 border">Vendor ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Location</th>
                    <th class="px-4 py-2 border">Description</th>
                    <th class="px-4 py-2 border">Price/Night</th>
                    <th class="px-4 py-2 border">Capacity</th>
                    <th class="px-4 py-2 border">Photo</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hotels as $hotel): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($hotel['hotel_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($hotel['vendor_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($hotel['name']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($hotel['location_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($hotel['description']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($hotel['price_per_night']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($hotel['capacity']) ?></td>
                        <td class="px-4 py-2 border">
                            <img src="../uploads/<?= htmlspecialchars($hotel['hotelPhoto']) ?>" alt="Hotel Photo" class="w-16 h-16 object-cover">
                        </td>
                        <td class="px-4 py-2 border">
                            <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                                    onclick="editHotel(<?= htmlspecialchars(json_encode($hotel)) ?>)">
                                Edit
                            </button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="hotel_id" value="<?= htmlspecialchars($hotel['hotel_id']) ?>">
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
        function editHotel(hotel) {
            document.getElementById('form-action').value = 'update';
            document.getElementById('hotel_id').value = hotel.hotel_id;
            document.getElementById('vendor_id').value = hotel.vendor_id;
            document.getElementById('name').value = hotel.name;
            document.getElementById('location_id').value = hotel.location_id;
            document.getElementById('description').value = hotel.description;
            document.getElementById('price_per_night').value = hotel.price_per_night;
            document.getElementById('capacity').value = hotel.capacity;
            document.getElementById('existingPhoto').value = hotel.hotelPhoto;
        }

        function resetForm() {
            document.getElementById('form-action').value = 'create';
            document.getElementById('hotel_id').value = '';
            document.getElementById('vendor_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('location_id').value = '';
            document.getElementById('description').value = '';
            document.getElementById('price_per_night').value = '';
            document.getElementById('capacity').value = '';
            document.getElementById('existingPhoto').value = '';
            document.getElementById('hotelPhoto').value = '';
        }
    </script>
</body>
</html>

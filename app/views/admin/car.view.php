<?php
require "../app/models/CarModel.php"; // Ensure you include your CarModel class

$carModel = new CarModel();
$message = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'create') {
            // Handle file upload
            $carPhoto = "";
            if (isset($_FILES['carPhoto']) && $_FILES['carPhoto']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "../public/uploads/car/";
                $targetFile = $targetDir . basename($_FILES["carPhoto"]["name"]);
                if (move_uploaded_file($_FILES["carPhoto"]["tmp_name"], $targetFile)) {
                    $carPhoto = basename($_FILES["carPhoto"]["name"]);
                }
            }
            // Create a new car
            $carModel->createCar($_POST['vendor_id'], $_POST['model'], $_POST['make_year'], $_POST['seats'], $_POST['price_per_hour'], $_POST['location_id'], $carPhoto);
            $message = "Car created successfully!";
        } elseif ($action === 'update') {
            // Handle file upload for update
            $carPhoto = $_POST['existing_carPhoto']; // Keep the existing photo if not updated
            if (isset($_FILES['carPhoto']) && $_FILES['carPhoto']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "../public/uploads/car/";
                $targetFile = $targetDir . basename($_FILES["carPhoto"]["name"]);
                if (move_uploaded_file($_FILES["carPhoto"]["tmp_name"], $targetFile)) {
                    $carPhoto = basename($_FILES["carPhoto"]["name"]);
                }
            }
            // Update an existing car
            $carModel->updateCar($_POST['car_id'], $_POST['vendor_id'], $_POST['model'], $_POST['make_year'], $_POST['seats'], $_POST['price_per_hour'], $_POST['location_id'], $carPhoto);
            $message = "Car updated successfully!";
        } elseif ($action === 'delete') {
            // Delete a car
            $carModel->deleteCar($_POST['car_id']);
            $message = "Car deleted successfully!";
        }
    }
}

// Fetch all cars
$cars = $carModel->getAllCars();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Management Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-4">Car Management Dashboard</h1>
        
        <!-- Display Message -->
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Car Form -->
        <form method="POST" class="mb-6 bg-white p-6 rounded shadow-md" enctype="multipart/form-data">
            <h2 class="text-xl font-bold mb-4">Add / Update Car</h2>
            <input type="hidden" name="action" value="create" id="form-action">
            <input type="hidden" name="car_id" id="car_id">
            <input type="hidden" name="existing_carPhoto" id="existing_carPhoto"> <!-- For keeping existing photo during update -->
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="vendor_id" id="vendor_id" placeholder="Vendor ID" class="border p-2 rounded" required>
                <input type="text" name="model" id="model" placeholder="Model" class="border p-2 rounded" required>
                <input type="number" name="make_year" id="make_year" placeholder="Make Year" class="border p-2 rounded" required>
                <input type="number" name="seats" id="seats" placeholder="Seats" class="border p-2 rounded" required>
                <input type="text" name="price_per_hour" id="price_per_hour" placeholder="Price Per Hour" class="border p-2 rounded" required>
                <input type="text" name="location_id" id="location_id" placeholder="Location ID" class="border p-2 rounded" required>
                <!-- New Car Photo input -->
                <input type="file" name="carPhoto" id="carPhoto" class="border p-2 rounded">
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                <button type="reset" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="resetForm()">Cancel</button>
            </div>
        </form>

        <!-- Car Table -->
        <h2 class="text-xl font-bold mb-4">All Cars</h2>
        <table class="table-auto w-full bg-white rounded shadow-md">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Car ID</th>
                    <th class="px-4 py-2">Vendor ID</th>
                    <th class="px-4 py-2">Model</th>
                    <th class="px-4 py-2">Make Year</th>
                    <th class="px-4 py-2">Seats</th>
                    <th class="px-4 py-2">Price/Hour</th>
                    <th class="px-4 py-2">Location ID</th>
                    <th class="px-4 py-2">Car Photo</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?php echo $car['car_id']; ?></td>
                        <td class="px-4 py-2"><?php echo $car['vendor_id']; ?></td>
                        <td class="px-4 py-2"><?php echo $car['model']; ?></td>
                        <td class="px-4 py-2"><?php echo $car['make_year']; ?></td>
                        <td class="px-4 py-2"><?php echo $car['seats']; ?></td>
                        <td class="px-4 py-2"><?php echo $car['price_per_hour']; ?></td>
                        <td class="px-4 py-2"><?php echo $car['location_id']; ?></td>
                        <td class="px-4 py-2">
                            <?php if ($car['carPhoto']): ?>
                                <img src="../uploads/<?php echo $car['carPhoto']; ?>" alt="Car Photo" class="w-16 h-16 object-cover">
                            <?php else: ?>
                                No Photo
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-2">
                            <button class="bg-yellow-500 text-white px-2 py-1 rounded" onclick="editCar(<?php echo htmlspecialchars(json_encode($car)); ?>)">Edit</button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editCar(car) {
            document.getElementById('form-action').value = 'update';
            document.getElementById('car_id').value = car.car_id;
            document.getElementById('vendor_id').value = car.vendor_id;
            document.getElementById('model').value = car.model;
            document.getElementById('make_year').value = car.make_year;
            document.getElementById('seats').value = car.seats;
            document.getElementById('price_per_hour').value = car.price_per_hour;
            document.getElementById('location_id').value = car.location_id;
            document.getElementById('existing_carPhoto').value = car.carPhoto; // Set the existing car photo
        }

        function resetForm() {
            document.getElementById('form-action').value = 'create';
            document.getElementById('car_id').value = '';
            document.getElementById('existing_carPhoto').value = '';
        }
    </script>
</body>
</html>

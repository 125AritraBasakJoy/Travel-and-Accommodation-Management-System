<?php
session_start();
require "../app/models/CarModel.php";
require "../app/models/FlightModel.php";
require "../app/models/HotelModel.php";

$carModel = new CarModel();
$flightModel = new FlightModel();
$hotelModel = new HotelModel();
$message = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($_SESSION['type'] === 'car') {
            if ($action === 'create') {
                $carPhoto = "";
                if (isset($_FILES['carPhoto']) && $_FILES['carPhoto']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "../public/uploads/car/";
                    $targetFile = $targetDir . basename($_FILES["carPhoto"]["name"]);
                    if (move_uploaded_file($_FILES["carPhoto"]["tmp_name"], $targetFile)) {
                        $carPhoto = basename($_FILES["carPhoto"]["name"]);
                    }
                }
                $carModel->createCar($_POST['vendor_id'], $_POST['model'], $_POST['make_year'], $_POST['seats'], $_POST['price_per_hour'], $_POST['location_id'], $carPhoto);
                $message = "Car created successfully!";
            } elseif ($action === 'update') {
                $carPhoto = $_POST['existing_carPhoto'];
                if (isset($_FILES['carPhoto']) && $_FILES['carPhoto']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "../public/uploads/car/";
                    $targetFile = $targetDir . basename($_FILES["carPhoto"]["name"]);
                    if (move_uploaded_file($_FILES["carPhoto"]["tmp_name"], $targetFile)) {
                        $carPhoto = basename($_FILES["carPhoto"]["name"]);
                    }
                }
                $carModel->updateCar($_POST['car_id'], $_POST['vendor_id'], $_POST['model'], $_POST['make_year'], $_POST['seats'], $_POST['price_per_hour'], $_POST['location_id'], $carPhoto);
                $message = "Car updated successfully!";
            } elseif ($action === 'delete') {
                $carModel->deleteCar($_POST['car_id']);
                $message = "Car deleted successfully!";
            }
        } elseif ($_SESSION['type'] === 'flight') {
            if ($action === 'create') {
                $flightModel->createFlight(
                    $_POST['vendor_id'],
                    $_POST['flight_number'],
                    $_POST['departure_location_id'],
                    $_POST['arrival_location_id'],
                    $_POST['departure_time'],
                    $_POST['arrival_time'],
                    $_POST['price']
                );
                $message = "Flight created successfully!";
            } elseif ($action === 'update') {
                $flightModel->updateFlight(
                    $_POST['flight_id'],
                    $_POST['vendor_id'],
                    $_POST['flight_number'],
                    $_POST['departure_location_id'],
                    $_POST['arrival_location_id'],
                    $_POST['departure_time'],
                    $_POST['arrival_time'],
                    $_POST['price']
                );
                $message = "Flight updated successfully!";
            } elseif ($action === 'delete') {
                $flightModel->deleteFlight($_POST['flight_id']);
                $message = "Flight deleted successfully!";
            }
        } elseif ($_SESSION['type'] === 'hotel') {
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
}

// Fetch data based on vendor type
if ($_SESSION['type'] === 'car') {
    $data = $carModel->getAllCars();
} elseif ($_SESSION['type'] === 'flight') {
    $data = $flightModel->getAllFlights();
} elseif ($_SESSION['type'] === 'hotel') {
    $data = $hotelModel->getAllHotels();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Management Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-4"><?php echo ucfirst($_SESSION['type']); ?> Management Dashboard</h1>
        
        <!-- Display Message -->
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="mb-6 bg-white p-6 rounded shadow-md" enctype="multipart/form-data">
            <h2 class="text-xl font-bold mb-4">Add / Update <?php echo ucfirst($_SESSION['type']); ?></h2>
            <input type="hidden" name="action" value="create" id="form-action">
            <input type="hidden" name="<?php echo $_SESSION['type']; ?>_id" id="entity_id">
            <input type="hidden" name="existing_photo" id="existing_photo">
<?php $_SESSION['vendor_id']?>
            <?php if ($_SESSION['type'] === 'car'): ?>
                <div class="grid grid-cols-2 gap-4">
                    <input type="hidden" name="vendor_id" id="vendor_id" placeholder="Vendor ID" class="border p-2 rounded" value="<?php echo $_SESSION['vendor_id']?>" required>
                    <input type="text" name="model" id="model" placeholder="Model" class="border p-2 rounded" required>
                    <input type="number" name="make_year" id="make_year" placeholder="Make Year" class="border p-2 rounded" required>
                    <input type="number" name="seats" id="seats" placeholder="Seats" class="border p-2 rounded" required>
                    <input type="text" name="price_per_hour" id="price_per_hour" placeholder="Price Per Hour" class="border p-2 rounded" required>
                    <input type="text" name="location_id" id="location_id" placeholder="Location ID" class="border p-2 rounded" required>
                    <input type="file" name="carPhoto" id="carPhoto" class="border p-2 rounded">
                </div>
            <?php elseif ($_SESSION['type'] === 'flight'): ?>
                <div class="grid grid-cols-2 gap-4">
                    <input type="hidden" name="vendor_id" id="vendor_id" placeholder="Vendor ID" class="border p-2 rounded" value="<?php echo $_SESSION['vendor_id']?>"  required>
                    <input type="text" name="flight_number" id="flight_number" placeholder="Flight Number" class="border p-2 rounded" required>
                    <input type="text" name="departure_location_id" id="departure_location_id" placeholder="Departure Location ID" class="border p-2 rounded" required>
                    <input type="text" name="arrival_location_id" id="arrival_location_id" placeholder="Arrival Location ID" class="border p-2 rounded" required>
                    <input type="datetime-local" name="departure_time" id="departure_time" class="border p-2 rounded" required>
                    <input type="datetime-local" name="arrival_time" id="arrival_time" class="border p-2 rounded" required>
                    <input type="number" name="price" id="price" placeholder="Price" class="border p-2 rounded" required>
                </div>
            <?php elseif ($_SESSION['type'] === 'hotel'): ?>
                <div class="grid grid-cols-2 gap-4">
                    <input type="hidden" name="vendor_id" id="vendor_id" placeholder="Vendor ID" class="border p-2 rounded" value="<?php echo $_SESSION['vendor_id']?>"  required>
                    <input type="text" name="name" id="name" placeholder="Hotel Name" class="border p-2 rounded" required>
                    <input type="text" name="location_id" id="location_id" placeholder="Location ID" class="border p-2 rounded" required>
                    <textarea name="description" id="description" placeholder="Description" class="border p-2 rounded" required></textarea>
                    <input type="number" name="price_per_night" id="price_per_night" placeholder="Price Per Night" class="border p-2 rounded" required>
                    <input type="number" name="capacity" id="capacity" placeholder="Capacity" class="border p-2 rounded" required>
                    <input type="file" name="hotelPhoto" id="hotelPhoto" class="border p-2 rounded">
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                <button type="reset" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="resetForm()">Cancel</button>
            </div>
        </form>

        <!-- Table -->
        <h2 class="text-xl font-bold mb-4">All <?php echo ucfirst($_SESSION['type']); ?>s</h2>
        <table class="table-auto w-full bg-white rounded shadow-md">
            <thead>
                <tr class="bg-gray-200">
                    <?php if ($_SESSION['type'] === 'car'): ?>
                        <th class="px-4 py-2">Car ID</th>
                        <th class="px-4 py-2">Vendor ID</th>
                        <th class="px-4 py-2">Model</th>
                        <th class="px-4 py-2">Make Year</th>
                        <th class="px-4 py-2">Seats</th>
                        <th class="px-4 py-2">Price/Hour</th>
                        <th class="px-4 py-2">Location ID</th>
                        <th class="px-4 py-2">Car Photo</th>
                    <?php elseif ($_SESSION['type'] === 'flight'): ?>
                        <th class="px-4 py-2">Flight ID</th>
                        <th class="px-4 py-2">Vendor ID</th>
                        <th class="px-4 py-2">Flight Number</th>
                        <th class="px-4 py-2">Departure</th>
                        <th class="px-4 py-2">Arrival</th>
                        <th class="px-4 py-2">Departure Time</th>
                        <th class="px-4 py-2">Arrival Time</th>
                        <th class="px-4 py-2">Price</th>
                    <?php elseif ($_SESSION['type'] === 'hotel'): ?>
                        <th class="px-4 py-2">Hotel ID</th>
                        <th class="px-4 py-2">Vendor ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Location</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Price/Night</th>
                        <th class="px-4 py-2">Capacity</th>
                        <th class="px-4 py-2">Photo</th>
                    <?php endif; ?>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>



<?php foreach ($data as $item): ?>
    <?php if ($item['vendor_id'] == $_SESSION['vendor_id']): ?>
        <tr class="border-b">
            <?php if ($_SESSION['type'] == 'car'): ?>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['car_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['vendor_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['model']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['make_year']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['seats']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['price_per_hour']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['location_id']); ?></td>
                <td class="px-4 py-2">
                    <?php if ($item['carPhoto']): ?>
                        <img src="../uploads/car/<?php echo htmlspecialchars($item['carPhoto']); ?>" alt="Car Photo" class="w-16 h-16 object-cover">
                    <?php else: ?>
                        No Photo
                    <?php endif; ?>
                </td>
            <?php elseif ($_SESSION['type'] == 'flight'): ?>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['flight_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['vendor_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['flight_number']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['departure_location_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['arrival_location_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['departure_time']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['arrival_time']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['price']); ?></td>
            <?php elseif ($_SESSION['type'] == 'hotel'): ?>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['hotel_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['vendor_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['name']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['location_id']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['description']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['price_per_night']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($item['capacity']); ?></td>
                <td class="px-4 py-2">
                    <img src="../uploads/<?php echo htmlspecialchars($item['hotelPhoto']); ?>" alt="Hotel Photo" class="w-16 h-16 object-cover">
                </td>
            <?php endif; ?>
            <td class="px-4 py-2">
                <button class="bg-yellow-500 text-white px-2 py-1 rounded" onclick="editEntity(<?php echo htmlspecialchars(json_encode($item)); ?>)">Edit</button>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="<?php echo htmlspecialchars($_SESSION['type']); ?>_id" value="<?php echo htmlspecialchars($item[$_SESSION['type'] . '_id']); ?>">
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                </form>
            </td>
        </tr>
    <?php endif; ?>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function editEntity(entity) {
            document.getElementById('form-action').value = 'update';
            document.getElementById('entity_id').value = entity[<?php echo json_encode($_SESSION['type'] . '_id'); ?>];
            document.getElementById('existing_photo').value = entity[<?php echo json_encode($_SESSION['type'] === 'car' ? 'carPhoto' : ($_SESSION['type'] === 'hotel' ? 'hotelPhoto' : '')); ?>];

            <?php if ($_SESSION['type'] === 'car'): ?>
                document.getElementById('vendor_id').value = entity.vendor_id;
                document.getElementById('model').value = entity.model;
                document.getElementById('make_year').value = entity.make_year;
                document.getElementById('seats').value = entity.seats;
                document.getElementById('price_per_hour').value = entity.price_per_hour;
                document.getElementById('location_id').value = entity.location_id;
            <?php elseif ($_SESSION['type'] === 'flight'): ?>
                document.getElementById('vendor_id').value = entity.vendor_id;
                document.getElementById('flight_number').value = entity.flight_number;
                document.getElementById('departure_location_id').value = entity.departure_location_id;
                document.getElementById('arrival_location_id').value = entity.arrival_location_id;
                document.getElementById('departure_time').value = entity.departure_time;
                document.getElementById('arrival_time').value = entity.arrival_time;
                document.getElementById('price').value = entity.price;
            <?php elseif ($_SESSION['type'] === 'hotel'): ?>
                document.getElementById('vendor_id').value = entity.vendor_id;
                document.getElementById('name').value = entity.name;
                document.getElementById('location_id').value = entity.location_id;
                document.getElementById('description').value = entity.description;
                document.getElementById('price_per_night').value = entity.price_per_night;
                document.getElementById('capacity').value = entity.capacity;
            <?php endif; ?>
        }

        function resetForm() {
            document.getElementById('form-action').value = 'create';
            document.getElementById('entity_id').value = '';
            document.getElementById('existing_photo').value = '';
            document.querySelector('form').reset();
        }
    </script>
</body>
</html>
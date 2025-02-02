<?php
require "../app/models/FlightModel.php";

$flightModel = new FlightModel();
$flights = $flightModel->getAllFlights();
$message = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
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
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Flights</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <div class="container mx-auto my-5">
        <h1 class="text-2xl font-bold mb-5">Manage Flights</h1>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- Flight Form -->
        <form method="POST" class="mb-5 p-5 border rounded">
            <input type="hidden" id="form-action" name="action" value="create">
            <input type="hidden" id="flight_id" name="flight_id">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="vendor_id" class="block text-gray-700">Vendor ID</label>
                    <input type="text" id="vendor_id" name="vendor_id" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="flight_number" class="block text-gray-700">Flight Number</label>
                    <input type="text" id="flight_number" name="flight_number" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="departure_location_id" class="block text-gray-700">Departure Location ID</label>
                    <input type="text" id="departure_location_id" name="departure_location_id" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="arrival_location_id" class="block text-gray-700">Arrival Location ID</label>
                    <input type="text" id="arrival_location_id" name="arrival_location_id" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="departure_time" class="block text-gray-700">Departure Time</label>
                    <input type="datetime-local" id="departure_time" name="departure_time" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="arrival_time" class="block text-gray-700">Arrival Time</label>
                    <input type="datetime-local" id="arrival_time" name="arrival_time" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label for="price" class="block text-gray-700">Price</label>
                    <input type="number" id="price" name="price" class="w-full px-3 py-2 border rounded" required>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit</button>
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="resetForm()">Reset</button>
            </div>
        </form>

        <!-- Flight List -->
        <table class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">Flight ID</th>
                    <th class="px-4 py-2 border">Vendor ID</th>
                    <th class="px-4 py-2 border">Flight Number</th>
                    <th class="px-4 py-2 border">Departure</th>
                    <th class="px-4 py-2 border">Arrival</th>
                    <th class="px-4 py-2 border">Departure Time</th>
                    <th class="px-4 py-2 border">Arrival Time</th>
                    <th class="px-4 py-2 border">Price</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flights as $flight): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($flight['flight_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($flight['vendor_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($flight['flight_number']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($flight['departure_location_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($flight['arrival_location_id']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($flight['departure_time']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($flight['arrival_time']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($flight['price']) ?></td>
                        <td class="px-4 py-2 border">
                            <button type="button" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600"
                                    onclick="editFlight(<?= htmlspecialchars(json_encode($flight)) ?>)">
                                Edit
                            </button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="flight_id" value="<?= htmlspecialchars($flight['flight_id']) ?>">
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
        function editFlight(flight) {
            document.getElementById('form-action').value = 'update';
            document.getElementById('flight_id').value = flight.flight_id;
            document.getElementById('vendor_id').value = flight.vendor_id;
            document.getElementById('flight_number').value = flight.flight_number;
            document.getElementById('departure_location_id').value = flight.departure_location_id;
            document.getElementById('arrival_location_id').value = flight.arrival_location_id;
            document.getElementById('departure_time').value = flight.departure_time;
            document.getElementById('arrival_time').value = flight.arrival_time;
            document.getElementById('price').value = flight.price;
        }

        function resetForm() {
            document.getElementById('form-action').value = 'create';
            document.getElementById('flight_id').value = '';
            document.getElementById('vendor_id').value = '';
            document.getElementById('flight_number').value = '';
            document.getElementById('departure_location_id').value = '';
            document.getElementById('arrival_location_id').value = '';
            document.getElementById('departure_time').value = '';
            document.getElementById('arrival_time').value = '';
            document.getElementById('price').value = '';
        }
    </script>
</body>
</html>

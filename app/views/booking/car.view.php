<?php

include('../public/inc/header.php');
require "../app/models/BookingCar.php";
require "../app/models/CarModel.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}

$BookingCar = new BookingCar();
$Car = new CarModel();

// Get car details from the ID passed
$car_id = filter_input(INPUT_GET, 'car_id', FILTER_VALIDATE_INT);
$car = $car_id ? $Car->getCarById($car_id) : null;

// Handle booking if the car is found
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_time = filter_input(INPUT_POST, 'start_time', FILTER_SANITIZE_STRING);
    $end_time = filter_input(INPUT_POST, 'end_time', FILTER_SANITIZE_STRING);

    // Validate both start and end times
    if (!$start_time || !$end_time) {
        die("Please select both start and end times.");
    }

    // Check if end time is after start time
    $start_timestamp = strtotime($start_time);
    $end_timestamp = strtotime($end_time);

    if ($end_timestamp <= $start_timestamp) {
        die("End time must be after the start time.");
    }

    // Calculate the total price (Assuming price per hour and time difference)
    $price_per_hour = $car['price_per_hour'];
    $duration = ($end_timestamp - $start_timestamp) / 3600; // Duration in hours
    $total_price = $duration * $price_per_hour;

    // Create booking
    $user_id = $_SESSION['user_id'];
    $status = "Pending"; // Default status until confirmed

    $success = $BookingCar->bookCar($user_id, $car_id, $start_time, $end_time, $total_price);

    if ($success) {

    } else {
        echo "Failed to book the car. Please try again.";
    }
}

// Fetch all bookings
$bookings = $BookingCar->getUserBookings($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Car</title>
</head>
<body>
    <?php if ($car) : ?>
        <section class="text-gray-600 body-font">
            <div class="container px-5 py-12 mx-auto">
                <h2 class="text-2xl font-medium text-gray-900">Book <?php echo htmlspecialchars($car['model']); ?></h2>
                <p class="mt-4 text-gray-600">Car Price per Hour: $<?php echo htmlspecialchars($car['price_per_hour']); ?></p>
                <p class="mt-1 text-gray-600">Seats: <?php echo htmlspecialchars($car['seats']); ?></p>
                <p class="mt-1 text-gray-600">Make Year: <?php echo htmlspecialchars($car['make_year']); ?></p>

                <form action="" method="POST" class="bg-gray-100 p-6 rounded-lg mt-6">
                    <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['car_id']); ?>">

                    <!-- Start Time -->
                    <div class="mb-4">
                        <label for="start_time" class="block text-gray-700">Start Time</label>
                        <input type="datetime-local" id="start_time" name="start_time" class="p-2 border rounded-lg w-full" required>
                    </div>

                    <!-- End Time -->
                    <div class="mb-4">
                        <label for="end_time" class="block text-gray-700">End Time</label>
                        <input type="datetime-local" id="end_time" name="end_time" class="p-2 border rounded-lg w-full" required>
                    </div>

                    <!-- Submit Booking -->
                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg">Book Now</button>
                </form>
            </div>
        </section>
    <?php endif; ?>

    <!-- Display All Bookings -->
    <section class="text-gray-600 body-font">
        <div class="container px-5 py-12 mx-auto">
            <h2 class="text-2xl font-medium text-gray-900">All Bookings</h2>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">Booking ID</th>
                            <th class="px-4 py-2 border">Car Model</th>
                            <th class="px-4 py-2 border">Start Time</th>
                            <th class="px-4 py-2 border">End Time</th>
                            <th class="px-4 py-2 border">Total Price</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking) : ?>
                            <tr>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($booking['car_model']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($booking['start_time']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($booking['end_time']); ?></td>
                                <td class="px-4 py-2 border">$<?php echo htmlspecialchars($booking['total_price']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($booking['status']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($booking['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <?php include('../public/inc/footer.php'); ?>
</body>
</html>
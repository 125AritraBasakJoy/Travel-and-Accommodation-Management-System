<?php
ob_start(); // Start output buffering
include('../public/inc/header.php'); 

require "../app/models/BookingFlight.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit();
}

$BookingFlight = new BookingFlight();
$user_id = $_SESSION['user_id']; // Get user ID from session

// Handle Flight Booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flight_id = $_POST['flight_id'] ?? null;
    $price = $_POST['price'] ?? null;

    if (!$flight_id || !$price) {
        die("Invalid booking request.");
    }

    // Use bookFlight instead of createBooking
    $success = $BookingFlight->bookFlight($user_id, $flight_id, "Auto-Assigned", $price);

    if ($success) {
        header("Location: ?success=1");
        exit();
    } else {
        echo "Failed to book flight. Try again.";
    }
}

// Fetch User's Bookings
$bookings = $BookingFlight->getUserBookings($user_id);
?>


<body class="bg-gray-100">

    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">My Flight Bookings</h2>

        <table class="w-full border-collapse border border-gray-300 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 border border-gray-300">Flight ID</th>
                    <th class="p-3 border border-gray-300">Seat Number</th>
                    <th class="p-3 border border-gray-300">Total Price</th>
                    <th class="p-3 border border-gray-300">Status</th>
                    <th class="p-3 border border-gray-300">Created At</th>
                    <th class="p-3 border border-gray-300">Cancel</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking) { ?>
                    <tr class="bg-white border-b border-gray-300 hover:bg-gray-100 transition">
                        <td class="p-3 text-center"><?php echo htmlspecialchars($booking['flight_id']); ?></td>
                        <td class="p-3 text-center"><?php echo htmlspecialchars($booking['seat_number']); ?></td>
                        <td class="p-3 text-center font-semibold text-blue-600">৳<?php echo htmlspecialchars($booking['total_price']); ?></td>
                        <td class="p-3 text-center">
                            <span class="px-2 py-1 rounded-md text-white
                                <?php echo ($booking['status'] === 'Pending') ? 'bg-yellow-500' : ($booking['status'] === 'Confirmed' ? 'bg-green-500' : 'bg-red-500'); ?>">
                                <?php echo htmlspecialchars($booking['status']); ?>
                            </span>
                        </td>
                        <td class="p-3 text-center"><?php echo htmlspecialchars($booking['created_at']); ?></td>
                        <td class="p-3 text-center">
                            <?php if ($booking['status'] == 'Pending') { ?>
                                <form action="cancel_booking.php" method="POST">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                                        Cancel
                                    </button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

<?php include('../public/inc/footer.php'); ?>
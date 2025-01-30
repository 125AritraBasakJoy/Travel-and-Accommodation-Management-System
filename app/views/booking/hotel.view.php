<?php
include('../public/inc/header.php'); 
require "../app/models/BookingHotel.php";
require "../app/models/HotelModel.php";

// ✅ Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please login to view bookings");
    exit();
}

$user_id = $_SESSION['user_id'];

// 📌 Fetch user bookings
$bookingHotel = new BookingHotel();
$bookings = $bookingHotel->getUserBookings($user_id);

// 💾 Handle cancellation request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    $cancel_booking_id = $_POST['cancel_booking_id'];
    $cancelSuccess = $bookingHotel->cancelBooking($cancel_booking_id, $user_id);

    if ($cancelSuccess) {
        $success = "Booking canceled successfully.";
        $bookings = $bookingHotel->getUserBookings($user_id); // Refresh bookings
    } else {
        $error = "Failed to cancel booking. Please try again.";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<?php ?>

<div class="container mx-auto py-10">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">My Bookings</h2>

    <!-- 🚨 Show error or success messages -->
    <?php if (!empty($error)): ?>
        <p class="text-red-500 text-center mb-4"><?= $error ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p class="text-green-500 text-center mb-4"><?= $success ?></p>
    <?php endif; ?>

    <div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
        <?php if (!empty($bookings)): ?>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 text-left">Hotel</th>
                        <th class="p-3 text-left">Check-in</th>
                        <th class="p-3 text-left">Check-out</th>
                        <th class="p-3 text-left">Total Price</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr class="border-t">
                            <td class="p-3"><?= htmlspecialchars($booking['hotel_name']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($booking['check_in']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($booking['check_out']) ?></td>
                            <td class="p-3">$<?= number_format($booking['total_price'], 2) ?></td>
                            <td class="p-3"><?= htmlspecialchars($booking['status']) ?></td>
                            <td class="p-3 text-center">
                                <?php if ($booking['status'] === 'Pending'): ?>
                                    <form method="POST">
                                        <input type="hidden" name="cancel_booking_id" value="<?= $booking['booking_id'] ?>">
                                        <button type="submit" class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-500">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500 text-center">You have no bookings yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('../public/inc/footer.php'); ?>

</body>
</html>

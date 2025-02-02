<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['vendor_id'])) {
    header("Location: login");
    exit();
}

$vendor_id = $_SESSION['vendor_id'];
$user_type = $_SESSION['type']; // 'hotel', 'flight', or 'car'

require "../app/models/BookingModel.php";
require "../app/models/Location.php";

$bookingModel = new BookingModel();
$location = new Location();

$message = "";

// Fetch bookings based on vendor type
switch ($user_type) {
    case 'hotel':
        $bookings = $bookingModel->getHotelBookingsByVendorId($vendor_id);
        break;
    case 'flight':
        $bookings = $bookingModel->getFlightBookingsByVendorId($vendor_id);
        break;
    case 'car':
        $bookings = $bookingModel->getCarBookingsByVendorId($vendor_id);
        break;
    default:
        $bookings = [];
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['status'];

    // Update booking status based on vendor type
    switch ($user_type) {
        case 'hotel':
            $bookingModel->updateHotelBookingStatus($booking_id, $new_status);
            break;
        case 'flight':
            $bookingModel->updateFlightBookingStatus($booking_id, $new_status);
            break;
        case 'car':
            $bookingModel->updateCarBookingStatus($booking_id, $new_status);
            break;
    }

    $_SESSION['message'] = "Status updated successfully!";
    header("Location: approver");
    exit();
}

// Display success message after redirect
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-4">Manage Bookings</h1>

        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md overflow-hidden">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="px-4 py-2">Booking ID</th>
                    <th class="px-4 py-2">Destination</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($bookings) && count($bookings) > 0): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($location->getLocationById($booking['location_id'])['city']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($booking['time']); ?></td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-sm font-semibold 
                                    <?php echo $booking['status'] === 'confirmed' ? 'bg-green-200 text-green-800' : 
                                                ($booking['status'] === 'Pending' ? 'bg-yellow-200 text-yellow-800' : 
                                                'bg-red-200 text-red-800'); ?>">
                                    <?php echo htmlspecialchars($booking['status']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600" onclick="return confirm('Are you sure you want to approve this booking?')">Approve</button>
                                </form>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to reject this booking?')">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

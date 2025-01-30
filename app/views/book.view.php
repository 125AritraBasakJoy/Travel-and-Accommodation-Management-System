<?php
include('../public/inc/header.php'); 
require "../app/models/HotelModel.php";
require "../app/models/BookingHotel.php";

// ✅ Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please login to book a hotel");
    exit();
}

// 📌 Get user ID from session
$user_id = $_SESSION['user_id'];

// ✅ Check if `hotel_id` is provided
if (!isset($_GET['hotel_id']) || empty($_GET['hotel_id'])) {
    header("Location: hotels.php?error=Invalid hotel selection");
    exit();
}

$hotel_id = $_GET['hotel_id'];

// 🏨 Fetch hotel details
$hotelModel = new HotelModel();
$hotel = $hotelModel->getHotelById($hotel_id);

if (!$hotel) {
    header("Location: hotels.php?error=Hotel not found");
    exit();
}

// 💾 Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Validate input dates
    if (empty($check_in) || empty($check_out) || strtotime($check_in) >= strtotime($check_out)) {
        $error = "Invalid check-in or check-out date.";
    } else {
        // Calculate total price
        $days = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
        $total_price = $days * $hotel['price_per_night'];

        // Save booking in database
        $bookingHotel = new BookingHotel();
        $bookingSuccess = $bookingHotel->createBooking($user_id, $hotel_id, $check_in, $check_out, $total_price);

        if ($bookingSuccess) {
            $success = "Booking successful! Your total price is $" . number_format($total_price, 2);
        } else {
            $error = "Booking failed. Please try again.";
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">


<div class="container mx-auto py-10">
    <div class="max-w-2xl mx-auto bg-white p-8 shadow-lg rounded-lg">

        <!-- Hotel Details -->
        <h2 class="text-2xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($hotel['name']) ?></h2>
        <img src="uploads/<?= htmlspecialchars($hotel['hotelPhoto']) ?>" alt="Hotel Image" class="w-full h-60 object-cover rounded-lg mb-4">
        <p class="text-gray-700 mb-2">Price per night: <strong>$<?= number_format($hotel['price_per_night'], 2) ?></strong></p>
        <p class="text-gray-700 mb-4"><?= htmlspecialchars($hotel['description']) ?></p>

        <!-- 🚨 Show error or success messages -->
        <?php if (!empty($error)): ?>
            <p class="text-red-500 mb-4"><?= $error ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p class="text-green-500 mb-4"><?= $success ?></p>
        <?php endif; ?>

        <!-- Booking Form -->
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Check-in Date</label>
                <input type="date" name="check_in" class="w-full mt-1 p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Check-out Date</label>
                <input type="date" name="check_out" class="w-full mt-1 p-2 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">Confirm Booking</button>
        </form>

    </div>
</div>

<?php include('../public/inc/footer.php'); ?>

</body>
</html>

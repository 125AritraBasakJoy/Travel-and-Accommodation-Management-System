<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php include('../public/inc/header.php'); ?>
<?php
require "../app/models/Location.php";
require "../app/models/HotelModel.php";
// Instantiate the Location model
$locationModel = new Location();
$locations = $locationModel->getAllLocations();
$isLoggedIn = isset($_SESSION['user_id']);

$hotelModel = new HotelModel();
$hotels = $hotelModel->getAllHotels(); // Fetch data from the database
$hotels = [];

// If a search is submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = $_GET['search'] ?? '';
    $minPrice = $_GET['min_price'] ?? 0;
    $maxPrice = $_GET['max_price'] ?? PHP_INT_MAX;
    $locationId = $_GET['location_id'] ?? null;

    // Fetch hotels based on filters
    $query = "SELECT * FROM `hotel` WHERE `name` LIKE ? AND `price_per_night` BETWEEN ? AND ?";
    $params = ["%$search%", $minPrice, $maxPrice];

    if (!empty($locationId)) {
        $query .= " AND `location_id` = ?";
        $params[] = $locationId;
    }

    $stmt = $hotelModel->getDb()->prepare($query);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $hotels = $result->fetch_all(MYSQLI_ASSOC);
}


?>

<section class="text-gray-600 body-font">

<div class="container mx-auto py-8">
        <!-- Search Form -->
        <form method="GET" class="bg-white p-6 shadow-lg rounded-lg mb-8">
            <div class="flex flex-wrap gap-4">
                <!-- Search Bar -->
                <div class="flex-grow">
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" id="search" name="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Search by hotel name">
                </div>

                <!-- Price Range -->
                <div class="w-1/4">
                    <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price</label>
                    <input type="number" id="min_price" name="min_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-1/4">
                    <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price</label>
                    <input type="number" id="max_price" name="max_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Location Filter -->
                <div class="w-1/4">
                    <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
                    <select id="location_id" name="location_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    <option value="">All Locations</option>
    <?php foreach ($locations as $location): ?>
        <option value="<?= htmlspecialchars($location['location_id']) ?>">
            <?= htmlspecialchars($location['city'] . ', ' . $location['state'] . ', ' . $location['country']) ?>
        </option>
    <?php endforeach; ?>
</select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700">Search</button>
                </div>
            </div>
        </form>

</div>        

        <!-- Results -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($hotels as $hotel): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="../uploads/<?= htmlspecialchars($hotel['hotelPhoto']) ?>" alt="Hotel Photo" class="h-48 w-full object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($hotel['name']) ?></h2>
                        <p class="text-gray-600"><?= htmlspecialchars($hotel['description']) ?></p>
                        <p class="mt-2 text-indigo-600 font-bold">৳<?= number_format($hotel['price_per_night'], 2) ?> / night</p>
                    </div>
                    <?php
                              // If user is logged in, show "Book Now" button
              if ($isLoggedIn) {
                echo '<a href="'.BASE_URL.'/booking?hotel_id=' . $hotel['hotel_id'] . '" class="mt-4 block text-center px-6 py-2 text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">Book Now</a>';
            } else {
                echo '<p class="mt-4 text-sm text-red-500">Login to book this hotel.</p>';
            }
                ?>
                </div>

            <?php endforeach; ?>
        </div>

        <!-- No Results Message -->
        <?php if (empty($hotels)): ?>
            <p class="text-center text-gray-600">No hotels found. Try adjusting your search criteria.</p>
        <?php endif; ?>

</section>

<?php include('../public/inc/footer.php'); ?>
</body>
</html>

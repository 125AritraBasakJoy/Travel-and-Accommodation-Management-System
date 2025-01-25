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

$hotel = new HotelModel();
$hotels = $hotel->getAllHotels(); // Fetch data from the database
?>

<section class="text-gray-600 body-font">

<div class="container mx-auto py-8">
        <!-- Search Form -->
        <form method="GET" action="search/hotel" class="bg-white p-6 shadow-lg rounded-lg mb-8">
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



  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-wrap -m-4">

    <?php 
    if (!empty($hotels)) {
        foreach ($hotels as $data) {
            // Building the display name for the hotel (vendor, location)
            $hotelName = htmlspecialchars($data["name"]);
            $pricePerNight = '$' . number_format($data["price_per_night"], 2);
            $description = htmlspecialchars($data["description"]);
            $capacity = htmlspecialchars($data["capacity"]);

            echo '<div class="lg:w-1/4 md:w-1/2 p-4 w-full">
                    <a class="block relative h-48 rounded overflow-hidden">
                      <img alt="hotel" class="object-cover object-center w-full h-full block" src="uploads/' . htmlspecialchars($data["hotelPhoto"]) . '" onerror="this.onerror=null;this.src=\'https://dummyimage.com/420x260\';">
                    </a>
                    <div class="mt-4">
                      <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">HOTEL</h3>
                      <h2 class="text-gray-900 title-font text-lg font-medium">' . $hotelName . '</h2>
                      <p class="mt-1">Price per Night: ' . $pricePerNight . '</p>
                      <p class="mt-1">Capacity: ' . $capacity . ' people</p>
                      <p class="mt-1">' . $description . '</p>
                    </div>
                  </div>';
        }
    } else {
        echo '<p class="text-center w-full text-gray-500">No hotels available.</p>';
    }
    ?>


    </div>
  </div>
</section>

<?php include('../public/inc/footer.php'); ?>
</body>
</html>

<?php 
require "../app/models/TripPlannerModel.php";
require "../app/models/Location.php";

$tripPlanner = new TripPlannerModel();
$location_id = $_GET["location"] ?? 1; // Default to 1 if not set
$hotel_nights = $_POST['hotel_nights'] ?? 3;
$car_hours = $_POST['car_hours'] ?? 5;
$location = new Location();
$location = $location->getLocationById($location_id);
$budgetTrip = $tripPlanner->findBudgetTrip($location_id);

// Recalculate total cost based on user input
$totalCost = $tripPlanner->calculateTotalCost(
    $budgetTrip['hotel'], 
    $budgetTrip['flight'], 
    $budgetTrip['car'], 
    $hotel_nights, 
    $car_hours
);
?>

<?php include('../public/inc/header.php'); $isLoggedIn = isset($_SESSION['user_id']);?>

<!-- Banner Section -->
<section class="relative">
    <img src="<?php echo htmlspecialchars('uploads/' . $location['locationPhoto']); ?>" alt="<?php echo htmlspecialchars($location['city']); ?>" class="w-full h-64 object-cover">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <h1 class="text-4xl text-white font-bold">Explore <?php echo htmlspecialchars($location['city']); ?></h1>
    </div>
</section>
<!-- Explore Section -->
<section class="py-8 bg-gray-100">
    <div class="container mx-auto text-center">
        <h2 class="text-2xl font-medium text-gray-900 mb-6">Explore More</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Explore Flight Button -->
            <a href="search/flight?arrival=<?php echo $location_id?>" class="bg-blue-600 text-white text-lg px-8 py-3 rounded-lg shadow-lg hover:bg-blue-700 transition duration-300">
                Explore Flights
            </a>
            <!-- Explore Hotel Button -->
            <a href="search/hotel?location_id=<?php echo $location_id?>" class="bg-green-600 text-white text-lg px-8 py-3 rounded-lg shadow-lg hover:bg-green-700 transition duration-300">
                Explore Hotels
            </a>
            <!-- Explore Car Button -->
            <a href="search/car?location_id=<?php echo $location_id?>" class="bg-red-600 text-white text-lg px-8 py-3 rounded-lg shadow-lg hover:bg-red-700 transition duration-300">
                Explore Cars
            </a>
        </div>
    </div>
</section>

<section class="text-gray-600 body-font">
    <div class="container px-5 py-12 mx-auto">
        <h2 class="text-2xl font-medium text-gray-900 mb-8">Budget Trip for <?php echo htmlspecialchars($location["city"]); ?></h2>

        <?php if ($budgetTrip) { ?>
            <form method="POST" class="bg-gray-100 p-6 rounded-lg mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Customize Your Trip</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700">Hotel Nights</label>
                        <input type="number" name="hotel_nights" value="<?php echo htmlspecialchars($hotel_nights); ?>" min="1" class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block text-gray-700">Car Rental Hours</label>
                        <input type="number" name="car_hours" value="<?php echo htmlspecialchars($car_hours); ?>" min="0" class="w-full p-2 border rounded" required>
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Update Trip Cost
                </button>
            </form>

            <div class="bg-gray-100 p-6 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Total Trip Cost</h3>
                <p class="text-gray-800 text-lg">Total Cost: ৳<?php echo number_format($totalCost, 2); ?></p>
            </div>
        <?php } else { ?>
            <p class="text-center text-gray-500">No budget trip found for the given location.</p>
        <?php } ?>
    </div>
</section>

<section class="text-gray-600 body-font">
    <div class="container px-5 py-12 mx-auto">
        <?php if ($budgetTrip) { ?>
            <!-- Hotel Details -->
            <div class="bg-gray-100 p-6 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Hotel Details</h3>
                <?php if ($budgetTrip['hotel']) { ?>
                    <div class="flex items-center space-x-6">
                        <img src="<?php echo htmlspecialchars('uploads/' . $budgetTrip['hotel']['hotelPhoto']); ?>" alt="Hotel Image" class="w-32 h-32 object-cover rounded-lg">
                        <div>
                            <h4 class="text-lg font-medium"><?php echo htmlspecialchars($budgetTrip['hotel']['name']); ?></h4>
                            <p class="text-gray-600"><?php echo htmlspecialchars($budgetTrip['hotel']['description']); ?></p>
                            <p class="mt-2 text-gray-800">Price per Night: ৳<?php echo number_format($budgetTrip['hotel']['price_per_night'], 2); ?></p>
                        </div>
                    </div>
                    
                    <!-- If user is logged in, show "Book Now" button -->
                    <?php if ($isLoggedIn) { ?>
                        <a href="booking?hotel_id=<?php echo $budgetTrip['hotel']['hotel_id']; ?>" class="mt-4 block text-center px-6 py-3 w-full text-white bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">
                            Book Now
                        </a>
                    <?php } else { ?>
                        <p class="mt-4 text-sm text-red-500"><a href="login" class="underline">Log in</a> to book this hotel.</p>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-gray-500">Hotel information is not available.</p>
                <?php } ?>
            </div>

            <!-- Flight Details -->
            <div class="bg-gray-100 p-6 rounded-lg mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Flight Details</h3>
                <?php if ($budgetTrip['flight']) { ?>
                    <div>
                        <p class="text-gray-800">Flight Number: <?php echo htmlspecialchars($budgetTrip['flight']['flight_number']); ?></p>
                        <p class="text-gray-600">Departure Location ID: <?php echo htmlspecialchars($budgetTrip['flight']['departure_location_id']); ?></p>
                        <p class="text-gray-600">Arrival Location ID: <?php echo htmlspecialchars($budgetTrip['flight']['arrival_location_id']); ?></p>
                        <p class="mt-2 text-gray-800">Price: ৳<?php echo number_format($budgetTrip['flight']['price'], 2); ?></p>
                    </div>
                    
                    <!-- Booking Button -->
                    <?php if ($isLoggedIn) { ?>
                        <form action="booking/flight" method="POST">
                            <input type="hidden" name="flight_id" value="<?php echo $budgetTrip['flight']['flight_id']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <input type="hidden" name="price" value="<?php echo $budgetTrip['flight']['price']; ?>">
                            <button type="submit" class="mt-4 w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Book Now
                            </button>
                        </form>
                    <?php } else { ?>
                        <p class="mt-4 text-red-500">
                            <a href="login" class="underline">Log in</a> to book this flight.
                        </p>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-gray-500">Flight information is not available.</p>
                <?php } ?>
            </div>

            <!-- Car Rental Details -->
            <div class="bg-gray-100 p-6 rounded-lg mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Car Rental Details</h3>
                <?php if ($budgetTrip['car']) { ?>
                    <div class="flex items-center space-x-6">
                        <img src="<?php echo htmlspecialchars('uploads/car/' . $budgetTrip['car']['carPhoto']); ?>" alt="Car Image" class="w-32 h-32 object-cover rounded-lg">
                        <div>
                            <h4 class="text-lg font-medium"><?php echo htmlspecialchars($budgetTrip['car']['model']); ?></h4>
                            <p class="text-gray-600">Seats: <?php echo htmlspecialchars($budgetTrip['car']['seats']); ?></p>
                            <p class="mt-2 text-gray-800">Price per Hour: ৳<?php echo number_format($budgetTrip['car']['price_per_hour'], 2); ?></p>
                        </div>
                    </div>
                    
                    <!-- Booking Button for Logged-In Users -->
                    <?php if ($isLoggedIn) { ?>
                        <form action="booking/car" method="GET" class="mt-4">
                            <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($budgetTrip['car']['car_id']); ?>">
                            <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                                Book Now
                            </button>
                        </form>
                    <?php } else { ?>
                        <p class="mt-4 text-red-500"><a href="login" class="underline">Log in</a> in to book this car.</p>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-gray-500">Car rental information is not available.</p>
                <?php } ?>
            </div>

            <!-- Final Trip Cost -->
            <div class="bg-gray-100 p-6 rounded-lg mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Final Trip Cost</h3>
                <p class="text-gray-800 text-lg">Total Cost: ৳<?php echo number_format($totalCost, 2); ?></p>
            </div>
        <?php } else { ?>
            <p class="text-center text-gray-500">No budget trip found for the given location.</p>
        <?php } ?>
    </div>
</section>

<?php include('../public/inc/footer.php'); ?>
</body>
</html>

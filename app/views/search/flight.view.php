<?php
require "../app/models/FlightModel.php";
require "../app/models/Location.php";

$Flight = new FlightModel();
$Location = new Location();

// Get search parameters
$departure = $_GET['departure'] ?? '';
$arrival = $_GET['arrival'] ?? '';
$budget = $_GET['budget'] ?? '';
$locations = $Location->getAllLocations();
// Fetch matching flights
$results = $Flight->searchFlightsWithBudget($departure, $arrival, $budget);
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<?php include('../public/inc/header.php'); ?>
<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <!-- Search Form -->
    <form action="" method="GET" class="mb-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Departure Location Dropdown -->
        <div>
          <label for="departure" class="block text-sm font-medium text-gray-700 mb-2">Departure Location</label>
          <select 
            id="departure" 
            name="departure" 
            class="w-full p-2 border border-gray-300 rounded"
            required
          >
            <option value="">Select Departure</option>
            <?php foreach ($locations as $location) { ?>
              <option value="<?php echo htmlspecialchars($location['location_id']); ?>">
                <?php echo htmlspecialchars($location['city']) . ', ' . htmlspecialchars($location['country']); ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <!-- Arrival Location Dropdown -->
        <div>
          <label for="arrival" class="block text-sm font-medium text-gray-700 mb-2">Arrival Location</label>
          <select 
            id="arrival" 
            name="arrival" 
            class="w-full p-2 border border-gray-300 rounded"
            required
          >
            <option value="">Select Arrival</option>
            <?php foreach ($locations as $location) { ?>
              <option value="<?php echo htmlspecialchars($location['location_id']); ?>">
                <?php echo htmlspecialchars($location['city']) . ', ' . htmlspecialchars($location['country']); ?>
              </option>
            <?php } ?>
          </select>
        </div>

        <!-- Departure Date -->
        <div>
          <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-2">Departure Date</label>
          <input 
            type="date" 
            id="departure_date" 
            name="departure_date" 
            class="w-full p-2 border border-gray-300 rounded"
            required
          >
        </div>

        <!-- Arrival Date -->
        <div>
          <label for="arrival_date" class="block text-sm font-medium text-gray-700 mb-2">Arrival Date</label>
          <input 
            type="date" 
            id="arrival_date" 
            name="arrival_date" 
            class="w-full p-2 border border-gray-300 rounded"
          >
        </div>

        <!-- Budget Filter -->
        <div>
          <label for="budget" class="block text-sm font-medium text-gray-700 mb-2">Budget ($)</label>
          <input 
            type="number" 
            id="budget" 
            name="budget" 
            placeholder="Enter max budget"
            class="w-full p-2 border border-gray-300 rounded"
          >
        </div>
      </div>

      <!-- Submit Button -->
      <div class="mt-4">
        <button 
          type="submit" 
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
          Search
        </button>
      </div>
    </form>
  </div>
</section>
<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <h1 class="text-2xl font-medium text-gray-900 mb-4">Search Results</h1>

    <div class="-my-8">
      <?php if (!empty($results)) { ?>
        <?php foreach ($results as $flight) { ?>
          <div class="py-8 flex items-center border-b border-gray-200">
            <div class="w-1/4">
              <a class="block relative h-48 rounded overflow-hidden">
                <img 
                  alt="Flight Image"
                  class="object-cover object-center w-full h-full block"
                  src="https://dummyimage.com/420x260/000/fff&text=Flight+Image"
                  onerror="this.onerror=null;this.src='https://dummyimage.com/420x260/000/fff&text=No+Image';"
                >
              </a>
            </div>
            <div class="w-3/4 pl-4">
              <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">FLIGHT NUMBER</h3>
              <h2 class="text-gray-900 title-font text-lg font-medium">
                <?php echo htmlspecialchars($flight['flight_number']); ?>
              </h2>
              <p class="mt-1">Departure: <?php echo htmlspecialchars($Location->getLocationById($flight['departure_location_id'])['city']); ?></p>
              <p class="mt-1">Arrival: <?php echo htmlspecialchars($Location->getLocationById($flight['arrival_location_id'])['city']); ?></p>
              <p class="mt-1">Departure Time: <?php echo htmlspecialchars($flight['departure_time']); ?></p>
              <p class="mt-1">Arrival Time: <?php echo htmlspecialchars($flight['arrival_time']); ?></p>
              <p class="mt-1">Price: ৳<?php echo htmlspecialchars($flight['price']); ?></p>
                          <!-- Booking Button -->
                          <?php if ((isset($_SESSION['user_id']))) { ?>
                <form action="<?php echo BASE_URL?>booking/flight" method="POST">
                  <input type="hidden" name="flight_id" value="<?php echo $flight['flight_id']; ?>">
                  <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                  <input type="hidden" name="price" value="<?php echo $flight['price']; ?>">
                  <button type="submit"
                          class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Book Now
                  </button>
                </form>
              <?php } else { ?>
                <p class="mt-4 text-red-500">
                  <a href="login" class="underline">Log in</a> to book this flight.
                </p>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <p class="text-center text-gray-500">No flights match your search criteria.</p>
      <?php } ?>
    </div>
  </div>
</section>

<?php include('../public/inc/footer.php'); ?>
</body>
</html>

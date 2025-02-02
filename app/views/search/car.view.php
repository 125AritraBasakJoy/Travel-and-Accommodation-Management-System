<?php include('../public/inc/header.php'); ?>
<?php
require "../app/models/CarModel.php";

$Car = new CarModel();
$filters = [
    'seats' => $_GET['seats'] ?? null,
    'min_price' => $_GET['min_price'] ?? null,
    'max_price' => $_GET['max_price'] ?? null,
    'location_id' => $_GET['location_id'] ?? null,
];

$Cars = $Car->searchCars($filters);
?>

<section class="text-gray-600 body-font">
  <div class="container px-5 py-12 mx-auto">
    <!-- Search Form -->
    <form action="search/car" method="GET" class="mb-8 bg-gray-100 p-6 rounded-lg">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Seats -->
        <input type="number" name="seats" placeholder="Seats" class="p-2 border rounded-lg" />
        <!-- Min Price -->
        <input type="number" name="min_price" placeholder="Min Price" class="p-2 border rounded-lg" />
        <!-- Max Price -->
        <input type="number" name="max_price" placeholder="Max Price" class="p-2 border rounded-lg" />
        <!-- Location -->
        <select name="location_id" class="p-2 border rounded-lg">
          <option value="">Select Location</option>
          <?php
          foreach ($locations as $location) {
              echo "<option value='" . htmlspecialchars($location['location_id']) . "'>" . htmlspecialchars($location['city']) . "</option>";
          }
          ?>
        </select>
      </div>
      <button type="submit" class="mt-4 w-full bg-blue-500 text-white p-2 rounded-lg">Search</button>
    </form>
  </div>
</section>
<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="-my-8">
      <?php if (!empty($Cars)) { ?>
        <?php foreach ($Cars as $car) { ?>
          <div class="py-8 flex items-center border-b border-gray-200">
            <div class="w-1/4">
              <a class="block relative h-48 rounded overflow-hidden">
                <img 
                  alt="<?php echo htmlspecialchars($car['model']); ?>" 
                  class="object-cover object-center w-full h-full block" 
                  src="<?php echo htmlspecialchars(BASE_URL."uploads/car/".$car['carPhoto']); ?>" 
                  onerror="this.onerror=null;this.src='https://dummyimage.com/420x260/000/fff&text=No+Image';"
                >
              </a>
            </div>
            <div class="w-3/4 pl-4">
              <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">CAR MODEL</h3>
              <h2 class="text-gray-900 title-font text-lg font-medium">
                <?php echo htmlspecialchars($car['model']); ?>
              </h2>
              <p class="mt-1">Seats: <?php echo htmlspecialchars($car['seats']); ?></p>
              <p class="mt-1">Price per Hour: ৳<?php echo htmlspecialchars($car['price_per_hour']); ?></p>
              <p class="mt-1">Make Year: <?php echo htmlspecialchars($car['make_year']); ?></p>
                          <!-- Booking Button for Logged-In Users -->
                          <?php if (isset($_SESSION['user_id'])) { ?>
                <form action="<?php echo BASE_URL?>booking/car" method="GET" class="mt-4">
                  <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['car_id']); ?>">
                  <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg">Book Now</button>
                </form>
              <?php } else { ?>
                <p class="mt-4 text-red-500">Please log in to book this car.</p>
              <?php } ?>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <p class="text-center text-gray-500">No cars match your search criteria.</p>
      <?php } ?>
    </div>
  </div>
</section>

<?php include('../public/inc/footer.php'); ?>
</body>
</html>

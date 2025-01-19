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
require "../app/models/FlightModel.php"; // Assuming you have a FlightModel for fetching flight data
$Flight = new FlightModel();
$Flights = $Flight->getAllFlights(); // Fetch data from the database
?>


<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="-my-8">
      <?php if (!empty($Flights)) { ?>
        <?php foreach ($Flights as $flight) { ?>
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
              <p class="mt-1">Departure: <?php echo htmlspecialchars($flight['departure_location_id']); ?></p>
              <p class="mt-1">Arrival: <?php echo htmlspecialchars($flight['arrival_location_id']); ?></p>
              <p class="mt-1">Departure Time: <?php echo htmlspecialchars($flight['departure_time']); ?></p>
              <p class="mt-1">Arrival Time: <?php echo htmlspecialchars($flight['arrival_time']); ?></p>
              <p class="mt-1">Price: $<?php echo htmlspecialchars($flight['price']); ?></p>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <p class="text-center text-gray-500">No flights available at the moment.</p>
      <?php } ?>
    </div>
  </div>
</section>

<?php include('../public/inc/footer.php'); ?>
</body>
</html>

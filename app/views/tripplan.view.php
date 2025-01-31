<?php 
require "../app/models/TripPlannerModel.php";

$tripPlanner = new TripPlannerModel();
$location_id = $_GET["location"]; // Example location ID
$budgetTrip = $tripPlanner->findBudgetTrip($location_id);
?>

<?php include('../public/inc/header.php'); ?>

<section class="text-gray-600 body-font">
    <div class="container px-5 py-12 mx-auto">
        <h2 class="text-2xl font-medium text-gray-900 mb-8">Budget Trip for Location ID: <?php echo htmlspecialchars($location_id); ?></h2>

        <?php if ($budgetTrip) { ?>
            <div class="bg-gray-100 p-6 rounded-lg">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Hotel Details</h3>
                <div class="flex items-center space-x-6">
                    <img src="<?php echo htmlspecialchars('uploads/' . $budgetTrip['hotel']['hotelPhoto']); ?>" alt="Hotel Image" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h4 class="text-lg font-medium"><?php echo htmlspecialchars($budgetTrip['hotel']['name']); ?></h4>
                        <p class="text-gray-600"><?php echo htmlspecialchars($budgetTrip['hotel']['description']); ?></p>
                        <p class="mt-2 text-gray-800">Price per Night: $<?php echo number_format($budgetTrip['hotel']['price_per_night'], 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-100 p-6 rounded-lg mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Flight Details</h3>
                <div class="flex items-center space-x-6">
                    <div>
                        <p class="text-gray-800">Flight Number: <?php echo htmlspecialchars($budgetTrip['flight']['flight_number']); ?></p>
                        <p class="text-gray-600">Departure Location ID: <?php echo htmlspecialchars($budgetTrip['flight']['departure_location_id']); ?></p>
                        <p class="text-gray-600">Arrival Location ID: <?php echo htmlspecialchars($budgetTrip['flight']['arrival_location_id']); ?></p>
                        <p class="mt-2 text-gray-800">Price: $<?php echo number_format($budgetTrip['flight']['price'], 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-100 p-6 rounded-lg mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Car Rental Details</h3>
                <div class="flex items-center space-x-6">
                    <img src="<?php echo htmlspecialchars('uploads/car/' . $budgetTrip['car']['carPhoto']); ?>" alt="Car Image" class="w-32 h-32 object-cover rounded-lg">
                    <div>
                        <h4 class="text-lg font-medium"><?php echo htmlspecialchars($budgetTrip['car']['model']); ?></h4>
                        <p class="text-gray-600">Seats: <?php echo htmlspecialchars($budgetTrip['car']['seats']); ?></p>
                        <p class="mt-2 text-gray-800">Price per Hour: $<?php echo number_format($budgetTrip['car']['price_per_hour'], 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-100 p-6 rounded-lg mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Total Trip Cost</h3>
                <p class="text-gray-800 text-lg">Total Cost: $<?php echo number_format($budgetTrip['total_cost'], 2); ?></p>
            </div>

        <?php } else { ?>
            <p class="text-center text-gray-500">No budget trip found for the given location.</p>
        <?php } ?>
    </div>
</section>

<?php include('../public/inc/footer.php'); ?>

</body>
</html>

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
$location = new Location();
$locations = $location->getAllLocations(); // Fetch data from the database
?>

<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto">
    <div class="flex flex-wrap -m-4">

    <?php 
if (!empty($locations)) {
    foreach ($locations as $data) {
        // Check if "state" exists and is not empty
        $locationName = !empty($data["state"]) 
            ? htmlspecialchars($data["state"]) . ' • ' . htmlspecialchars($data["country"]) 
            : htmlspecialchars($data["country"]);

        echo '<div class="lg:w-1/4 md:w-1/2 p-4 w-full">
                <a class="block relative h-48 rounded overflow-hidden">
                  <img alt="location" class="object-cover object-center w-full h-full block" src="' . htmlspecialchars($data["locationPhoto"]) . '" onerror="this.onerror=null;this.src=\'https://dummyimage.com/420x260\';">
                </a>
                <div class="mt-4">
                  <h3 class="text-gray-500 text-xs tracking-widest title-font mb-1">CATEGORY</h3>
                  <h2 class="text-gray-900 title-font text-lg font-medium">' . htmlspecialchars($data["city"]) . '</h2>
                  <p class="mt-1">' . $locationName . '</p>
                </div>
              </div>';
    }
} else {
    echo '<p class="text-center w-full text-gray-500">No locations available.</p>';
}
?>


    </div>
  </div>
</section>
<?php include('../public/inc/footer.php'); ?>
</body>
</html>

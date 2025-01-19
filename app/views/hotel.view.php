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
require "../app/models/HotelModel.php";
$hotel = new HotelModel();
$hotels = $hotel->getAllHotels(); // Fetch data from the database
?>

<section class="text-gray-600 body-font">
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
                      <img alt="hotel" class="object-cover object-center w-full h-full block" src="' . htmlspecialchars($data["hotelPhoto"]) . '" onerror="this.onerror=null;this.src=\'https://dummyimage.com/420x260\';">
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

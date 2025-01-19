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
require "../app/models/CarModel.php";
$Car = new CarModel();
$Cars = $Car->getAllCars(); // Fetch data from the database
?>

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
  src="<?php echo htmlspecialchars($car['carPhoto']); ?>" 
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
              <p class="mt-1">Price per Hour: $<?php echo htmlspecialchars($car['price_per_hour']); ?></p>
              <p class="mt-1">Make Year: <?php echo htmlspecialchars($car['make_year']); ?></p>
            </div>
          </div>
        <?php } ?>
      <?php } else { ?>
        <p class="text-center text-gray-500">No cars available at the moment.</p>
      <?php } ?>
    </div>
  </div>
</section>
<?php include('../public/inc/footer.php'); ?>
</body>
</html>

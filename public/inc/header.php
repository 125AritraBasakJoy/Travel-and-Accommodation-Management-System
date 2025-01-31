<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header class="text-gray-600 body-font">
        <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
            <a href="<?php echo BASE_URL ?>" class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-indigo-500 rounded-full" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span class="ml-3 text-xl">TAMS</span>
            </a>
            <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
                <a href="<?php echo BASE_URL ?>hotel" class="mr-5 hover:text-gray-900">Hotels</a>
                <a href="<?php echo BASE_URL ?>car" class="mr-5 hover:text-gray-900">Cars</a>
                <a href="<?php echo BASE_URL ?>flight" class="mr-5 hover:text-gray-900">Flights</a>
                <a href="#" class="mr-5 hover:text-gray-900">Bundle + Save</a>
            </nav>
            <div class="inline-flex items-center space-x-4">
                <?php if (isset($_SESSION['user_name'])): ?>
                    <!-- My Bookings Dropdown -->
                    <div class="relative">
                        <button id="bookingsDropdownBtn" class="bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base">
                            My Bookings
                        </button>
                        <div id="bookingsDropdownMenu" class="absolute hidden bg-white shadow-md mt-1 py-1 rounded w-48">
                            <a href="<?php echo BASE_URL ?>booking/hotel" class="block px-4 py-2 hover:bg-gray-100">Hotel Bookings</a>
                            <a href="<?php echo BASE_URL ?>booking/car" class="block px-4 py-2 hover:bg-gray-100">Car Bookings</a>
                            <a href="<?php echo BASE_URL ?>booking/flight" class="block px-4 py-2 hover:bg-gray-100">Flight Bookings</a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- User Dropdown -->
                <div class="relative">
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <div class="relative">
                            <button id="userDropdownBtn" class="bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base">
                                <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <div id="userDropdownMenu" class="absolute hidden bg-white shadow-md mt-1 py-1 rounded w-40">
                                <a href="<?php echo BASE_URL ?>/user" class="block px-4 py-2 hover:bg-gray-100">My Account</a>
                                <a href="<?php echo BASE_URL ?>/login" class="block px-4 py-2 hover:bg-gray-100">Sign Out</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL ?>/login" class="bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // User Dropdown
            const userButton = document.querySelector("#userDropdownBtn");
            const userDropdownMenu = document.querySelector("#userDropdownMenu");

            if (userButton && userDropdownMenu) {
                userButton.addEventListener("click", function (event) {
                    event.stopPropagation();
                    userDropdownMenu.classList.toggle("hidden");
                });

                document.addEventListener("click", function (event) {
                    if (!userButton.contains(event.target) && !userDropdownMenu.contains(event.target)) {
                        userDropdownMenu.classList.add("hidden");
                    }
                });
            }

            // My Bookings Dropdown
            const bookingsButton = document.querySelector("#bookingsDropdownBtn");
            const bookingsDropdownMenu = document.querySelector("#bookingsDropdownMenu");

            if (bookingsButton && bookingsDropdownMenu) {
                bookingsButton.addEventListener("click", function (event) {
                    event.stopPropagation();
                    bookingsDropdownMenu.classList.toggle("hidden");
                });

                document.addEventListener("click", function (event) {
                    if (!bookingsButton.contains(event.target) && !bookingsDropdownMenu.contains(event.target)) {
                        bookingsDropdownMenu.classList.add("hidden");
                    }
                });
            }
        });
    </script>
</body>
</html>
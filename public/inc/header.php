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
            <a href="<?php echo BASE_URL?>" class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-indigo-500 rounded-full" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span class="ml-3 text-xl">TAMS</span>
            </a>
            <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">

    <a href="<?php echo BASE_URL?>/hotel" class="mr-5 hover:text-gray-900">Hotels</a>
    <a href="<?php echo BASE_URL?>/car" class="mr-5 hover:text-gray-900">Cars</a>
    <a href="<?php echo BASE_URL?>/flight" class="mr-5 hover:text-gray-900">Flights</a>

<script>
    // Add active class to the current navigation link
    document.addEventListener("DOMContentLoaded", function () {
        const currentPath = window.location.pathname.split("/").pop(); // Get the last part of the path
        const links = document.querySelectorAll("nav a");
        
        links.forEach(link => {
            if (link.getAttribute("href") === currentPath) {
                link.classList.add("text-indigo-500", "font-bold"); // Add active styles
            } else {
                link.classList.remove("text-indigo-500", "font-bold"); // Remove active styles
            }
        });
    });
</script>

                <a href="#" class="mr-5 hover:text-gray-900">Bundle + Save</a>
            </nav>
            <div class="inline-flex items-center space-x-4">
                <div class="relative">
                    <button class="bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base">Select Your Language</button>
                    <div class="absolute hidden bg-white shadow-md mt-1 py-1 rounded">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">English (US)</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Español (US)</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Français (CA)</a>
                    </div>
                </div>
                <div class="relative">
                    <button class="bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base">My Trips</button>
                    <div class="absolute hidden bg-white shadow-md mt-1 py-1 rounded">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">See My Trips</a>
                    </div>
                </div>
                <div class="relative">
                    <a href="login" class="bg-gray-100 border-0 py-1 px-3 focus:outline-none hover:bg-gray-200 rounded text-base">Member</button>
                    <div class="absolute hidden bg-white shadow-md mt-1 py-1 rounded">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">My Trips</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">My Account</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>
</html>

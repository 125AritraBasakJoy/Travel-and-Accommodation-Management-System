<!-- admin_dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        .dashboard-card {
            border-radius: 10px;
            text-align: center;
            padding: 20px;
            margin: 10px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .dashboard-card:hover {
            background-color: #e9ecef;
        }
        .dashboard-card i {
            font-size: 50px;
            margin-bottom: 15px;
        }
        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .card-container .card {
            width: 18rem;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Admin Dashboard</h1>
    
    <!-- Cards for Car, Flight, Hotel, Vendor, and User Management -->
    <div class="card-container">
        <!-- Car Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/car'">
            <i class="fas fa-car"></i>
            <h4>Manage Cars</h4>
            <p>Click to manage the cars in your system.</p>
        </div>
        
        <!-- Flight Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/flight'">
            <i class="fas fa-plane"></i> <!-- Changed to plane icon for flight -->
            <h4>Manage Flights</h4>
            <p>Click to manage the flight details in your system.</p>
        </div>
        
        <!-- Hotel Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/hotel'">
            <i class="fas fa-hotel"></i> <!-- Changed to hotel icon for clarity -->
            <h4>Manage Hotels</h4>
            <p>Click to manage the hotels in your system.</p>
        </div>

        <!-- Vendor Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/vendor'">
            <i class="fas fa-briefcase"></i> <!-- Changed to briefcase icon for vendor -->
            <h4>Manage Vendors</h4>
            <p>Click to manage the vendors in your system.</p>
        </div>

        <!-- User Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/user'">
            <i class="fas fa-users"></i>
            <h4>Manage Users</h4>
            <p>Click to manage the users in your system.</p>
        </div>

        <!-- User Management Card -->
        <div class="card dashboard-card" onclick="window.location.href='admin/location'">
            <i class="fas fa-users"></i>
            <h4>Manage location</h4>
            <p>Click to manage the users in your system.</p>
        </div>        
    </div>
</div>


<!-- Bootstrap JS (Optional, but recommended for dynamic content) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

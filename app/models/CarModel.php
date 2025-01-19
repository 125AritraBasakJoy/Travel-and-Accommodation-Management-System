<?php
class CarModel
{
    private $db;
 
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection(); // Assuming Database class returns a MySQLi connection
    }
 
    // Read all cars
    public function getAllCars()
    {
        $query = "SELECT `car_id`, `vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `location_id`, `created_at` FROM `car`";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC); // Return result as associative array
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
 
    // Read a specific car by ID
    public function getCarById($car_id)
    {
        $query = "SELECT `car_id`, `vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `location_id`, `created_at` FROM `car` WHERE `car_id` = ?";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        $stmt->bind_param("i", $car_id); // Bind the car_id parameter
 
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc(); // Fetch a single row as associative array
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
 
    // Create a new car record
    public function createCar($vendor_id, $model, $make_year, $seats, $price_per_hour, $location_id)
    {
        $query = "INSERT INTO `car` (`vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `location_id`, `created_at`) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        $stmt->bind_param("isiiis", $vendor_id, $model, $make_year, $seats, $price_per_hour, $location_id);
 
        if ($stmt->execute()) {
            return $this->db->insert_id; // Return the ID of the newly inserted car
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
 
    // Update a car record
    public function updateCar($car_id, $vendor_id, $model, $make_year, $seats, $price_per_hour, $location_id)
    {
        $query = "UPDATE `car` SET `vendor_id` = ?, `model` = ?, `make_year` = ?, `seats` = ?, `price_per_hour` = ?, `location_id` = ?, `created_at` = NOW() WHERE `car_id` = ?";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        $stmt->bind_param("isiiisi", $vendor_id, $model, $make_year, $seats, $price_per_hour, $location_id, $car_id);
 
        if ($stmt->execute()) {
            return true; // Successfully updated
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
 
    // Delete a car record
    public function deleteCar($car_id)
    {
        $query = "DELETE FROM `car` WHERE `car_id` = ?";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        $stmt->bind_param("i", $car_id); // Bind the car_id parameter
 
        if ($stmt->execute()) {
            return true; // Successfully deleted
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
}
?>
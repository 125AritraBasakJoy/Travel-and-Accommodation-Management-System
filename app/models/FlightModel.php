<?php
class FlightModel
{
    private $db;
 
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection(); // Assuming Database class returns a MySQLi connection
    }
 
    // Read all flights
    public function getAllFlights()
    {
        $query = "SELECT `flight_id`, `vendor_id`, `flight_number`, `departure_location_id`, `arrival_location_id`, `departure_time`, `arrival_time`, `price`, `created_at` FROM `flight`";
        $result = $this->db->query($query);
 
        if ($result) {
            $flights = [];
            while ($row = $result->fetch_assoc()) {
                $flights[] = $row;
            }
            return $flights;
        } else {
            return [];
        }
    }
 
    // Read a specific flight by ID
    public function getFlightById($flight_id)
    {
        $query = "SELECT `flight_id`, `vendor_id`, `flight_number`, `departure_location_id`, `arrival_location_id`, `departure_time`, `arrival_time`, `price`, `created_at` FROM `flight` WHERE `flight_id` = ?";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        $stmt->bind_param("i", $flight_id); // Bind the flight_id parameter
 
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc(); // Fetch a single row as associative array
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
 
    // Create a new flight record
    public function createFlight($vendor_id, $flight_number, $departure_location_id, $arrival_location_id, $departure_time, $arrival_time, $price)
    {
        $query = "INSERT INTO `flight` (`vendor_id`, `flight_number`, `departure_location_id`, `arrival_location_id`, `departure_time`, `arrival_time`, `price`, `created_at`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        $stmt->bind_param("issssdi", $vendor_id, $flight_number, $departure_location_id, $arrival_location_id, $departure_time, $arrival_time, $price);
 
        if ($stmt->execute()) {
            return $this->db->insert_id; // Return the ID of the newly inserted flight
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
 
    // Update a flight record
    public function updateFlight($flight_id, $vendor_id, $flight_number, $departure_location_id, $arrival_location_id, $departure_time, $arrival_time, $price)
    {
        $query = "UPDATE `flight` SET `vendor_id` = ?, `flight_number` = ?, `departure_location_id` = ?, `arrival_location_id` = ?, `departure_time` = ?, `arrival_time` = ?, `price` = ?, `created_at` = NOW() WHERE `flight_id` = ?";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        $stmt->bind_param("issssdis", $vendor_id, $flight_number, $departure_location_id, $arrival_location_id, $departure_time, $arrival_time, $price, $flight_id);
 
        if ($stmt->execute()) {
            return true; // Successfully updated
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
 
    
    // Delete a flight record
    public function deleteFlight($flight_id)
    {
        $query = "DELETE FROM `flight` WHERE `flight_id` = ?";
        $stmt = $this->db->prepare($query);
 
        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }
 
        $stmt->bind_param("i", $flight_id); // Bind the flight_id parameter
 
        if ($stmt->execute()) {
            return true; // Successfully deleted
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
}
?>

has context menu
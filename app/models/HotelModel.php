<?php
class HotelModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection(); // Assuming getConnection() is a mysqli connection
    }

    // Read: Get all hotels
    public function getAllHotels()
    {
        $query = "SELECT `hotel_id`, `vendor_id`, `name`, `location_id`, `description`, `price_per_night`, `capacity`, `created_at`, `hotelPhoto` FROM `hotel`";
        $result = $this->db->query($query);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC); // Fetch results as an associative array
        } else {
            return []; // Return an empty array if there is an error
        }
    }

    // Create: Insert a new hotel
    public function createHotel($vendor_id, $name, $location_id, $description, $price_per_night, $capacity, $hotelPhoto)
    {
        $query = "INSERT INTO `hotel` (`vendor_id`, `name`, `location_id`, `description`, `price_per_night`, `capacity`, `hotelPhoto`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare statement
        if ($stmt = $this->db->prepare($query)) {
            // Bind parameters
            $stmt->bind_param("isisdss", $vendor_id, $name, $location_id, $description, $price_per_night, $capacity, $hotelPhoto);
            
            // Execute query and return true if successful
            return $stmt->execute();
        }
        
        return false; // Return false if the query fails
    }
    

    // Read: Get hotel by ID
    public function getHotelById($hotel_id)
    {
        $query = "SELECT `hotel_id`, `vendor_id`, `name`, `location_id`, `description`, `price_per_night`, `capacity`, `created_at`, `hotelPhoto` FROM `hotel` WHERE `hotel_id` = ?";
        
        // Prepare statement
        if ($stmt = $this->db->prepare($query)) {
            // Bind parameters
            $stmt->bind_param("i", $hotel_id);
            
            // Execute and fetch result
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                return $result->fetch_assoc(); // Return the first row as an associative array
            }
        }
        
        return null; // Return null if no hotel is found
    }

    public function updateHotel($hotel_id, $vendor_id, $name, $location_id, $description, $price_per_night, $capacity, $hotelPhoto)
    {
        $query = "UPDATE `hotel` SET `vendor_id` = ?, `name` = ?, `location_id` = ?, `description` = ?, `price_per_night` = ?, `capacity` = ?, `hotelPhoto` = ? WHERE `hotel_id` = ?";
        
        // Prepare statement
        if ($stmt = $this->db->prepare($query)) {
            // Bind parameters
            $stmt->bind_param("isisdssi", $vendor_id, $name, $location_id, $description, $price_per_night, $capacity, $hotelPhoto, $hotel_id);
            
            // Execute query and return true if successful
            return $stmt->execute();
        }
        
        return false; // Return false if the query fails
    }
    

    // Delete: Delete a hotel by ID
    public function deleteHotel($hotel_id)
    {
        $query = "DELETE FROM `hotel` WHERE `hotel_id` = ?";
        
        // Prepare statement
        if ($stmt = $this->db->prepare($query)) {
            // Bind parameters
            $stmt->bind_param("i", $hotel_id);
            
            // Execute query and return true if successful
            return $stmt->execute();
        }
        
        return false; // Return false if the query fails
    }
}
?>

<?php
class Location
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // CREATE: Add a new location
    public function createLocation($city, $state, $country, $locationPhoto)
    {
        $query = "INSERT INTO `location` (`city`, `state`, `country`, `locationPhoto`) 
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssss", $city, $state, $country, $locationPhoto);
            return $stmt->execute();
        } else {
            return false;
        }
    }

    // READ: Get all locations
    public function getAllLocations()
    {
        $query = "SELECT `location_id`, `city`, `state`, `country`, `locationPhoto` FROM `location`";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    // READ: Get a single location by ID
    public function getLocationById($location_id)
    {
        $query = "SELECT `location_id`, `city`, `state`, `country`, `locationPhoto` 
                  FROM `location` WHERE `location_id` = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $location_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // UPDATE: Update a location's details
    public function updateLocation($location_id, $city, $state, $country, $locationPhoto)
    {
        $query = "UPDATE `location` SET `city` = ?, `state` = ?, `country` = ?, `locationPhoto` = ? 
                  WHERE `location_id` = ?";

        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssssi", $city, $state, $country, $locationPhoto, $location_id);
            return $stmt->execute();
        } else {
            return false;
        }
    }

    // DELETE: Delete a location by ID
    public function deleteLocation($location_id)
    {
        $query = "DELETE FROM `location` WHERE `location_id` = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("i", $location_id);
            return $stmt->execute();
        } else {
            return false;
        }
    }
}
?>

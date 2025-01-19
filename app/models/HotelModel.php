<?php
class HotelModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection(); // Assuming getConnection() is a mysqli connection
    }

    public function getAllHotels()
    {
        $query = "SELECT `hotel_id`, `vendor_id`, `name`, `location_id`, `description`, `price_per_night`, `capacity`, `created_at`,`hotelPhoto` FROM `hotel`";
        $result = $this->db->query($query);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC); // Fetch results as an associative array
        } else {
            return []; // Return an empty array if there is an error
        }
    }
}
?>

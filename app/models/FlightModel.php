<?php
class FlightModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

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
}
?>

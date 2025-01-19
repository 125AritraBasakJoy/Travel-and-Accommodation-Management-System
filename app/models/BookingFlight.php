<?php
class BookingFlight
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllBookingFlights()
    {
        $query = "SELECT `booking_id`, `user_id`, `flight_id`, `status`, `seat_number`, `total_price`, `created_at` FROM `booking_flight`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

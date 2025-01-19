<?php
class BookingHotel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllBookingHotels()
    {
        $query = "SELECT `booking_id`, `user_id`, `hotel_id`, `check_in`, `check_out`, `status`, `total_price`, `created_at` FROM `booking_hotel`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

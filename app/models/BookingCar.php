<?php
class BookingCar
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllBookingCars()
    {
        $query = "SELECT `booking_id`, `user_id`, `car_id`, `start_time`, `end_time`, `status`, `total_price`, `created_at` FROM `booking_car`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

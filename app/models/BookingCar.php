<?php
class BookingCar
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // ✅ Get all car bookings
    public function getAllBookingcar(): array
    {
        $query = "SELECT 
                    bc.booking_id, 
                    bc.user_id, 
                    bc.car_id, 
                    bc.start_time, 
                    bc.end_time, 
                    bc.status, 
                    bc.total_price, 
                    bc.created_at,
                    c.model AS car_model,
                    c.make_year,
                    c.seats,
                    c.price_per_hour
                  FROM booking_car bc
                  LEFT JOIN car c ON bc.car_id = c.car_id";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            $bookings = [];
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }

            $stmt->close();
            return $bookings;
        } catch (Exception $e) {
            // Log the error and return an empty array
            error_log("Error fetching bookings: " . $e->getMessage());
            return [];
        }
    }

    // ✅ Get car bookings by User ID
    public function getUserBookings(int $user_id): array
    {
        $query = "SELECT 
                    bc.booking_id, 
                    bc.user_id, 
                    bc.car_id, 
                    bc.start_time, 
                    bc.end_time, 
                    bc.status, 
                    bc.total_price, 
                    bc.created_at,
                    c.model AS car_model,
                    c.make_year,
                    c.seats,
                    c.price_per_hour
                  FROM booking_car bc
                  LEFT JOIN car c ON bc.car_id = c.car_id
                  WHERE bc.user_id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $bookings = [];
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }

            $stmt->close();
            return $bookings;
        } catch (Exception $e) {
            // Log the error and return an empty array
            error_log("Error fetching user bookings: " . $e->getMessage());
            return [];
        }
    }

    // ✅ Cancel a car booking
    public function cancelBooking(int $booking_id, int $user_id): bool
    {
        $query = "UPDATE booking_car 
                  SET status = 'Canceled' 
                  WHERE booking_id = ? AND user_id = ? AND status = 'Pending'";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ii", $booking_id, $user_id);
            $success = $stmt->execute();
            $stmt->close();

            return $success;
        } catch (Exception $e) {
            // Log the error and return false
            error_log("Error canceling booking: " . $e->getMessage());
            return false;
        }
    }

    // ✅ Create a new car booking
    public function bookCar(int $user_id, int $car_id, string $start_time, string $end_time, float $total_price): bool
    {
        $query = "INSERT INTO booking_car (user_id, car_id, start_time, end_time, status, total_price, created_at) 
                  VALUES (?, ?, ?, ?, 'Pending', ?, NOW())";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iissd", $user_id, $car_id, $start_time, $end_time, $total_price);
            $success = $stmt->execute();
            $stmt->close();

            return $success;
        } catch (Exception $e) {
            // Log the error and return false
            error_log("Error creating booking: " . $e->getMessage());
            return false;
        }
    }
}
?>
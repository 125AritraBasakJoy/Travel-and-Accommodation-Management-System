<?php
class BookingFlight
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // ✅ Get all flight bookings with flight details
    public function getAllBookingFlights()
    {
        $query = "SELECT 
                    bf.booking_id, 
                    bf.user_id, 
                    bf.flight_id, 
                    bf.status, 
                    bf.seat_number, 
                    bf.total_price, 
                    bf.created_at,
                    f.flight_number,
                    f.departure_time,
                    f.arrival_time,
                    f.departure_location_id,
                    f.arrival_location_id
                  FROM booking_flight bf
                  INNER JOIN flight f ON bf.flight_id = f.flight_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        $stmt->close();
        return $bookings;
    }

    // ✅ Get flight bookings by User ID
    public function getUserBookings($user_id)
    {
        $query = "SELECT 
                    bf.booking_id, 
                    bf.user_id, 
                    bf.flight_id, 
                    bf.status, 
                    bf.seat_number, 
                    bf.total_price, 
                    bf.created_at,
                    f.flight_number,
                    f.departure_time,
                    f.arrival_time,
                    f.departure_location_id,
                    f.arrival_location_id
                  FROM booking_flight bf
                  INNER JOIN flight f ON bf.flight_id = f.flight_id
                  WHERE bf.user_id = ?";

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
    }

    // ✅ Cancel a flight booking
    public function cancelBooking($booking_id, $user_id)
    {
        $query = "UPDATE booking_flight 
                  SET status = 'Canceled' 
                  WHERE booking_id = ? AND user_id = ? AND status = 'Pending'";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $booking_id, $user_id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // ✅ Create a new flight booking
    public function createBooking($user_id, $flight_id, $seat_number, $total_price)
    {
        $query = "INSERT INTO booking_flight (user_id, flight_id, status, seat_number, total_price, created_at) 
                  VALUES (?, ?, 'Pending', ?, ?, NOW())";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iisd", $user_id, $flight_id, $seat_number, $total_price);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
    public function bookFlight($user_id, $flight_id, $seat_number, $total_price)
{
    $query = "INSERT INTO booking_flight (user_id, flight_id, seat_number, total_price, status, created_at)
              VALUES (?, ?, ?, ?, 'Pending', NOW())";
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("iiid", $user_id, $flight_id, $seat_number, $total_price);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}


    // ✅ Update flight booking details
    public function updateBooking($booking_id, $seat_number, $total_price, $status)
    {
        $query = "UPDATE booking_flight 
                  SET seat_number = ?, total_price = ?, status = ? 
                  WHERE booking_id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("dsis", $seat_number, $total_price, $status, $booking_id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
?>

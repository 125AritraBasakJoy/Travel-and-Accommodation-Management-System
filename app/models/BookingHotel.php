<?php
class BookingHotel
{
    private $db;

    public function __construct()
    {
        $database = new Database();  // Assuming you have a Database class to handle DB connection
        $this->db = $database->getConnection();  // Store the connection in $this->db
    }

    // ✅ Get all hotel bookings
    public function getAllBookingHotels()
    {
        $query = "SELECT * FROM `booking_hotel` ORDER BY `created_at` DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserBookings($user_id) {
        // SQL query to get bookings along with hotel details
        $query = "
            SELECT 
                b.booking_id, 
                b.user_id, 
                b.hotel_id, 
                b.check_in, 
                b.check_out, 
                b.status, 
                b.total_price, 
                b.created_at, 
                h.name AS hotel_name, 
                h.location_id, 
                h.description AS hotel_description, 
                h.price_per_night, 
                h.hotelPhoto, 
                h.capacity 
            FROM booking_hotel b
            LEFT JOIN hotel h ON b.hotel_id = h.hotel_id
            WHERE b.user_id = ?";
    
        // Prepare the SQL statement
        if ($stmt = $this->db->prepare($query)) {
            // Bind parameters
            $stmt->bind_param("i", $user_id);  // "i" means integer (user_id)
    
            // Execute the query
            $stmt->execute();
    
            // Get the result
            $result = $stmt->get_result();
    
            // Initialize an array to hold the bookings
            $bookings = [];
    
            // Fetch the rows and add them to the $bookings array
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
    
            // Close the statement
            $stmt->close();
    
            return $bookings;
        } else {
            // If the statement fails to prepare, return an empty array or error
            return [];
        }
    }
    
    
    
    public function cancelBooking($booking_id, $user_id)
    {
        $query = "UPDATE booking_hotel SET status = 'Canceled' WHERE booking_id = ? AND user_id = ? AND status = 'Pending'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $booking_id, $user_id);
        return $stmt->execute();
    }

    // ✅ Get booking by ID
    public function getBookingById($booking_id)
    {
        $query = "SELECT * FROM `booking_hotel` WHERE `booking_id` = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ Get bookings by User ID
    public function getBookingsByUser($user_id)
    {
        $query = "SELECT * FROM `booking_hotel` WHERE `user_id` = ? ORDER BY `created_at` DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ✅ Create a new booking
    public function createBooking($user_id, $hotel_id, $check_in, $check_out, $total_price)
    {
        $query = "INSERT INTO `booking_hotel` (`user_id`, `hotel_id`, `check_in`, `check_out`, `status`, `total_price`, `created_at`) 
                  VALUES (?, ?, ?, ?, 'pending', ?, NOW())";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iissi", $user_id, $hotel_id, $check_in, $check_out, $total_price);
        return $stmt->execute();
    }

    // ✅ Update booking details
    public function updateBooking($booking_id, $check_in, $check_out, $total_price, $status)
    {
        $query = "UPDATE `booking_hotel` SET `check_in` = ?, `check_out` = ?, `total_price` = ?, `status` = ? WHERE `booking_id` = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssssi", $check_in, $check_out, $total_price, $status, $booking_id);
        return $stmt->execute();
    }

    // ✅ Cancel (delete) a booking
    public function deleteBooking($booking_id)
    {
        $query = "DELETE FROM `booking_hotel` WHERE `booking_id` = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $booking_id);
        return $stmt->execute();
    }

    // ✅ Search bookings by status
    public function searchBookingsByStatus($status)
    {
        $query = "SELECT * FROM `booking_hotel` WHERE `status` = ? ORDER BY `created_at` DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

// ✅ Get all bookings for a specific hotel (including hotel name)
public function getBookingsByHotel($hotel_id)
{
    $query = "SELECT bh.*, h.name AS hotel_name 
              FROM booking_hotel bh 
              JOIN hotel h ON bh.hotel_id = h.hotel_id 
              WHERE bh.hotel_id = ? 
              ORDER BY bh.created_at DESC";
    
    $stmt = $this->db->prepare($query);
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

}

?>

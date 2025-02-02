<?php
class BookingModel {
    private $db;

    public function __construct()
    {
        $database = new Database();  // Assuming you have a Database class to handle DB dbection
        $this->db = $database->getConnection();  // Store the connection in $this->db
    }
    // Fetch hotel bookings by vendor ID
    public function getHotelBookingsByVendorId($vendor_id) {
        $sql = "SELECT 
        booking_hotel.booking_id, 
        booking_hotel.user_id, 
        booking_hotel.hotel_id, 
        CONCAT(booking_hotel.check_in, ' to ', booking_hotel.check_out) AS `time`, 
        booking_hotel.status, 
        booking_hotel.total_price, 
        booking_hotel.created_at AS booking_created_at, 
        hotel.hotel_id, 
        hotel.vendor_id, 
        hotel.name, 
        hotel.location_id, 
        hotel.description, 
        hotel.price_per_night, 
        hotel.hotelPhoto, 
        hotel.capacity, 
        hotel.created_at AS hotel_created_at
    FROM 
        booking_hotel 
    JOIN 
        hotel 
    ON 
        booking_hotel.hotel_id = hotel.hotel_id 
    WHERE 
        hotel.vendor_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch flight bookings by vendor ID
    public function getFlightBookingsByVendorId($vendor_id) {
        $sql = "SELECT 
        booking_flight.booking_id, 
        booking_flight.user_id, 
        booking_flight.flight_id, 
        booking_flight.status, 
        booking_flight.seat_number, 
        booking_flight.total_price, 
        booking_flight.created_at AS booking_created_at, 
        CONCAT(flight.departure_time, ' to ', flight.arrival_time) AS `time`, 
        flight.flight_id, 
        flight.vendor_id, 
        flight.flight_number, 
        flight.departure_location_id, 
        flight.arrival_location_id AS location_id, 
        flight.price, 
        flight.created_at AS flight_created_at
    FROM 
        booking_flight 
    JOIN 
        flight 
    ON 
        booking_flight.flight_id = flight.flight_id 
    WHERE 
        flight.vendor_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch car bookings by vendor ID
    public function getCarBookingsByVendorId($vendor_id) {
        $sql = "SELECT 
        booking_car.booking_id, 
        booking_car.user_id, 
        booking_car.car_id, 
        CONCAT(booking_car.start_time, ' to ', booking_car.end_time) AS `time`, 
        booking_car.status, 
        booking_car.total_price, 
        booking_car.created_at, 
        car.car_id, 
        car.vendor_id, 
        car.model, 
        car.make_year, 
        car.seats, 
        car.price_per_hour, 
        car.carPhoto, 
        car.location_id, 
        car.created_at AS car_created_at
    FROM 
        booking_car 
    JOIN 
        car 
    ON 
        booking_car.car_id = car.car_id 
    WHERE 
        car.vendor_id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Update hotel booking status
    public function updateHotelBookingStatus($booking_id, $status) {
        $sql = "UPDATE booking_hotel SET status = ? WHERE booking_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $booking_id);
        return $stmt->execute();
    }

    // Update flight booking status
    public function updateFlightBookingStatus($booking_id, $status) {
        $sql = "UPDATE booking_flight SET status = ? WHERE booking_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $booking_id);
        return $stmt->execute();
    }

    // Update car booking status
    public function updateCarBookingStatus($booking_id, $status) {
        $sql = "UPDATE booking_car SET status = ? WHERE booking_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $status, $booking_id);
        return $stmt->execute();
    }
}
?>

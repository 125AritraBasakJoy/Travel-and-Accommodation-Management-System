<?php
class TripPlannerModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getTotalCost(?array $hotel, ?array $flight, ?array $car, int $hotelNights = 3, int $carHours = 5): float
    {
        return $this->calculateTotalCost($hotel, $flight, $car, $hotelNights, $carHours);
    }

    public function findBudgetTrip(int $location_id): array
    {
        $hotels = $this->getHotelsByLocation($location_id);
        $flights = $this->getFlightsByLocation($location_id);
        $cars = $this->getCarsByLocation($location_id);

        $cheapestHotel = $this->findCheapestHotel($hotels);
        $cheapestFlight = $this->findCheapestFlight($flights);
        $cheapestCar = $this->findCheapestCar($cars);

        $totalCost = $this->calculateTotalCost($cheapestHotel, $cheapestFlight, $cheapestCar);

        return [
            'hotel' => $cheapestHotel,
            'flight' => $cheapestFlight,
            'car' => $cheapestCar,
            'total_cost' => $totalCost,
        ];
    }

    public function fillModal(int $location_id): array
    {
        $hotels = $this->getHotelsByLocation($location_id);
        $flights = $this->getFlightsByLocation($location_id);
        $cars = $this->getCarsByLocation($location_id);

        return [
            'hotels' => $hotels,
            'flights' => $flights,
            'cars' => $cars,
        ];
    }

    private function getHotelsByLocation(int $location_id): array
    {
        $query = "SELECT * FROM hotel WHERE location_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $location_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $hotels = [];
        while ($row = $result->fetch_assoc()) {
            $hotels[] = $row;
        }

        return $hotels;
    }

    private function getFlightsByLocation(int $location_id): array
    {
        $query = "SELECT * FROM flight WHERE departure_location_id = ? OR arrival_location_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $location_id, $location_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $flights = [];
        while ($row = $result->fetch_assoc()) {
            $flights[] = $row;
        }

        return $flights;
    }

    private function getCarsByLocation(int $location_id): array
    {
        $query = "SELECT * FROM car WHERE location_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $location_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $cars = [];
        while ($row = $result->fetch_assoc()) {
            $cars[] = $row;
        }

        return $cars;
    }

    private function findCheapestHotel(array $hotels): ?array
    {
        if (empty($hotels)) {
            return null;
        }

        $cheapest = $hotels[0];
        foreach ($hotels as $hotel) {
            if ($hotel['price_per_night'] < $cheapest['price_per_night']) {
                $cheapest = $hotel;
            }
        }

        return $cheapest;
    }

    private function findCheapestFlight(array $flights): ?array
    {
        if (empty($flights)) {
            return null;
        }

        $cheapest = $flights[0];
        foreach ($flights as $flight) {
            if ($flight['price'] < $cheapest['price']) {
                $cheapest = $flight;
            }
        }

        return $cheapest;
    }

    private function findCheapestCar(array $cars): ?array
    {
        if (empty($cars)) {
            return null;
        }

        $cheapest = $cars[0];
        foreach ($cars as $car) {
            if ($car['price_per_hour'] < $cheapest['price_per_hour']) {
                $cheapest = $car;
            }
        }

        return $cheapest;
    }

    public function calculateTotalCost(?array $hotel, ?array $flight, ?array $car, int $hotelNights = 3, int $carHours = 5): float
    {
        $totalCost = 0;

        if ($hotel && $hotelNights > 0) {
            $totalCost += $hotel['price_per_night'] * $hotelNights;
        }

        if ($flight) {
            $totalCost += $flight['price'];
        }

        if ($car && $carHours > 0) {
            $totalCost += $car['price_per_hour'] * $carHours;
        }

        return $totalCost;
    }
}
?>
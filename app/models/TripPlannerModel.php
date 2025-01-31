<?php
class TripPlannerModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Find the best budget trip for a given location ID.
     *
     * @param int $location_id
     * @return array
     */
    public function findBudgetTrip(int $location_id): array
    {
        // Fetch all hotels, flights, and cars for the given location
        $hotels = $this->getHotelsByLocation($location_id);
        $flights = $this->getFlightsByLocation($location_id);
        $cars = $this->getCarsByLocation($location_id);

        // Find the cheapest options
        $cheapestHotel = $this->findCheapestHotel($hotels);
        $cheapestFlight = $this->findCheapestFlight($flights);
        $cheapestCar = $this->findCheapestCar($cars);

        // Calculate total cost
        $totalCost = $this->calculateTotalCost($cheapestHotel, $cheapestFlight, $cheapestCar);

        // Return the budget trip plan
        return [
            'hotel' => $cheapestHotel,
            'flight' => $cheapestFlight,
            'car' => $cheapestCar,
            'total_cost' => $totalCost,
        ];
    }

    /**
     * Fetch hotels by location ID.
     *
     * @param int $location_id
     * @return array
     */
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

    /**
     * Fetch flights by location ID.
     *
     * @param int $location_id
     * @return array
     */
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

    /**
     * Fetch cars by location ID.
     *
     * @param int $location_id
     * @return array
     */
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

    /**
     * Find the cheapest hotel.
     *
     * @param array $hotels
     * @return array|null
     */
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

    /**
     * Find the cheapest flight.
     *
     * @param array $flights
     * @return array|null
     */
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

    /**
     * Find the cheapest car.
     *
     * @param array $cars
     * @return array|null
     */
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

    /**
     * Calculate the total cost of the trip.
     *
     * @param array|null $hotel
     * @param array|null $flight
     * @param array|null $car
     * @return float
     */
    private function calculateTotalCost(?array $hotel, ?array $flight, ?array $car): float
    {
        $totalCost = 0;

        // Assume 3 nights for the hotel
        if ($hotel) {
            $totalCost += $hotel['price_per_night'] * 3;
        }

        // Add flight cost
        if ($flight) {
            $totalCost += $flight['price'];
        }

        // Assume 5 hours for the car rental
        if ($car) {
            $totalCost += $car['price_per_hour'] * 5;
        }

        return $totalCost;
    }
}
?>
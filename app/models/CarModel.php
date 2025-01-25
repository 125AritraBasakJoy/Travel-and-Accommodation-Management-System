<?php
class CarModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // CREATE a new car
    public function createCar($vendor_id, $model, $make_year, $seats, $price_per_hour, $location_id, $carPhoto)
    {
        $query = "INSERT INTO `car` (`vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `location_id`, `carPhoto`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param("issiiss", $vendor_id, $model, $make_year, $seats, $price_per_hour, $location_id, $carPhoto);

        if ($stmt->execute()) {
            return $this->db->insert_id; // Return the ID of the newly created car
        } else {
            die("Insert operation failed: " . $stmt->error);
        }
    }

    // READ all cars
    public function getAllCars()
    {
        $query = "SELECT `car_id`, `vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `location_id`, `carPhoto`, `created_at` FROM `car`";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }

    // READ a single car by ID
    public function getCarById($car_id)
    {
        $query = "SELECT `car_id`, `vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `location_id`, `carPhoto`, `created_at` 
                  FROM `car` WHERE `car_id` = ?";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param("i", $car_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_assoc(); // Return single record
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }

    // UPDATE a car
    public function updateCar($car_id, $vendor_id, $model, $make_year, $seats, $price_per_hour, $location_id, $carPhoto)
    {
        $query = "UPDATE `car` 
                  SET `vendor_id` = ?, `model` = ?, `make_year` = ?, `seats` = ?, `price_per_hour` = ?, `location_id` = ?, `carPhoto` = ? 
                  WHERE `car_id` = ?";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param("ssiisssi", $vendor_id, $model, $make_year, $seats, $price_per_hour, $location_id, $carPhoto, $car_id);

        if ($stmt->execute()) {
            return $stmt->affected_rows; // Return the number of affected rows
        } else {
            die("Update operation failed: " . $stmt->error);
        }
    }

    // DELETE a car
    public function deleteCar($car_id)
    {
        $query = "DELETE FROM `car` WHERE `car_id` = ?";

        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }

        $stmt->bind_param("i", $car_id);

        if ($stmt->execute()) {
            return $stmt->affected_rows; // Return the number of affected rows
        } else {
            die("Delete operation failed: " . $stmt->error);
        }
    }
// SEARCH cars with filters
public function searchCars($filters = [])
{
    $query = "SELECT `car_id`, `vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `location_id`, `carPhoto`, `created_at` FROM `car` WHERE 1=1";

    $params = [];
    $types = "";

    if (!empty($filters['model'])) {
        $query .= " AND `model` LIKE ?";
        $params[] = "%" . $filters['model'] . "%";
        $types .= "s";
    }

    if (!empty($filters['make_year'])) {
        $query .= " AND `make_year` = ?";
        $params[] = $filters['make_year'];
        $types .= "i";
    }

    if (!empty($filters['seats'])) {
        $query .= " AND `seats` >= ?";
        $params[] = $filters['seats'];
        $types .= "i";
    }

    if (!empty($filters['min_price'])) {
        $query .= " AND `price_per_hour` >= ?";
        $params[] = $filters['min_price'];
        $types .= "d";
    }

    if (!empty($filters['max_price'])) {
        $query .= " AND `price_per_hour` <= ?";
        $params[] = $filters['max_price'];
        $types .= "d";
    }

    if (!empty($filters['location_id'])) {
        $query .= " AND `location_id` = ?";
        $params[] = $filters['location_id'];
        $types .= "i";
    }

    $stmt = $this->db->prepare($query);

    if (!$stmt) {
        die("Failed to prepare statement: " . $this->db->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Query execution failed: " . $stmt->error);
    }
}









}
?>

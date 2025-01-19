<?php
class CarModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllCars()
    {
        $query = "SELECT `car_id`, `vendor_id`, `model`, `make_year`, `seats`, `price_per_hour`, `location_id`, `created_at` FROM `car`";

        // Use MySQLi to prepare and execute the query
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die("Failed to prepare statement: " . $this->db->error);
        }

        // Execute the query
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            die("Query execution failed: " . $stmt->error);
        }
    }
}
?>

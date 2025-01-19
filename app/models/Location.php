<?php
class Location
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllLocations()
    {
        $query = "SELECT `location_id`, `city`, `state`, `country`, `locationPhoto` FROM `location`";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            // Handle errors appropriately
            return [];
        }
    }
}
?>

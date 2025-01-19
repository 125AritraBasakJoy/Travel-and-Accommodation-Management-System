<?php
class Vendor
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllVendors()
    {
        $query = "SELECT `vendor_id`, `name`, `email`, `password`, `phone`, `type`, `profile_status`, `created_at` FROM `vendor`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

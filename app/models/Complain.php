<?php
class Complain
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllComplains()
    {
        $query = "SELECT `complain_id`, `user_id`, `type`, `item_id`, `complain_text`, `status`, `created_at` FROM `complain`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

  
   <?php
class Admin
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection(); 
    }

    public function getAllAdmins()
    {
        $query = "SELECT `admin_id`, `name`, `email`, `password`, `phone`, `created_at` FROM `admin`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

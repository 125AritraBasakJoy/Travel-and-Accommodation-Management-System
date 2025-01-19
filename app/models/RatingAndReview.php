<?php
class RatingAndReview
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllReviews()
    {
        $query = "SELECT `review_id`, `user_id`, `type`, `item_id`, `rating`, `review_text`, `created_at` FROM `ratingandreview`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

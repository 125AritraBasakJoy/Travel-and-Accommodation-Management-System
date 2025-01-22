<?php
class Vendor
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // CREATE a new vendor
    public function createVendor($name, $email, $password, $phone, $type)
    {
        // Step 1: Check if the vendor already exists with the provided email
        $sql = "SELECT * FROM vendor WHERE email = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            $stmt->bindParam(1, $email);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return false; // Vendor already exists
            }
        }
    
        // Step 2: Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Step 3: Insert the new vendor into the database
        $sql = "INSERT INTO vendor (name, email, password, phone, type, profile_status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            // Bind the parameters to the query
            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $email);
            $stmt->bindParam(3, $hashedPassword);
            $stmt->bindParam(4, $phone);
            $stmt->bindParam(5, $type);
            $stmt->bindParam(6, $profile_status = 'Pending'); // Default profile status
    
            if ($stmt->execute()) {
                return true; // Vendor account created successfully
            }
        }
    
        return false; // Failed to create vendor
    }

    // READ all vendors
    public function getAllVendors()
    {
        $query = "SELECT `vendor_id`, `name`, `email`, `phone`, `type`, `profile_status`, `created_at` FROM `vendor`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
    
        // Fetch all rows using mysqli
        $result = $stmt->get_result();
        $vendors = [];
        while ($row = $result->fetch_assoc()) {
            $vendors[] = $row;
        }
        return $vendors;
    }
    
    // READ a single vendor by ID
    public function getVendorById($vendor_id)
    {
        $query = "SELECT `vendor_id`, `name`, `email`, `phone`, `type`, `profile_status`, `created_at` FROM `vendor` WHERE vendor_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $vendor_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE vendor information
    public function updateVendor($vendor_id, $name, $email, $phone, $type, $profile_status)
    {
        // Check if the email already exists for another vendor
        $sql = "SELECT * FROM vendor WHERE email = ? AND vendor_id != ?";
        $stmt = $this->db->prepare($sql);
    
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->db->error);
        }
    
        $stmt->bind_param('si', $email, $vendor_id);
        $stmt->execute();
    
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $result->free(); // Free the result
            return false;    // Email already exists
        }
        $result->free();
    
        // Update vendor details
        $sql = "UPDATE vendor SET name = ?, email = ?, phone = ?, type = ?, profile_status = ? WHERE vendor_id = ?";
        $stmt = $this->db->prepare($sql);
    
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->db->error);
        }
    
        $stmt->bind_param('sssssi', $name, $email, $phone, $type, $profile_status, $vendor_id);
        return $stmt->execute();
    }
    
    

    // DELETE a vendor
    public function deleteVendor($vendor_id)
    {
        $sql = "DELETE FROM vendor WHERE vendor_id = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(1, $vendor_id);
            if ($stmt->execute()) {
                return true; // Vendor deleted successfully
            }
        }
    
        return false; // Failed to delete vendor
    }

    // Vendor Login (validate credentials)
    public function validateLogin($email, $password)
    {
        $sql = "SELECT `vendor_id`, `name`, `email`, `password`, `profile_status` FROM vendor WHERE `email` = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && password_verify($password, $result['password'])) {
                // Check profile status
                if ($result['profile_status'] === 'Approved') {
                    return $result; // Vendor logged in successfully
                } else {
                    return false; // Profile not approved
                }
            }
        }

        return false; // Invalid login credentials
    }
}
?>

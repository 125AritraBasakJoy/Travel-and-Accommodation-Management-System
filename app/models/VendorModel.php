<?php
class Vendor
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function createVendor($name, $email, $password, $phone, $type)
    {
        // Step 1: Check if the vendor already exists with the provided email
        $sql = "SELECT * FROM vendor WHERE email = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            $stmt->bind_param('s', $email); // 's' indicates that email is a string
            $stmt->execute();
            
            // Ensure the result set is freed after executing the query
            $stmt->store_result(); // Necessary to store result for rowCount()
            if ($stmt->num_rows > 0) {
                $stmt->free_result(); // Free the result set
                return false; // Vendor already exists
            }
            $stmt->free_result(); // Free the result set after checking
        }
    
        // Step 2: Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Step 3: Insert the new vendor into the database
        $sql = "INSERT INTO vendor (name, email, password, phone, type, profile_status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            // Define profile status as a variable before passing it to bind_param
            $profile_status = 'Pending';
            
            // Bind the parameters to the query
            $stmt->bind_param('ssssss', $name, $email, $hashedPassword, $phone, $type, $profile_status); // 'ssssss' indicates the types for all params
    
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
// VendorModel.php - getVendorById
public function getVendorById($vendor_id)
{
    // Prepare SQL statement
    $sql = "SELECT `vendor_id`, `name`, `email`, `phone`, `type`, `profile_status` FROM vendor WHERE `vendor_id` = ?";
    
    // Prepare the statement
    $stmt = $this->db->prepare($sql);
    
    if ($stmt) {
        // Bind parameters (s - string, i - integer, etc.)
        $stmt->bind_param("i", $vendor_id);
        
        // Execute the query
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        // Fetch the data
        $vendor = $result->fetch_assoc();
        
        // Return the vendor data
        return $vendor;
    }

    return false; // If something goes wrong, return false
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
        $stmt->bind_param("i", $vendor_id); // "i" specifies that vendor_id is an integer
        if ($stmt->execute()) {
            return true; // Vendor deleted successfully
        }
    }

    return false; // Failed to delete vendor
}


// Vendor Login (validate credentials)
public function validateLogin($email, $password)
{
    $sql = "SELECT * FROM vendor WHERE `email` = ?";
    $stmt = $this->db->prepare($sql);
echo "hi";
    if ($stmt) {
        // Use bind_param for MySQLi
        $stmt->bind_param('s', $email); // 's' means string for the email parameter
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc(); // Use get_result() for fetching results

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
// VendorModel.php - changePassword
public function changePassword($vendor_id, $current_password, $new_password)
{
    // Step 1: Fetch the current password from the database
    $sql = "SELECT password FROM vendor WHERE vendor_id = ?";
    $stmt = $this->db->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $vendor_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        // Check if the current password matches the stored password
        if ($result && password_verify($current_password, $result['password'])) {
            // Step 2: Validate the new password (you can add your own validation rules)
            if (strlen($new_password) < 6) {
                return "Password must be at least 6 characters long.";
            }
            
            // Step 3: Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Step 4: Update the password in the database
            $update_sql = "UPDATE vendor SET password = ? WHERE vendor_id = ?";
            $update_stmt = $this->db->prepare($update_sql);
            
            if ($update_stmt) {
                $update_stmt->bind_param("si", $hashed_password, $vendor_id);
                $update_stmt->execute();
                
                if ($update_stmt->affected_rows > 0) {
                    return true; // Password changed successfully
                } else {
                    return "Failed to update password.";
                }
            } else {
                return "Error preparing update statement.";
            }
        } else {
            return "Current password is incorrect.";
        }
    } else {
        return "Error preparing select statement.";
    }
}

}
?>

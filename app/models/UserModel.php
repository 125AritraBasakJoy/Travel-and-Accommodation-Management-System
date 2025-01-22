<?php
// Done by Aritra
class UserModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // READ all users
    public function getAllUsers()
    {
        $sql = "SELECT `user_id`, `name`, `email`, `phone`, `created_at` FROM user";
        $result = $this->db->query($sql);

        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    // READ a single user by ID
    public function getUserById($user_id)
    {
        $sql = "SELECT `user_id`, `name`, `email`, `phone`, `created_at` FROM user WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows === 1) {
                return $result->fetch_assoc(); // Return user data
            }
        }
        return false; // User not found
    }

    // Validate login (already implemented by Nusrat)
    public function validateLogin($email, $password)
    {
        $sql = "SELECT `user_id`, `name`, `email`, `password` FROM user WHERE `email` = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    unset($user['password']);
                    return $user;
                }
            }
        }

        return false;
    }

    // CREATE a new user
    public function createUser($name, $email, $password, $phone)
    {
        // Check if user already exists
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                return false; // User already exists
            }
        }
    
        // Hash the password before storing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Insert the new user into the database
        $sql = "INSERT INTO user (name, email, password, phone, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            $stmt->bind_param('ssss', $name, $email, $hashedPassword, $phone);
    
            if ($stmt->execute()) {
                return true; // User created successfully
            }
        }
    
        return false; // Failed to create user
    }

    // UPDATE an existing user
    public function updateUser($user_id, $name, $email, $phone)
    {
        // Check if email is already taken by another user
        $sql = "SELECT * FROM user WHERE email = ? AND user_id != ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            $stmt->bind_param('si', $email, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                return false; // Email already taken
            }
        }
    
        // Update user details
        $sql = "UPDATE user SET name = ?, email = ?, phone = ? WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            $stmt->bind_param('sssi', $name, $email, $phone, $user_id);
    
            if ($stmt->execute()) {
                return true; // User updated successfully
            }
        }
    
        return false; // Failed to update user
    }

    // DELETE a user
    public function deleteUser($user_id)
    {
        $sql = "DELETE FROM user WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('i', $user_id);
            if ($stmt->execute()) {
                return true; // User deleted successfully
            }
        }
    
        return false; // Failed to delete user
    }
}
?>

<?php

class UserModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllUsers()
    {
        $sql = "SELECT `user_id`, `name`, `email`, `password`, `phone`, `created_at` FROM user";
        $result = $this->db->query($sql);

        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    //Done by Aritra
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

    public function createUser($name, $email, $password, $phone)
    {
        // Step 1: Check if the user already exists with the provided email
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
    
            // If a user with the same email already exists
            if ($result->num_rows > 0) {
                return false; // User already exists
            }
        }
    
        // Step 2: Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Step 3: Insert the new user into the database
        $sql = "INSERT INTO user (name, email, password, phone, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt) {
            // Bind the parameters to the query
            $stmt->bind_param('ssss', $name, $email, $hashedPassword, $phone);
    
            // Execute the query and return the result
            if ($stmt->execute()) {
                return true; // User account created successfully
            } else {
                // Log or handle errors as needed
                return false; // Failed to execute the insert query
            }
        }
    
        return false; // Return false if the query couldn't be prepared
    }
    
}


?>
